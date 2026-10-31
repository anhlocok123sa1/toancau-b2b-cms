<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\VpsPlan;
use App\Services\ImageWebpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductCmsController extends Controller
{
    public function __construct(protected ImageWebpService $imageService) {}

    /**
     * List products and visual content in Admin CMS
     */
    public function index(Request $request): Response
    {
        $query = Product::with('category')->latest();

        if ($sector = $request->input('sector_code')) {
            $query->where('sector_code', $sector);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('model_code', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->with('parent')->orderBy('display_order')->get();
        $projects = Project::orderBy('display_order')->get();
        $vpsPlans = VpsPlan::orderBy('display_order')->get();
        $pageSections = PageSection::orderBy('display_order')->get()->groupBy('page_key');
        $posts = Post::latest()->get();

        return Inertia::render('Admin/ToanCauAdminPortal', [
            'currentTab' => 'products',
            'products' => $products,
            'categories' => $categories,
            'projects' => $projects,
            'vpsPlans' => $vpsPlans,
            'pageSections' => $pageSections,
            'posts' => $posts,
            'filters' => $request->only(['sector_code', 'search']),
        ]);
    }

    /**
     * Create product page (Full Page Editor)
     */
    public function create(Request $request): Response
    {
        $categories = Category::with('parent')->orderBy('display_order')->get();
        $otherProducts = Product::orderBy('name')->get(['id', 'name', 'model_code', 'sector_code']);

        return Inertia::render('Admin/ProductEdit', [
            'product' => null,
            'categories' => $categories,
            'otherProducts' => $otherProducts,
            'prefillSector' => $request->query('sector_code', 'smart_iot'),
        ]);
    }

    /**
     * Edit product page (Full Page Editor for 11 Components)
     */
    public function edit(Product $product): Response
    {
        $categories = Category::with('parent')->orderBy('display_order')->get();
        $otherProducts = Product::where('id', '!=', $product->id)->orderBy('name')->get(['id', 'name', 'model_code', 'sector_code']);

        return Inertia::render('Admin/ProductEdit', [
            'product' => $product->load('category'),
            'categories' => $categories,
            'otherProducts' => $otherProducts,
        ]);
    }

    /**
     * Quick toggle price visibility (Component [11])
     */
    public function togglePrice(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $this->ensureCanMutate($request);

        $product->is_price_visible = ! $product->is_price_visible;
        $product->save();

        $state = $product->is_price_visible ? 'HIỂN THỊ GIÁ NIÊM YẾT' : 'CHUYỂN SANG NÚT "LIÊN HỆ"';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_price_visible' => $product->is_price_visible,
                'message' => "Đã chuyển sản phẩm sang chế độ {$state}",
            ]);
        }

        return redirect()->back()->with('success', "Đã chuyển sản phẩm sang chế độ {$state}");
    }

    /**
     * Quick toggle featured status (for Home showcase)
     */
    public function toggleFeatured(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $this->ensureCanMutate($request);

        $product->is_featured = ! $product->is_featured;
        $product->save();

        $state = $product->is_featured ? 'NỔI BẬT TRANG CHỦ' : 'BÌNH THƯỜNG';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_featured' => $product->is_featured,
                'message' => "Sản phẩm đã được gắn trạng thái {$state}",
            ]);
        }

        return redirect()->back()->with('success', "Sản phẩm đã được gắn trạng thái {$state}");
    }

    /**
     * Quick toggle active status
     */
    public function toggleActive(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $this->ensureCanMutate($request);

        $product->is_active = ! $product->is_active;
        $product->save();

        $state = $product->is_active ? 'KÍCH HOẠT' : 'TẠM ẨN';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $product->is_active,
                'message' => "Sản phẩm hiện đã được {$state}",
            ]);
        }

        return redirect()->back()->with('success', "Sản phẩm hiện đã được {$state}");
    }

    /**
     * Store a new Product with 11 Components
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sector_code' => 'required|string',
            'name' => 'required|string|max:255',
            'model_code' => 'required|string|max:100',
            'badge' => 'nullable|string|max:255',
            'overview' => 'required|string',
            'warranty_policy' => 'nullable|string',
            'support_commitment' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'price_unit' => 'nullable|string',
            'is_price_visible' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'thumbnail_file' => 'nullable|image|max:5120',
            'datasheet_file' => 'nullable|file|mimes:pdf|max:15360',
            'specs_json' => 'nullable|string',
            'features_json' => 'nullable|string',
            'scenarios_json' => 'nullable|string',
            'specs' => 'nullable|array',
            'features' => 'nullable|array',
            'application_scenarios' => 'nullable|array',
            'related_product_ids' => 'nullable|array',
        ]);

        $slug = Str::slug($validated['name']).'-'.Str::lower($validated['model_code']);

        $thumbnailUrl = '/images/products/default-hardware.webp';
        if ($request->hasFile('thumbnail_file')) {
            $thumbnailUrl = $this->imageService->convertToWebp($request->file('thumbnail_file'), 'products');
        }

        $datasheetPath = null;
        if ($request->hasFile('datasheet_file')) {
            $file = $request->file('datasheet_file');
            $dsName = Str::slug($validated['model_code']).'-datasheet.pdf';
            $file->move(public_path('datasheets'), $dsName);
            $datasheetPath = '/datasheets/'.$dsName;
        }

        // Process specs
        $specs = $request->input('specs');
        if (is_string($specs)) {
            $specs = json_decode($specs, true);
        }
        if (! is_array($specs)) {
            $specs = ! empty($validated['specs_json']) ? json_decode($validated['specs_json'], true) : [];
        }

        // Process features
        $features = $request->input('features');
        if (is_string($features)) {
            $features = json_decode($features, true);
        }
        if (! is_array($features)) {
            $features = ! empty($validated['features_json']) ? json_decode($validated['features_json'], true) : [];
        }

        // Process application scenarios
        $scenarios = $request->input('application_scenarios');
        if (is_string($scenarios)) {
            $scenarios = json_decode($scenarios, true);
        }
        if (! is_array($scenarios)) {
            $scenarios = ! empty($validated['scenarios_json']) ? json_decode($validated['scenarios_json'], true) : ['Doanh nghiệp, Nhà xưởng, Hạ tầng mạng'];
        }

        Product::create([
            'category_id' => $validated['category_id'],
            'sector_code' => $validated['sector_code'],
            'name' => $validated['name'],
            'slug' => $slug,
            'model_code' => $validated['model_code'],
            'badge' => $validated['badge'] ?: 'Sản Phẩm B2B Tiêu Chuẩn',
            'thumbnail' => $thumbnailUrl,
            'gallery' => [$thumbnailUrl],
            'overview' => $validated['overview'],
            'features' => $features ?: [
                ['icon' => 'ShieldCheckIcon', 'title' => 'Chính Hãng 100%', 'desc' => 'Đầy đủ CO/CQ từ nhà sản xuất'],
            ],
            'specs' => $specs ?: [
                ['label' => 'Mã Model', 'value' => $validated['model_code']],
            ],
            'application_scenarios' => $scenarios ?: ['Doanh nghiệp, Nhà xưởng, Hạ tầng mạng'],
            'datasheet_pdf_path' => $datasheetPath,
            'warranty_policy' => $validated['warranty_policy'] ?? '24 tháng 1 đổi 1 trong 30 ngày',
            'support_commitment' => $validated['support_commitment'] ?? 'Hỗ trợ kỹ thuật 24/7/365, SLA 15-30 phút',
            'is_price_visible' => (bool) ($validated['is_price_visible'] ?? false),
            'price' => $validated['price'] ?? null,
            'price_unit' => $validated['price_unit'] ?? 'tháng',
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'related_product_ids' => $request->input('related_product_ids', []),
        ]);

        return redirect()->route('admin.products.index')->with('success', "Đã thêm mới sản phẩm [{$validated['name']}] chuẩn 11 thành phần B2B thành công!");
    }

    protected function ensureCanMutate(Request $request): void
    {
        if ($request->user()->hasRole('Viewer')) {
            abort(403, 'Tài khoản Viewer chỉ có quyền xem (Read-Only).');
        }
    }
}
