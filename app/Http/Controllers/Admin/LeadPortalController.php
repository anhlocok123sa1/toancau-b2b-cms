<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LeadPortalController extends Controller
{
    /**
     * Display Lead Management Portal
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        if ($user->hasRole('Content Admin')) {
            abort(403, 'Quy chuẩn bảo mật Toàn Cầu: Vai trò Content Admin tuyệt đối bị chặn không được xem hoặc truy cập dữ liệu Lead bán hàng.');
        }

        $query = Lead::with(['product:id,name,model_code', 'assignee:id,name'])
            ->latest();

        // Filters
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($formType = $request->input('form_type')) {
            $query->where('form_type', $formType);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('sla_breach_only')) {
            $query->whereNotIn('status', [Lead::STATUS_CLOSED_WON, Lead::STATUS_CLOSED_LOST])
                ->where('sla_due_at', '<', now());
        }

        $leads = $query->paginate(15)->withQueryString();

        $salesUsers = User::role(['Admin', 'Sales', 'Partner Manager'])->get(['id', 'name', 'email']);

        return Inertia::render('Admin/ToanCauAdminPortal', [
            'currentTab' => 'leads',
            'leads' => $leads,
            'filters' => $request->only(['status', 'form_type', 'search', 'sla_breach_only']),
            'salesUsers' => $salesUsers,
            'statusLabels' => Lead::STATUS_LABELS,
            'formTypes' => Lead::FORM_TYPES,
        ]);
    }

    /**
     * Display Full Lead Detail & Contract Management Page
     */
    public function show(Request $request, Lead $lead): Response
    {
        $user = $request->user();
        if ($user->hasRole('Content Admin')) {
            abort(403, 'Quy chuẩn bảo mật Toàn Cầu: Vai trò Content Admin không có quyền truy cập Lead bán hàng.');
        }

        $lead->load(['product', 'assignee']);

        $salesUsers = User::role(['Admin', 'Sales', 'Partner Manager'])->get(['id', 'name', 'email']);

        return Inertia::render('Admin/LeadDetail', [
            'lead' => $lead,
            'salesUsers' => $salesUsers,
            'statusLabels' => Lead::STATUS_LABELS,
            'formTypes' => Lead::FORM_TYPES,
        ]);
    }

    /**
     * Update Customer Information from Lead Detail
     */
    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $user = $request->user();
        if ($user->hasRole('Viewer')) {
            abort(403, 'Tài khoản Viewer chỉ có quyền xem (Read-Only).');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'company_name' => 'nullable|string|max:200',
            'phone' => 'required|string|max:25',
            'email' => 'required|email|max:150',
            'city_province' => 'nullable|string|max:100',
            'interested_service' => 'nullable|string|max:200',
            'message' => 'nullable|string|max:3000',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|string',
            'closed_reason' => 'nullable|string|max:1000',
        ]);

        $lead->update($validated);

        $actor = $user->name . ' (' . ($user->roles->first()->name ?? 'Staff') . ')';
        $lead->appendTimelineNote($actor, 'Cập nhật thông tin khách hàng và tiến trình xử lý.');

        return redirect()->back()->with('success', 'Đã cập nhật thông tin yêu cầu của khách hàng thành công!');
    }

    /**
     * Update Contract & Deal Information
     */
    public function updateContract(Request $request, Lead $lead): RedirectResponse
    {
        $user = $request->user();
        if ($user->hasRole('Viewer')) {
            abort(403, 'Tài khoản Viewer chỉ có quyền xem (Read-Only).');
        }

        $validated = $request->validate([
            'contract_number' => 'nullable|string|max:100',
            'contract_value' => 'nullable|numeric|min:0',
            'contract_term' => 'nullable|string|max:100',
            'contract_payment_terms' => 'nullable|string|max:500',
            'contract_signed_at' => 'nullable|date',
            'contract_notes' => 'nullable|string|max:2000',
            'status' => 'nullable|string',
            'contract_file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:20480',
        ]);

        $contractData = [
            'contract_number' => $validated['contract_number'] ?? null,
            'contract_value' => $validated['contract_value'] ?? null,
            'contract_term' => $validated['contract_term'] ?? null,
            'contract_payment_terms' => $validated['contract_payment_terms'] ?? null,
            'contract_signed_at' => $validated['contract_signed_at'] ?? null,
            'contract_notes' => $validated['contract_notes'] ?? null,
        ];

        // Handle Contract File Upload into secure private storage
        if ($request->hasFile('contract_file')) {
            $file = $request->file('contract_file');
            $originalName = $file->getClientOriginalName();
            $safeFilename = 'contract_' . \Illuminate\Support\Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

            $privateDir = storage_path('app/private/contract_files');
            if (! \Illuminate\Support\Facades\File::isDirectory($privateDir)) {
                \Illuminate\Support\Facades\File::makeDirectory($privateDir, 0700, true, true);
            }

            $file->move($privateDir, $safeFilename);
            $contractData['contract_attachment_path'] = 'contract_files/' . $safeFilename;
            $contractData['contract_attachment_name'] = $originalName;
        }

        if (! empty($validated['status'])) {
            $contractData['status'] = $validated['status'];
        } elseif (! empty($validated['contract_signed_at']) && $lead->status !== Lead::STATUS_CLOSED_WON) {
            $contractData['status'] = Lead::STATUS_CLOSED_WON;
        }

        $lead->update($contractData);

        $actor = $user->name . ' (' . ($user->roles->first()->name ?? 'Staff') . ')';
        $noteMsg = 'Cập nhật thông tin hợp đồng';
        if (! empty($contractData['contract_number'])) {
            $noteMsg .= ' [' . $contractData['contract_number'] . ']';
        }
        if (! empty($contractData['contract_value'])) {
            $noteMsg .= ' - Trị giá: ' . number_format($contractData['contract_value'], 0, ',', '.') . ' VNĐ';
        }
        if (! empty($contractData['contract_signed_at'])) {
            $noteMsg .= ' (Ký ngày: ' . date('d/m/Y', strtotime($contractData['contract_signed_at'])) . ')';
        }
        $lead->appendTimelineNote($actor, $noteMsg);

        return redirect()->back()->with('success', 'Đã lưu hợp đồng và cập nhật tiến trình thành công!');
    }

    /**
     * Download Contract File securely
     */
    public function downloadContractFile(Request $request, Lead $lead): BinaryFileResponse
    {
        $user = $request->user();
        if ($user->hasRole('Content Admin')) {
            abort(403, 'Chặn quyền truy cập.');
        }

        if (! $lead->contract_attachment_path) {
            abort(404, 'Không có tệp hợp đồng đính kèm.');
        }

        $fullPath = storage_path('app/private/' . $lead->contract_attachment_path);
        if (! file_exists($fullPath)) {
            abort(404, 'Tệp tin không tồn tại trên hệ thống lưu trữ an toàn.');
        }

        return response()->download($fullPath, $lead->contract_attachment_name ?? basename($fullPath));
    }

    /**
     * Update Lead Status (Enforcing 5 lifecycle states + mandatory closed_reason)
     */
    public function updateStatus(Request $request, Lead $lead): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        if ($user->hasRole('Viewer')) {
            abort(403, 'Tài khoản Viewer chỉ có quyền xem (Read-Only).');
        }

        $validated = $request->validate([
            'status' => 'required|in:1_new,2_acknowledged,3_in_progress,4_quoted,5_negotiating,6_closed_won,7_closed_lost,5_closed_won,5_closed_lost',
            'closed_reason' => 'required_if:status,7_closed_lost,5_closed_lost|nullable|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
            'note' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $lead->status;
        $newStatus = $validated['status'];

        $lead->status = $newStatus;

        if (array_key_exists('assigned_to', $validated)) {
            $lead->assigned_to = $validated['assigned_to'];
        }

        if (in_array($newStatus, [Lead::STATUS_CLOSED_LOST, '5_closed_lost'])) {
            $lead->closed_reason = $validated['closed_reason'];
        } elseif (in_array($newStatus, [Lead::STATUS_CLOSED_WON, '5_closed_won'])) {
            $lead->closed_reason = null;
        }

        $actor = $user->name.' ('.($user->roles->first()->name ?? 'Staff').')';
        $noteText = 'Chuyển trạng thái từ '.(Lead::STATUS_LABELS[$oldStatus] ?? $oldStatus).' sang '.(Lead::STATUS_LABELS[$newStatus] ?? $newStatus);

        if (! empty($validated['note'])) {
            $noteText .= '. Ghi chú: '.$validated['note'];
        }
        if ($newStatus === Lead::STATUS_CLOSED_LOST) {
            $noteText .= '. Lý do không thành công: '.$validated['closed_reason'];
        }

        $lead->appendTimelineNote($actor, $noteText);

        $msg = "Đã cập nhật trạng thái Lead [{$lead->code}] thành công!";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'lead' => $lead->fresh(['assignee', 'product']),
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Add Consultation Note / Activity Log to Lead Timeline
     */
    public function addNote(Request $request, Lead $lead): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        if ($user->hasRole('Viewer')) {
            abort(403, 'Tài khoản Viewer chỉ có quyền xem (Read-Only).');
        }

        $validated = $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        $actor = $user->name.' ('.($user->roles->first()->name ?? 'Staff').')';
        $lead->appendTimelineNote($actor, $validated['note']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'notes' => $lead->notes]);
        }

        return redirect()->back()->with('success', 'Đã thêm ghi chú tư vấn!');
    }

    /**
     * Secure Download for Lead Private Attachment
     */
    public function downloadAttachment(Request $request, Lead $lead): BinaryFileResponse
    {
        $user = $request->user();
        if ($user->hasRole('Content Admin')) {
            abort(403, 'Chặn quyền truy cập.');
        }

        if (! $lead->attachment_path) {
            abort(404, 'Không có tệp đính kèm.');
        }

        $fullPath = storage_path('app/private/'.$lead->attachment_path);
        if (! file_exists($fullPath)) {
            abort(404, 'Tệp tin không tồn tại trên hệ thống lưu trữ an toàn.');
        }

        return response()->download($fullPath, $lead->attachment_name ?? basename($fullPath));
    }

    /**
     * Export Leads to CSV format
     */
    public function exportCsv(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('Content Admin')) {
            abort(403, 'Content Admin không có quyền xuất dữ liệu Lead.');
        }

        $leads = Lead::with('assignee')->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="ToanCau_Leads_'.date('Ymd_His').'.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($leads) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel in Vietnamese
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Mã Lead',
                'Loại Biểu Mẫu',
                'Họ Tên Khách Hàng',
                'Doanh Nghiệp',
                'Số Điện Thoại',
                'Email',
                'Tỉnh / Thành Phố',
                'Dịch Vụ Quan Tâm',
                'Trạng Thái',
                'Hạn SLA 30 Phút',
                'Nhân Viên Phụ Trách',
                'Lý Do Thất Bại',
                'Thời Gian Tạo',
            ]);

            foreach ($leads as $l) {
                fputcsv($handle, [
                    $l->code,
                    Lead::FORM_TYPES[$l->form_type] ?? $l->form_type,
                    $l->full_name,
                    $l->company_name ?? '',
                    $l->phone,
                    $l->email,
                    $l->city_province ?? '',
                    $l->interested_service ?? '',
                    Lead::STATUS_LABELS[$l->status] ?? $l->status,
                    $l->sla_due_at ? $l->sla_due_at->format('H:i d/m/Y') : '',
                    $l->assignee->name ?? 'Chưa phân công',
                    $l->closed_reason ?? '',
                    $l->created_at->format('H:i d/m/Y'),
                ]);
            }

            fclose($handle);
        };

        return FacadeResponse::stream($callback, 200, $headers);
    }
}
