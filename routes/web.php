<?php

use App\Http\Controllers\Admin\AdminContentManagerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ComplianceController;
use App\Http\Controllers\Admin\LeadPortalController;
use App\Http\Controllers\Admin\ProductCmsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LeadSubmissionController;
use App\Http\Controllers\Public\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Toàn Cầu Technology B2B Portal)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/cloud-infrastructure', [CatalogController::class, 'cloudInfrastructure'])->name('public.cloud_infra');
Route::redirect('/cloud-business', '/cloud-infrastructure?tab=business', 301);
Route::get('/internet-connectivity', [CatalogController::class, 'internetConnectivity'])->name('public.internet');
Route::get('/smart-iot', [CatalogController::class, 'smartIot'])->name('public.smart_iot');
Route::get('/bluecom', [CatalogController::class, 'bluecom'])->name('public.bluecom');
Route::get('/technical-services', [CatalogController::class, 'technicalServices'])->name('public.tech_services');
Route::get('/doi-tac-dai-ly', [CatalogController::class, 'partner'])->name('public.partner');
Route::get('/ve-toan-cau', [CatalogController::class, 'about'])->name('public.about');
Route::get('/du-an-tieu-bieu', [CatalogController::class, 'projects'])->name('public.projects');
Route::get('/tin-tuc', [CatalogController::class, 'news'])->name('public.news');
Route::get('/tin-tuc/{slug}', [CatalogController::class, 'postDetail'])->name('public.post_detail');
Route::get('/lien-he', [CatalogController::class, 'contact'])->name('public.contact');
Route::get('/chinh-sach-bao-mat', [CatalogController::class, 'privacyPolicy'])->name('public.privacy_policy');

// Full Products Directory Catalog
Route::get('/san-pham', [CatalogController::class, 'allProducts'])->name('public.products');

// 11-Component Product Detail Page
Route::get('/san-pham/{slug}', [CatalogController::class, 'productDetail'])->name('public.product_detail');

// Lead capture submissions (throttled 10 per minute per IP)
Route::post('/lead-submission', [LeadSubmissionController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('public.lead_submit');

// Pure native XML Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('public.sitemap');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin CMS Routes (Protected by Auth & EnforceToanCauRbac)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'toancau.rbac'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.index');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // 1. Leads Management Portal (Content Admin is strictly blocked by middleware!)
    Route::middleware('toancau.rbac:Admin,Sales,Partner Manager,Viewer')->group(function () {
        Route::get('/leads', [LeadPortalController::class, 'index'])->name('admin.leads.index');
        Route::get('/leads-export', [LeadPortalController::class, 'exportCsv'])->name('admin.leads.export');
        Route::get('/leads/{lead}', [LeadPortalController::class, 'show'])->name('admin.leads.show');
        Route::get('/leads/{lead}/download', [LeadPortalController::class, 'downloadAttachment'])->name('admin.leads.download_attachment');
        Route::get('/leads/{lead}/contract-download', [LeadPortalController::class, 'downloadContractFile'])->name('admin.leads.download_contract');

        // Mutating routes (Viewer blocked by middleware automatically)
        Route::put('/leads/{lead}', [LeadPortalController::class, 'update'])->name('admin.leads.update');
        Route::post('/leads/{lead}/status', [LeadPortalController::class, 'updateStatus'])->name('admin.leads.update_status');
        Route::post('/leads/{lead}/contract', [LeadPortalController::class, 'updateContract'])->name('admin.leads.update_contract');
        Route::post('/leads/{lead}/notes', [LeadPortalController::class, 'addNote'])->name('admin.leads.add_note');
    });

    // 2. Product & Visual Content CMS (Admin & Content Admin)
    Route::middleware('toancau.rbac:Admin,Content Admin,Viewer')->group(function () {
        // Products (Chuẩn 11 thành phần B2B & Full Page Editor)
        Route::get('/products', [ProductCmsController::class, 'index'])->name('admin.products.index');
        Route::get('/products/create', [ProductCmsController::class, 'create'])->name('admin.products.create');
        Route::post('/products', [ProductCmsController::class, 'store'])->name('admin.products.store');
        Route::get('/products/{product}/edit', [ProductCmsController::class, 'edit'])->name('admin.products.edit');
        Route::post('/products/{product}', [AdminContentManagerController::class, 'updateProduct'])->name('admin.products.update_direct');
        Route::post('/products/{product}/update', [AdminContentManagerController::class, 'updateProduct'])->name('admin.products.update');
        Route::delete('/products/{product}', [AdminContentManagerController::class, 'destroyProduct'])->name('admin.products.destroy');
        Route::post('/products/{product}/toggle-price', [ProductCmsController::class, 'togglePrice'])->name('admin.products.toggle_price');
        Route::post('/products/{product}/toggle-featured', [ProductCmsController::class, 'toggleFeatured'])->name('admin.products.toggle_featured');
        Route::post('/products/{product}/toggle-active', [ProductCmsController::class, 'toggleActive'])->name('admin.products.toggle_active');

        // Projects (Dự án tiêu biểu)
        Route::post('/projects', [AdminContentManagerController::class, 'storeProject'])->name('admin.projects.store');
        Route::post('/projects/{project}', [AdminContentManagerController::class, 'updateProject'])->name('admin.projects.update');
        Route::delete('/projects/{project}', [AdminContentManagerController::class, 'destroyProject'])->name('admin.projects.destroy');
        Route::post('/projects/{project}/toggle-featured', [AdminContentManagerController::class, 'toggleProjectFeatured'])->name('admin.projects.toggle_featured');

        // Cloud VPS Plans (Quản lý cấu hình & so sánh)
        Route::post('/vps-plans', [AdminContentManagerController::class, 'storeVpsPlan'])->name('admin.vps_plans.store');
        Route::post('/vps-plans/{vpsPlan}', [AdminContentManagerController::class, 'updateVpsPlan'])->name('admin.vps_plans.update');
        Route::delete('/vps-plans/{vpsPlan}', [AdminContentManagerController::class, 'destroyVpsPlan'])->name('admin.vps_plans.destroy');

        // Categories & Core Sectors (6 Trụ Cột Cốt Lõi)
        Route::post('/categories', [AdminContentManagerController::class, 'storeCategory'])->name('admin.categories.store');
        Route::post('/categories/{category}', [AdminContentManagerController::class, 'updateCategory'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [AdminContentManagerController::class, 'destroyCategory'])->name('admin.categories.destroy');

        // Page Sections (Visual Zero-Code Content Manager)
        Route::post('/page-sections/{pageSection}', [AdminContentManagerController::class, 'updatePageSection'])->name('admin.page_sections.update');

        // News Posts (Tin tức & Bài viết)
        Route::post('/posts', [AdminContentManagerController::class, 'storePost'])->name('admin.posts.store');
        Route::post('/posts/{post}', [AdminContentManagerController::class, 'updatePost'])->name('admin.posts.update');
        Route::delete('/posts/{post}', [AdminContentManagerController::class, 'destroyPost'])->name('admin.posts.destroy');
    });

    // 3. Legal Compliance & System Settings (Admin Only!)
    Route::middleware('toancau.rbac:Admin')->group(function () {
        Route::get('/compliance', [ComplianceController::class, 'index'])->name('admin.compliance.index');
        Route::post('/compliance/{checklist}/toggle', [ComplianceController::class, 'toggleVerification'])->name('admin.compliance.toggle');
        Route::post('/settings', [ComplianceController::class, 'updateSettings'])->name('admin.settings.update');
    });
});
