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

class AdminContentManagerController extends Controller
{
    public function __construct(protected ImageWebpService $imageService) {}

    /*
    |--------------------------------------------------------------------------
    | 1. PROJECTS CRUD (Dự án tiêu biểu & Khách hàng)
    |--------------------------------------------------------------------------
    */
    public function storeProject(Request $request): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'sector_code' => 'required|string',
            'description' => 'required|string',
            'is_featured' => 'boolean',
            'image_file' => 'nullable|image|max:5120',
        ]);

        $imageUrl = '/images/projects/project-default.webp';
        if ($request->hasFile('image_file')) {
            $imageUrl = $this->imageService->convertToWebp($request->file('image_file'), 'projects');
        }

        Project::create([
            'title' => $validated['title'],
            'client_name' => $validated['client_name'],
            'sector_code' => $validated['sector_code'],
            'image_url' => $imageUrl,
            'description' => $validated['description'],
            'is_featured' => (bool) ($validated['is_featured'] ?? true),
            'display_order' => Project::max('display_order') + 1,
        ]);

        return redirect()->back()->with('success', "Đã thêm dự án [{$validated['title']}] thành công!");
    }

    public function updateProject(Request $request, Project $project): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'sector_code' => 'required|string',
            'description' => 'required|string',
            'is_featured' => 'boolean',
            'image_file' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image_file')) {
            $project->image_url = $this->imageService->convertToWebp($request->file('image_file'), 'projects');
        }

        $project->update([
            'title' => $validated['title'],
            'client_name' => $validated['client_name'],
            'sector_code' => $validated['sector_code'],
            'description' => $validated['description'],
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
        ]);

        return redirect()->back()->with('success', "Đã cập nhật dự án [{$project->title}] thành công!");
    }

    public function destroyProject(Request $request, Project $project): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $title = $project->title;
        $project->delete();

        return redirect()->back()->with('success', "Đã xóa dự án [{$title}]!");
    }

    public function toggleProjectFeatured(Request $request, Project $project): JsonResponse|RedirectResponse
    {
        $this->ensureCanMutate($request);

        $project->is_featured = ! $project->is_featured;
        $project->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_featured' => $project->is_featured,
                'message' => 'Đã đổi trạng thái nổi bật dự án.',
            ]);
        }

        return redirect()->back()->with('success', 'Đã đổi trạng thái nổi bật dự án.');
    }

    /*
    |--------------------------------------------------------------------------
    | 2. VPS PLANS CRUD (Gói Cloud VPS & Ma trận so sánh)
    |--------------------------------------------------------------------------
    */
    public function storeVpsPlan(Request $request): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vcpu' => 'required|integer|min:1',
            'ram_gb' => 'required|integer|min:1',
            'storage_nvme_gb' => 'required|integer|min:10',
            'bandwidth' => 'required|string',
            'port_speed' => 'required|string',
            'ipv4_count' => 'required|integer|min:1',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'discount_percent_yearly' => 'nullable|integer|min:0|max:100',
            'features_text' => 'nullable|string',
            'is_popular' => 'boolean',
        ]);

        $features = array_filter(array_map('trim', explode("\n", $validated['features_text'] ?? '')));

        VpsPlan::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'vcpu' => $validated['vcpu'],
            'ram_gb' => $validated['ram_gb'],
            'storage_nvme_gb' => $validated['storage_nvme_gb'],
            'bandwidth' => $validated['bandwidth'],
            'port_speed' => $validated['port_speed'],
            'ipv4_count' => $validated['ipv4_count'],
            'price_monthly' => $validated['price_monthly'],
            'price_yearly' => $validated['price_yearly'],
            'discount_percent_yearly' => $validated['discount_percent_yearly'] ?? 15,
            'features' => $features ?: ['Anti-DDoS miễn phí', 'SLA 99.95%'],
            'is_popular' => (bool) ($validated['is_popular'] ?? false),
            'display_order' => VpsPlan::max('display_order') + 1,
        ]);

        return redirect()->back()->with('success', "Đã tạo gói Cloud VPS [{$validated['name']}] thành công!");
    }

    public function updateVpsPlan(Request $request, VpsPlan $vpsPlan): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vcpu' => 'required|integer|min:1',
            'ram_gb' => 'required|integer|min:1',
            'storage_nvme_gb' => 'required|integer|min:10',
            'bandwidth' => 'required|string',
            'port_speed' => 'required|string',
            'ipv4_count' => 'required|integer|min:1',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'discount_percent_yearly' => 'nullable|integer|min:0|max:100',
            'features_text' => 'nullable|string',
            'is_popular' => 'boolean',
        ]);

        $features = array_filter(array_map('trim', explode("\n", $validated['features_text'] ?? '')));

        $vpsPlan->update([
            'name' => $validated['name'],
            'vcpu' => $validated['vcpu'],
            'ram_gb' => $validated['ram_gb'],
            'storage_nvme_gb' => $validated['storage_nvme_gb'],
            'bandwidth' => $validated['bandwidth'],
            'port_speed' => $validated['port_speed'],
            'ipv4_count' => $validated['ipv4_count'],
            'price_monthly' => $validated['price_monthly'],
            'price_yearly' => $validated['price_yearly'],
            'discount_percent_yearly' => $validated['discount_percent_yearly'] ?? 15,
            'features' => $features,
            'is_popular' => (bool) ($validated['is_popular'] ?? false),
        ]);

        return redirect()->back()->with('success', "Đã cập nhật cấu hình gói [{$vpsPlan->name}] thành công!");
    }

    public function destroyVpsPlan(Request $request, VpsPlan $vpsPlan): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $name = $vpsPlan->name;
        $vpsPlan->delete();

        return redirect()->back()->with('success', "Đã xóa gói VPS [{$name}]!");
    }

    /*
    |--------------------------------------------------------------------------
    | 3. CATEGORIES & CORE SECTORS CRUD (Danh mục & Hệ sinh thái 6 trụ cột)
    |--------------------------------------------------------------------------
    */
    public function storeCategory(Request $request): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sector_code' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'display_order' => 'nullable|integer',
        ]);

        // Generate unique slug
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $order = ! empty($validated['display_order'])
            ? (int) $validated['display_order']
            : (Category::where('sector_code', $validated['sector_code'])->max('display_order') ?? 0) + 1;

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'sector_code' => $validated['sector_code'],
            'parent_id' => ! empty($validated['parent_id']) ? $validated['parent_id'] : null,
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? 'ServerIcon',
            'display_order' => $order,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "Đã tạo danh mục [{$validated['name']}] thành công!");
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sector_code' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $parentId = ! empty($validated['parent_id']) ? (int) $validated['parent_id'] : null;
        if ($parentId === $category->id) {
            $parentId = null;
        }

        $category->update([
            'name' => $validated['name'],
            'sector_code' => $validated['sector_code'],
            'parent_id' => $parentId,
            'description' => $validated['description'],
            'icon' => $validated['icon'] ?? $category->icon,
            'display_order' => $validated['display_order'] ?? $category->display_order,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()->back()->with('success', "Đã cập nhật danh mục [{$category->name}] thành công!");
    }

    public function destroyCategory(Request $request, Category $category): RedirectResponse
    {
        $this->ensureCanMutate($request);

        // System core root slugs that must remain intact to prevent broken main pages
        $systemSlugs = [
            'cloud-infrastructure',
            'internet-connectivity',
            'smart-iot',
            'bluecom',
            'technical-services',
            'doi-tac-dai-ly',
            've-toan-cau',
        ];

        if (in_array($category->slug, $systemSlugs) && $category->parent_id === null) {
            return redirect()->back()->with('error', "Không thể xóa trụ cột hệ thống gốc [{$category->name}]. Trụ cột này gắn liền với trang chức năng và menu cốt lõi!");
        }

        $name = $category->name;
        $childCount = $category->children()->count();
        if ($childCount > 0) {
            return redirect()->back()->with('error', "Không thể xóa danh mục [{$name}] vì đang chứa {$childCount} danh mục con. Vui lòng chuyển hoặc xóa các danh mục con trước!");
        }

        $productCount = $category->products()->count();
        if ($productCount > 0) {
            return redirect()->back()->with('error', "Không thể xóa danh mục [{$name}] vì đang có {$productCount} sản phẩm liên kết. Vui lòng chuyển sản phẩm sang danh mục khác trước khi xóa!");
        }

        $category->delete();

        return redirect()->back()->with('success', "Đã xóa danh mục [{$name}] thành công!");
    }

    /*
    |--------------------------------------------------------------------------
    | 4. PRODUCT EDIT & DELETE
    |--------------------------------------------------------------------------
    */
    public function updateProduct(Request $request, Product $product): RedirectResponse
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

        if ($request->hasFile('thumbnail_file')) {
            $product->thumbnail = $this->imageService->convertToWebp($request->file('thumbnail_file'), 'products');
            $product->gallery = [$product->thumbnail];
        }

        if ($request->hasFile('datasheet_file')) {
            $file = $request->file('datasheet_file');
            $dsName = Str::slug($validated['model_code']).'-datasheet.pdf';
            $file->move(public_path('datasheets'), $dsName);
            $product->datasheet_pdf_path = '/datasheets/'.$dsName;
        }

        // Process specs
        $specs = $request->input('specs');
        if (is_string($specs)) {
            $specs = json_decode($specs, true);
        }
        if (! is_array($specs)) {
            $specs = ! empty($validated['specs_json']) ? json_decode($validated['specs_json'], true) : $product->specs;
        }

        // Process features
        $features = $request->input('features');
        if (is_string($features)) {
            $features = json_decode($features, true);
        }
        if (! is_array($features)) {
            $features = ! empty($validated['features_json']) ? json_decode($validated['features_json'], true) : $product->features;
        }

        // Process application scenarios
        $scenarios = $request->input('application_scenarios');
        if (is_string($scenarios)) {
            $scenarios = json_decode($scenarios, true);
        }
        if (! is_array($scenarios)) {
            $scenarios = ! empty($validated['scenarios_json']) ? json_decode($validated['scenarios_json'], true) : $product->application_scenarios;
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'sector_code' => $validated['sector_code'],
            'name' => $validated['name'],
            'model_code' => $validated['model_code'],
            'badge' => $validated['badge'] ?: 'Sản Phẩm B2B Tiêu Chuẩn',
            'overview' => $validated['overview'],
            'warranty_policy' => $validated['warranty_policy'] ?? $product->warranty_policy,
            'support_commitment' => $validated['support_commitment'] ?? $product->support_commitment,
            'price' => $validated['price'] ?? null,
            'price_unit' => $validated['price_unit'] ?? 'tháng',
            'is_price_visible' => (bool) ($validated['is_price_visible'] ?? false),
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? $product->is_active),
            'specs' => $specs,
            'features' => $features,
            'application_scenarios' => $scenarios,
            'related_product_ids' => $request->input('related_product_ids', $product->related_product_ids),
        ]);

        return redirect()->back()->with('success', "Đã cập nhật sản phẩm [{$product->name}] chuẩn 11 thành phần B2B thành công!");

        return redirect()->back()->with('success', "Đã cập nhật sản phẩm [{$product->name}] thành công!");
    }

    public function destroyProduct(Request $request, Product $product): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $name = $product->name;
        $product->delete();

        return redirect()->back()->with('success', "Đã xóa sản phẩm [{$name}]!");
    }

    /*
    |--------------------------------------------------------------------------
    | 5. DYNAMIC PAGE SECTIONS (Visual CMS for Zero-Code Management)
    |--------------------------------------------------------------------------
    */
    public function updatePageSection(Request $request, PageSection $pageSection): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'metadata_json' => 'nullable|string',
        ]);

        $metadata = ! empty($validated['metadata_json'])
            ? json_decode($validated['metadata_json'], true)
            : $pageSection->metadata;

        $pageSection->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'badge' => $validated['badge'],
            'content' => $validated['content'],
            'metadata' => $metadata,
        ]);

        return redirect()->back()->with('success', "Đã cập nhật nội dung mục [{$pageSection->title}] thành công!");
    }

    /*
    |--------------------------------------------------------------------------
    | 6. NEWS POSTS CRUD (Tin tức & Bài viết)
    |--------------------------------------------------------------------------
    */
    public function storePost(Request $request): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'author_name' => 'nullable|string',
            'is_published' => 'boolean',
            'thumbnail_file' => 'nullable|image|max:5120',
        ]);

        $thumbnail = '/images/news/news-default.webp';
        if ($request->hasFile('thumbnail_file')) {
            $thumbnail = $this->imageService->convertToWebp($request->file('thumbnail_file'), 'news');
        }

        Post::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::random(5),
            'summary' => $validated['summary'],
            'content' => $validated['content'],
            'thumbnail' => $thumbnail,
            'author_name' => $validated['author_name'] ?? 'Ban Kỹ thuật Toàn Cầu',
            'is_published' => (bool) ($validated['is_published'] ?? true),
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', "Đã đăng bài viết [{$validated['title']}] thành công!");
    }

    public function updatePost(Request $request, Post $post): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'author_name' => 'nullable|string',
            'is_published' => 'boolean',
            'thumbnail_file' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $post->thumbnail = $this->imageService->convertToWebp($request->file('thumbnail_file'), 'news');
        }

        $post->update([
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'content' => $validated['content'],
            'author_name' => $validated['author_name'] ?? $post->author_name,
            'is_published' => (bool) ($validated['is_published'] ?? true),
        ]);

        return redirect()->back()->with('success', "Đã cập nhật bài viết [{$post->title}] thành công!");
    }

    public function destroyPost(Request $request, Post $post): RedirectResponse
    {
        $this->ensureCanMutate($request);

        $title = $post->title;
        $post->delete();

        return redirect()->back()->with('success', "Đã xóa bài viết [{$title}]!");
    }

    protected function ensureCanMutate(Request $request): void
    {
        if ($request->user()->hasRole('Viewer')) {
            abort(403, 'Tài khoản Viewer chỉ có quyền xem (Read-Only), không được phép chỉnh sửa nội dung.');
        }
    }
}
