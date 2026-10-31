<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ComplianceChecklist;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\VpsPlan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    /**
     * All Products Catalog & Directory Page (/san-pham)
     */
    public function allProducts(Request $request): Response
    {
        $sector = $request->query('sector', 'all');
        $categorySlug = $request->query('category', 'all');
        $search = $request->query('q', '');

        $query = Product::with('category')->where('is_active', true);

        if ($sector !== 'all') {
            $query->where('sector_code', $sector);
        }

        if ($categorySlug !== 'all') {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('model_code', 'like', "%{$search}%")
                    ->orWhere('overview', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('is_featured', 'desc')->orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('display_order')->get();

        return Inertia::render('Public/AllProducts', [
            'products' => $products,
            'categories' => $categories,
            'currentSector' => $sector,
            'currentCategory' => $categorySlug,
            'searchQuery' => $search,
        ]);
    }

    /**
     * 1. Cloud & Infrastructure Page
     */
    public function cloudInfrastructure(Request $request): Response
    {
        $vpsPlans = VpsPlan::orderBy('display_order')->get();

        $dedicatedServers = Product::where('sector_code', 'cloud_infra')
            ->where('slug', 'like', '%dedicated%')
            ->where('is_active', true)
            ->get();

        $ipServices = Product::where('sector_code', 'cloud_infra')
            ->where('slug', 'like', '%ipv4%')
            ->where('is_active', true)
            ->get();

        $isIpVerified = ComplianceChecklist::isItemVerified('ip_resource_4000');
        $cloudSections = PageSection::getPageSections('cloud');

        return Inertia::render('Public/CloudInfrastructure', [
            'vpsPlans' => $vpsPlans,
            'dedicatedServers' => $dedicatedServers,
            'ipServices' => $ipServices,
            'isIpVerified' => $isIpVerified,
            'cloudSections' => $cloudSections,
            'activeTab' => $request->query('tab', 'vps'),
        ]);
    }

    /**
     * 2. Internet & Connectivity Page
     */
    public function internetConnectivity(): Response
    {
        $products = Product::where('sector_code', 'internet')
            ->where('is_active', true)
            ->get();

        // Checklist 3: Starlink status check
        $isStarlinkVerified = ComplianceChecklist::isItemVerified('starlink_reseller_capability');
        $internetSections = PageSection::getPageSections('internet');

        return Inertia::render('Public/InternetConnectivity', [
            'products' => $products,
            'isStarlinkVerified' => $isStarlinkVerified,
            'internetSections' => $internetSections,
        ]);
    }

    /**
     * 3. Smart IoT Page (Dynamic sub-categories & filtering)
     */
    public function smartIot(Request $request): Response
    {
        $iotCategory = Category::where('sector_code', 'smart_iot')->whereNull('parent_id')->first();

        // Retrieve all active sub-categories under Smart IoT
        $subCategories = Category::where('sector_code', 'smart_iot')
            ->when($iotCategory, fn ($q) => $q->where('id', '!=', $iotCategory->id))
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();

        // Eager load category to support instantaneous client and server-side filtering
        $products = Product::where('sector_code', 'smart_iot')
            ->where('is_active', true)
            ->with('category')
            ->get();

        $iotSections = PageSection::getPageSections('smart_iot');

        return Inertia::render('Public/SmartIot', [
            'subCategories' => $subCategories,
            'products' => $products,
            'iotSections' => $iotSections,
            'activeSubCategory' => $request->query('sub', 'all'),
        ]);
    }

    /**
     * 4. Bluecom Page
     */
    public function bluecom(): Response
    {
        $products = Product::where('sector_code', 'bluecom')
            ->where('is_active', true)
            ->get();

        // Checklist 4: Bluecom distribution status check
        $isBluecomVerified = ComplianceChecklist::isItemVerified('bluecom_distribution_status');
        $bluecomSections = PageSection::getPageSections('bluecom');

        return Inertia::render('Public/Bluecom', [
            'products' => $products,
            'isBluecomVerified' => $isBluecomVerified,
            'bluecomSections' => $bluecomSections,
        ]);
    }

    /**
     * 5. Technical Services Page
     */
    public function technicalServices(): Response
    {
        $techSections = PageSection::getPageSections('tech_services');

        return Inertia::render('Public/TechnicalServices', [
            'techSections' => $techSections,
        ]);
    }

    /**
     * 6. Partner & Dealer Page
     */
    public function partner(): Response
    {
        return Inertia::render('Public/PartnerRegistration');
    }

    /**
     * 7. About Company & Capacities
     */
    public function about(): Response
    {
        $isLegalVerified = ComplianceChecklist::isItemVerified('legal_corporate_profile');
        $legalProfile = ComplianceChecklist::getSafeItemMetadata('legal_corporate_profile') ?? [];
        $aboutSections = PageSection::getPageSections('about');

        return Inertia::render('Public/AboutCompany', [
            'isLegalVerified' => $isLegalVerified,
            'legalProfile' => $legalProfile,
            'aboutSections' => $aboutSections,
        ]);
    }

    /**
     * Projects Showcase
     */
    public function projects(): Response
    {
        $projects = Project::orderBy('display_order')->get();

        return Inertia::render('Public/Projects', [
            'projects' => $projects,
        ]);
    }

    /**
     * News & Technical Insights
     */
    public function news(): Response
    {
        $posts = Post::where('is_published', true)
            ->orderByDesc('published_at')
            ->paginate(9);

        return Inertia::render('Public/News', [
            'posts' => $posts,
        ]);
    }

    /**
     * News Article Detail
     */
    public function postDetail(string $slug): Response
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $relatedPosts = Post::where('id', '!=', $post->id)
            ->where('is_published', true)
            ->limit(3)
            ->get();

        return Inertia::render('Public/PostDetail', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    /**
     * Contact Page
     */
    public function contact(): Response
    {
        $legalProfile = ComplianceChecklist::getSafeItemMetadata('legal_corporate_profile') ?? [];

        return Inertia::render('Public/Contact', [
            'legalProfile' => $legalProfile,
        ]);
    }

    /**
     * Decree 13/2023/ND-CP Privacy Policy
     */
    public function privacyPolicy(): Response
    {
        return Inertia::render('Public/PrivacyPolicy');
    }

    /**
     * 8. Product / Service Detail Page with 11 Mandatory Components
     */
    public function productDetail(string $slug): Response
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        // [01] Breadcrumb items
        $breadcrumbs = [
            ['name' => 'Trang chủ', 'url' => url('/')],
            ['name' => $product->category->name ?? 'Sản phẩm', 'url' => url('/'.str_replace('_', '-', $product->sector_code))],
            ['name' => $product->name, 'url' => url()->current()],
        ];

        // JSON-LD BreadcrumbList
        $jsonLdBreadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(function ($item, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'],
                    'item' => $item['url'],
                ];
            }, $breadcrumbs, array_keys($breadcrumbs)),
        ];

        // [10] Related Products
        $related = $product->related_products;

        return Inertia::render('Public/ProductDetail11Step', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name, // [02]
                'slug' => $product->slug,
                'model_code' => $product->model_code, // [02]
                'badge' => $product->badge ?: 'Sản Phẩm B2B Tiêu Chuẩn',
                'category_name' => $product->category->name ?? 'Sản Phẩm B2B',
                'thumbnail' => $product->thumbnail,
                'gallery' => $product->gallery ?? [$product->thumbnail], // [03]
                'overview' => $product->overview, // [04]
                'features' => $product->features ?? [], // [05]
                'specs' => $product->specs ?? [], // [06]
                'application_scenarios' => $product->application_scenarios ?? [], // [07]
                'datasheet_pdf_path' => $product->datasheet_pdf_path, // [08]
                'warranty_policy' => $product->warranty_policy, // [09]
                'support_commitment' => $product->support_commitment, // [09]
                'is_price_visible' => $product->is_price_visible, // [11]
                'price' => $product->price, // [11]
                'price_unit' => $product->price_unit,
                'sector_code' => $product->sector_code,
                'meta_title' => $product->meta_title ?? ($product->name.' | Toàn Cầu Tech'),
                'meta_description' => $product->meta_description ?? substr(strip_tags($product->overview), 0, 160),
            ],
            'breadcrumbs' => $breadcrumbs, // [01]
            'jsonLdBreadcrumbs' => $jsonLdBreadcrumbs, // [01]
            'relatedProducts' => $related, // [10]
        ]);
    }
}
