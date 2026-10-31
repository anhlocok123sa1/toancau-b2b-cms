<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplianceChecklist;
use App\Models\Lead;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class AdminDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $isContentAdmin = $user->hasRole('Content Admin');

        // Lead statistics (Strictly suppressed for Content Admin!)
        $leadStats = null;
        if (! $isContentAdmin) {
            $totalLeads = Lead::count();
            $newLeads = Lead::where('status', Lead::STATUS_NEW)->count();
            $acknowledged = Lead::where('status', Lead::STATUS_ACKNOWLEDGED)->count();
            $inProgress = Lead::where('status', Lead::STATUS_IN_PROGRESS)->count();
            $quoted = Lead::where('status', Lead::STATUS_QUOTED)->count();
            $closedWon = Lead::where('status', Lead::STATUS_CLOSED_WON)->count();
            $closedLost = Lead::where('status', Lead::STATUS_CLOSED_LOST)->count();

            // SLA breaches (Pending leads past due date)
            $slaBreached = Lead::whereNotIn('status', [Lead::STATUS_CLOSED_WON, Lead::STATUS_CLOSED_LOST])
                ->where('sla_due_at', '<', now())
                ->count();

            $leadStats = [
                'total' => $totalLeads,
                'new' => $newLeads,
                'acknowledged' => $acknowledged,
                'in_progress' => $inProgress,
                'quoted' => $quoted,
                'closed_won' => $closedWon,
                'closed_lost' => $closedLost,
                'sla_breached' => $slaBreached,
            ];
        }

        // Product stats
        $productStats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'featured' => Product::where('is_featured', true)->count(),
        ];

        // Compliance Checklist status (6 mandatory items)
        $complianceList = ComplianceChecklist::with('verifier:id,name')->get();
        $verifiedCount = $complianceList->where('is_verified', true)->count();

        // Recent Audit logs (Admin only or General)
        $recentActivities = collect();
        if ($user->hasRole('Admin')) {
            $recentActivities = Activity::with('causer:id,name')
                ->latest()
                ->limit(10)
                ->get()
                ->map(fn ($act) => [
                    'id' => $act->id,
                    'description' => $act->description,
                    'subject_type' => class_basename($act->subject_type),
                    'causer_name' => $act->causer->name ?? 'System',
                    'created_at' => $act->created_at->format('H:i d/m/Y'),
                ]);
        }

        return Inertia::render('Admin/ToanCauAdminPortal', [
            'currentTab' => 'dashboard',
            'leadStats' => $leadStats,
            'productStats' => $productStats,
            'complianceList' => $complianceList,
            'complianceSummary' => [
                'total' => 6,
                'verified' => $verifiedCount,
            ],
            'recentActivities' => $recentActivities,
        ]);
    }
}
