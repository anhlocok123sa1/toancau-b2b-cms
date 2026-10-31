<?php

namespace App\Http\Middleware;

use App\Models\ComplianceChecklist;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        // 6 Compliance verification flags
        $complianceVerified = [
            'legal_corporate_profile' => ComplianceChecklist::isItemVerified('legal_corporate_profile'),
            'ip_resource_4000' => ComplianceChecklist::isItemVerified('ip_resource_4000'),
            'starlink_reseller_capability' => ComplianceChecklist::isItemVerified('starlink_reseller_capability'),
            'bluecom_distribution_status' => ComplianceChecklist::isItemVerified('bluecom_distribution_status'),
            'product_datasheets_warranty' => ComplianceChecklist::isItemVerified('product_datasheets_warranty'),
            'lead_sla_privacy_nd13' => ComplianceChecklist::isItemVerified('lead_sla_privacy_nd13'),
        ];

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(),
                    'is_admin' => $user->hasRole('Admin'),
                    'is_sales' => $user->hasRole('Sales'),
                    'is_partner_mgr' => $user->hasRole('Partner Manager'),
                    'is_content_admin' => $user->hasRole('Content Admin'),
                    'is_viewer' => $user->hasRole('Viewer'),
                ] : null,
            ],
            'compliance' => $complianceVerified,
            'settings' => [
                'hotline' => SystemSetting::get('hotline', '1900 6868'),
                'hotline_raw' => SystemSetting::get('hotline_raw', '19006868'),
                'zalo_oa_url' => SystemSetting::get('zalo_oa_url', 'https://zalo.me/toancautech'),
                'email_support' => SystemSetting::get('email_support', 'support@toancau.vn'),
                'email_lead' => SystemSetting::get('email_lead', 'lead@toancau.vn'),
                'show_infra_stats_home' => (bool) SystemSetting::get('show_infra_stats_home', true),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'lead_code' => fn () => $request->session()->get('lead_code'),
                'sla_due' => fn () => $request->session()->get('sla_due'),
            ],
        ];
    }
}
