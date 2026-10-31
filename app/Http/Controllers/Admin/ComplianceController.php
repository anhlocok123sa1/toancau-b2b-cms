<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplianceChecklist;
use App\Models\SystemSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ComplianceController extends Controller
{
    /**
     * Display Compliance and Settings Matrix (Admin only)
     */
    public function index(Request $request): Response
    {
        $checklists = ComplianceChecklist::with('verifier:id,name')->get();

        $settings = SystemSetting::all()->pluck('value', 'key')->toArray();

        return Inertia::render('Admin/ToanCauAdminPortal', [
            'currentTab' => 'compliance',
            'complianceList' => $checklists,
            'systemSettings' => $settings,
        ]);
    }

    /**
     * Toggle Verification Status for a Checklist item
     */
    public function toggleVerification(Request $request, ComplianceChecklist $checklist): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        if (! $user->hasRole('Admin')) {
            abort(403, 'Chỉ Quản trị viên Tối cao (Admin) mới có thẩm quyền phê duyệt hạng mục Pháp lý.');
        }

        $newStatus = ! $checklist->is_verified;
        $checklist->is_verified = $newStatus;
        $checklist->verified_at = $newStatus ? now() : null;
        $checklist->verified_by = $newStatus ? $user->id : null;
        $checklist->save();

        $statusText = $newStatus ? 'ĐÃ PHÊ DUYỆT CÔNG KHAI' : 'HỦY DUYỆT (ẨN KHỎI PUBLIC)';
        $msg = "Hạng mục [{$checklist->title}] hiện ở trạng thái: {$statusText}.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_verified' => $checklist->is_verified,
                'verified_at' => $checklist->verified_at ? $checklist->verified_at->format('H:i d/m/Y') : null,
                'verifier_name' => $newStatus ? $user->name : null,
                'message' => $msg,
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Update GA4, GSC, and General Settings
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (! $user->hasRole('Admin')) {
            abort(403, 'Chỉ Quản trị viên Tối cao (Admin) mới có quyền đổi cấu hình hệ thống.');
        }

        $validated = $request->validate([
            'google_analytics_id' => 'nullable|string|max:50',
            'google_search_console_token' => 'nullable|string|max:200',
            'hotline' => 'nullable|string|max:100',
            'zalo_oa_url' => 'nullable|url|max:255',
            'show_infra_stats_home' => 'nullable|boolean',
        ]);

        foreach ($validated as $k => $v) {
            SystemSetting::updateOrCreate(
                ['key' => $k],
                ['value' => is_bool($v) ? ($v ? '1' : '0') : (string) $v]
            );
        }

        return redirect()->back()->with('success', 'Đã lưu cấu hình GA4, GSC và Hotline thành công!');
    }
}
