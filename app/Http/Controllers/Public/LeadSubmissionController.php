<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LeadSubmissionController extends Controller
{
    /**
     * Submit lead from any of the 3 standard forms
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $formType = $request->input('form_type', 'consultation_quote');

        // Common validation rules
        $rules = [
            'form_type' => 'required|in:consultation_quote,partner_registration,technical_service',
            'full_name' => 'required|string|max:120',
            'phone' => 'required|string|max:20|regex:/^[0-9\+\-\s\(\)]+$/',
            'data_consent' => 'accepted', // Decree 13/2023/ND-CP compliance
        ];

        // Specific form validations
        if ($formType === 'consultation_quote') {
            $rules['email'] = 'required|email|max:150';
            $rules['company_name'] = 'nullable|string|max:200';
            $rules['interested_service'] = 'nullable|string|max:200';
            $rules['product_id'] = 'nullable|exists:products,id';
            $rules['message'] = 'nullable|string|max:2000';
        } elseif ($formType === 'partner_registration') {
            $rules['email'] = 'required|email|max:150';
            $rules['company_name'] = 'required|string|max:200';
            $rules['city_province'] = 'required|string|max:100';
            $rules['interested_service'] = 'required|string|max:200';
            $rules['message'] = 'nullable|string|max:2000';
            $rules['attachment'] = 'nullable|file|mimes:pdf,doc,docx,zip|max:10240'; // <= 10MB
        } elseif ($formType === 'technical_service') {
            $rules['email'] = 'nullable|email|max:150';
            $rules['city_province'] = 'required|string|max:200'; // Dia diem / cong trinh
            $rules['interested_service'] = 'required|string|max:100'; // Phan loai dich vu
            $rules['message'] = 'required|string|max:3000'; // Mo ta su co
            $rules['attachment'] = 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240'; // <= 10MB
        }

        $validated = $request->validate($rules);

        // Handle attachment file storage into storage/app/private/lead_attachments/
        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $safeFilename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();

            $privateDir = storage_path('app/private/lead_attachments');
            if (! File::isDirectory($privateDir)) {
                File::makeDirectory($privateDir, 0700, true, true);
            }

            $file->move($privateDir, $safeFilename);
            $attachmentPath = 'lead_attachments/'.$safeFilename;
        }

        // Generate Lead unique code: TC-LEAD-YYYYMM-XXXX
        $leadCountThisMonth = Lead::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $code = sprintf('TC-LEAD-%s-%04d', now()->format('Ym'), $leadCountThisMonth + 1);

        // 30-minute SLA deadline
        $slaDueAt = Carbon::now()->addMinutes(30);

        // Store Lead to DB
        $lead = Lead::create([
            'form_type' => $formType,
            'code' => $code,
            'full_name' => $validated['full_name'],
            'company_name' => $validated['company_name'] ?? null,
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? 'no-email@toancau.vn',
            'city_province' => $validated['city_province'] ?? null,
            'interested_service' => $validated['interested_service'] ?? null,
            'product_id' => $validated['product_id'] ?? null,
            'message' => $validated['message'] ?? null,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'data_consent' => true,
            'status' => Lead::STATUS_NEW,
            'sla_due_at' => $slaDueAt,
            'notes' => [
                [
                    'time' => now()->toIso8601String(),
                    'actor' => 'Hệ thống tiếp nhận tự động',
                    'text' => 'Khách hàng gửi yêu cầu qua biểu mẫu: '.Lead::FORM_TYPES[$formType].'. Cam kết SLA phản hồi trước '.$slaDueAt->format('H:i d/m/Y').'.',
                ],
            ],
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        // Step 4 & 5: Internal Notification & Autoresponder Email simulation
        $this->dispatchLeadNotifications($lead);

        $successMessage = "Yêu cầu của quý khách đã được tiếp nhận thành công với mã tra cứu: [{$lead->code}]. Chuyên viên kỹ thuật Toàn Cầu sẽ liên hệ trong vòng 15-30 phút!";

        // If submitted via Inertia form, return redirect back with session flash so Inertia renders the professional in-modal success view
        if ($request->header('X-Inertia')) {
            return redirect()->back()->with([
                'success' => $successMessage,
                'lead_code' => $lead->code,
                'sla_due' => $slaDueAt->format('H:i d/m/Y'),
            ]);
        }

        // Only return raw JSON for external API callers who specifically request JSON without Inertia
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'code' => $lead->code,
                'message' => $successMessage,
                'sla_due' => $slaDueAt->toIso8601String(),
            ]);
        }

        return redirect()->back()->with([
            'success' => $successMessage,
            'lead_code' => $lead->code,
            'sla_due' => $slaDueAt->format('H:i d/m/Y'),
        ]);
    }

    /**
     * Dispatch internal notification & customer autoresponder
     */
    protected function dispatchLeadNotifications(Lead $lead): void
    {
        try {
            // Log for audit / SLA monitoring
            Log::info("New B2B Lead Received: [{$lead->code}] Type: {$lead->form_type} Name: {$lead->full_name} Phone: {$lead->phone} SLA: {$lead->sla_due_at}");

            // Autoresponder mail logic (Logs driver by default)
            $recipient = $lead->email;
            if (filter_var($recipient, FILTER_VALIDATE_EMAIL) && ! str_contains($recipient, 'no-email')) {
                Mail::raw(
                    "Kính gửi {$lead->full_name},\n\n"
                    ."Toàn Cầu Technology đã tiếp nhận yêu cầu của Quý khách (Mã tra cứu: {$lead->code}).\n"
                    ."Nội dung quan tâm: {$lead->interested_service}\n"
                    ."Cam kết mức độ dịch vụ (SLA): Chuyên viên giải pháp sẽ phản hồi trong tối đa 15-30 phút.\n\n"
                    ."Hotline hỗ trợ 24/7: 1900 6868\n"
                    ."Trân trọng,\nBan Khách hàng Doanh nghiệp - Toàn Cầu Technology.",
                    function ($message) use ($lead, $recipient) {
                        $message->to($recipient)
                            ->subject("[Toàn Cầu Tech] Tiếp nhận yêu cầu [{$lead->code}] - Cam kết phản hồi trong 30 phút");
                    }
                );
            }
        } catch (\Throwable $e) {
            Log::warning("Lead notification error: {$e->getMessage()}");
        }
    }
}
