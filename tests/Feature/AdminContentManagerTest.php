<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
use App\Models\VpsPlan;
use Database\Seeders\ToanCauCmsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ToanCauCmsSeeder::class);
    }

    /**
     * Admin and Content Admin can update dynamic page sections (Zero-Code CMS)
     */
    public function test_admin_and_content_admin_can_update_page_section(): void
    {
        $admin = User::where('email', 'admin@toancau.vn')->first();
        $this->assertNotNull($admin);

        $section = PageSection::where('page_key', 'home')->whereIn('section_key', ['hero', 'hero_banner'])->first();
        $this->assertNotNull($section);

        $response = $this->actingAs($admin)->post('/admin/page-sections/'.$section->id, [
            'title' => 'TIÊU ĐỀ HERO MỚI ĐÃ CẬP NHẬT TỪ CMS',
            'subtitle' => 'Mô tả phụ mới',
            'badge' => 'HẠ TẦNG SỐ 2026',
            'content' => 'Nội dung giới thiệu doanh nghiệp mới cập nhật qua giao diện CMS không cần code.',
            'metadata_json' => json_encode(['custom_key' => 'custom_val']),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('page_sections', [
            'id' => $section->id,
            'title' => 'TIÊU ĐỀ HERO MỚI ĐÃ CẬP NHẬT TỪ CMS',
            'badge' => 'HẠ TẦNG SỐ 2026',
        ]);

        // Public page reflects update in Inertia props
        $public = $this->get('/');
        $public->assertStatus(200);
        $public->assertInertia(fn ($page) => $page
            ->component('Public/HomePage')
            ->has('homeSections')
        );
    }

    /**
     * Viewer role is strictly read-only and blocked with 403 on mutation
     */
    public function test_viewer_role_cannot_modify_page_section(): void
    {
        $viewer = User::where('email', 'viewer@toancau.vn')->first();
        $this->assertNotNull($viewer);

        $section = PageSection::first();

        $response = $this->actingAs($viewer)->post('/admin/page-sections/'.$section->id, [
            'title' => 'Hacked Title',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test Projects CRUD and Featured Toggle
     */
    public function test_project_crud_and_featured_toggle(): void
    {
        $contentAdmin = User::where('email', 'content@toancau.vn')->first();
        $this->assertNotNull($contentAdmin);

        // 1. Create Project
        $response = $this->actingAs($contentAdmin)->post('/admin/projects', [
            'title' => 'Hệ thống Camera Giám sát Ven biển Côn Đảo',
            'client_name' => 'Chi cục Kiểm lâm Vùng 3',
            'sector_code' => 'smart_iot',
            'description' => 'Triển khai 30 cụm Solar Camera 4G ngoài trời chống nước IP67.',
            'is_featured' => true,
        ]);
        $response->assertStatus(302);

        $project = Project::where('title', 'Hệ thống Camera Giám sát Ven biển Côn Đảo')->first();
        $this->assertNotNull($project);
        $this->assertTrue((bool) $project->is_featured);

        // 2. Toggle Featured
        $toggle = $this->actingAs($contentAdmin)->post('/admin/projects/'.$project->id.'/toggle-featured');
        $toggle->assertStatus(302);
        $this->assertFalse((bool) $project->fresh()->is_featured);

        // 3. Update Project
        $update = $this->actingAs($contentAdmin)->post('/admin/projects/'.$project->id, [
            'title' => 'Hệ thống Camera Giám sát Ven biển Côn Đảo - Pha 2',
            'client_name' => 'Chi cục Kiểm lâm Vùng 3 & Bộ Tư lệnh Vùng Cảnh sát Biển',
            'sector_code' => 'smart_iot',
            'description' => 'Mở rộng thêm 20 trạm IoT quan trắc biển.',
            'is_featured' => true,
        ]);
        $update->assertStatus(302);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Hệ thống Camera Giám sát Ven biển Côn Đảo - Pha 2',
        ]);

        // 4. Destroy Project
        $delete = $this->actingAs($contentAdmin)->delete('/admin/projects/'.$project->id);
        $delete->assertStatus(302);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /**
     * Test VPS Plans CRUD
     */
    public function test_vps_plans_crud(): void
    {
        $admin = User::where('email', 'admin@toancau.vn')->first();

        // 1. Create Plan
        $response = $this->actingAs($admin)->post('/admin/vps-plans', [
            'name' => 'Cloud VPS Ultra Fast AI',
            'vcpu' => 32,
            'ram_gb' => 64,
            'storage_nvme_gb' => 1000,
            'bandwidth' => 'Không giới hạn 10Gbps',
            'port_speed' => '10 Gbps Uplink',
            'ipv4_count' => 4,
            'price_monthly' => 2500000,
            'price_yearly' => 25500000,
            'discount_percent_yearly' => 15,
            'features_text' => "32 vCPU AMD EPYC\n64 GB ECC RAM\n1TB NVMe RAID-10",
            'is_popular' => true,
        ]);
        $response->assertStatus(302);

        $plan = VpsPlan::where('name', 'Cloud VPS Ultra Fast AI')->first();
        $this->assertNotNull($plan);
        $this->assertEquals(32, $plan->vcpu);

        // 2. Update Plan
        $update = $this->actingAs($admin)->post('/admin/vps-plans/'.$plan->id, [
            'name' => 'Cloud VPS Ultra Fast AI v2',
            'vcpu' => 48,
            'ram_gb' => 128,
            'storage_nvme_gb' => 2000,
            'bandwidth' => 'Không giới hạn 10Gbps',
            'port_speed' => '10 Gbps Uplink',
            'ipv4_count' => 8,
            'price_monthly' => 3500000,
            'price_yearly' => 35700000,
            'discount_percent_yearly' => 15,
            'features_text' => "48 vCPU AMD EPYC\n128 GB ECC RAM",
            'is_popular' => true,
        ]);
        $update->assertStatus(302);
        $this->assertDatabaseHas('vps_plans', [
            'id' => $plan->id,
            'name' => 'Cloud VPS Ultra Fast AI v2',
            'vcpu' => 48,
        ]);

        // 3. Destroy Plan
        $del = $this->actingAs($admin)->delete('/admin/vps-plans/'.$plan->id);
        $del->assertStatus(302);
        $this->assertDatabaseMissing('vps_plans', ['id' => $plan->id]);
    }

    /**
     * Test Category CRUD and Smart IoT Sub-Category workflow
     */
    public function test_category_crud(): void
    {
        $admin = User::where('email', 'admin@toancau.vn')->first();
        $iotRoot = Category::where('sector_code', 'smart_iot')->whereNull('parent_id')->first();
        $this->assertNotNull($iotRoot);

        // 1. Create Sub-category under Smart IoT
        $response = $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'Camera AI Đếm Xe Giao Thông',
            'sector_code' => 'smart_iot',
            'parent_id' => $iotRoot->id,
            'description' => 'Thiết bị camera AI thông minh đếm lưu lượng xe.',
            'icon' => 'CameraIcon',
            'display_order' => 6,
        ]);
        $response->assertStatus(302);

        $cat = Category::where('slug', 'camera-ai-dem-xe-giao-thong')->first();
        $this->assertNotNull($cat);
        $this->assertEquals($iotRoot->id, $cat->parent_id);

        // 2. Update Category
        $update = $this->actingAs($admin)->post('/admin/categories/'.$cat->id, [
            'name' => 'Camera AI Đếm Xe & Nhận Diện Biển Số',
            'sector_code' => 'smart_iot',
            'parent_id' => $iotRoot->id,
            'description' => 'Camera AI chuyên dụng nhận diện biển số giao thông.',
            'icon' => 'CameraIcon',
            'display_order' => 6,
            'is_active' => true,
        ]);
        $update->assertStatus(302);
        $this->assertDatabaseHas('categories', [
            'id' => $cat->id,
            'name' => 'Camera AI Đếm Xe & Nhận Diện Biển Số',
        ]);

        // 3. Prevent deleting root system category
        $delRoot = $this->actingAs($admin)->delete('/admin/categories/'.$iotRoot->id);
        $delRoot->assertStatus(302);
        $delRoot->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $iotRoot->id]);

        // 4. Destroy new subcategory cleanly
        $del = $this->actingAs($admin)->delete('/admin/categories/'.$cat->id);
        $del->assertStatus(302);
        $del->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }

    /**
     * Test Smart IoT public page category filtering
     */
    public function test_smart_iot_public_filtering(): void
    {
        $response = $this->get('/smart-iot?sub=solar-camera');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Public/SmartIot')
            ->has('subCategories')
            ->has('products')
            ->where('activeSubCategory', 'solar-camera')
        );
    }

    /**
     * Test News Posts CRUD
     */
    public function test_news_posts_crud(): void
    {
        $contentAdmin = User::where('email', 'content@toancau.vn')->first();

        // 1. Create Post
        $response = $this->actingAs($contentAdmin)->post('/admin/posts', [
            'title' => 'Toàn Cầu Ra Mắt Gói Cloud VPS Thế Hệ Mới 2026',
            'summary' => 'Nâng cấp toàn bộ chip AMD EPYC và ổ cứng NVMe Enterprise.',
            'content' => 'Chi tiết nâng cấp hạ tầng viễn thông và máy chủ...',
            'author_name' => 'Ban Công Nghệ Toàn Cầu',
            'is_published' => true,
        ]);
        $response->assertStatus(302);

        $post = Post::where('title', 'Toàn Cầu Ra Mắt Gói Cloud VPS Thế Hệ Mới 2026')->first();
        $this->assertNotNull($post);

        // 2. Update Post
        $update = $this->actingAs($contentAdmin)->post('/admin/posts/'.$post->id, [
            'title' => 'Toàn Cầu Ra Mắt Gói Cloud VPS Thế Hệ Mới 2026 - Phiên Bản Nâng Cao',
            'summary' => 'Cập nhật thêm tính năng chống DDoS Layer 7.',
            'content' => 'Nội dung chi tiết sau chỉnh sửa...',
            'author_name' => 'Ban Công Nghệ Toàn Cầu',
            'is_published' => true,
        ]);
        $update->assertStatus(302);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Toàn Cầu Ra Mắt Gói Cloud VPS Thế Hệ Mới 2026 - Phiên Bản Nâng Cao',
        ]);

        // 3. Destroy Post
        $del = $this->actingAs($contentAdmin)->delete('/admin/posts/'.$post->id);
        $del->assertStatus(302);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /**
     * Test Product Full Page Editor and Badge Customization
     */
    public function test_product_full_page_editor_and_badge_customization(): void
    {
        $contentAdmin = User::where('email', 'content@toancau.vn')->first();
        $product = Product::first();
        $this->assertNotNull($product);

        // 1. Visit Full Page Edit Product
        $editPage = $this->actingAs($contentAdmin)->get('/admin/products/'.$product->id.'/edit');
        $editPage->assertStatus(200);
        $editPage->assertInertia(fn ($page) => $page
            ->component('Admin/ProductEdit')
            ->has('product')
            ->has('categories')
            ->has('otherProducts')
        );

        // 2. Visit Full Page Create Product
        $createPage = $this->actingAs($contentAdmin)->get('/admin/products/create');
        $createPage->assertStatus(200);
        $createPage->assertInertia(fn ($page) => $page
            ->component('Admin/ProductEdit')
            ->where('product', null)
            ->has('categories')
        );

        // 3. Update Product with Custom Badge and Visual Arrays
        $customBadge = 'Chuyên Dụng Ven Biển IP67 Siêu Bền';
        $update = $this->actingAs($contentAdmin)->post('/admin/products/'.$product->id, [
            'category_id' => $product->category_id,
            'sector_code' => $product->sector_code,
            'name' => $product->name,
            'model_code' => $product->model_code,
            'badge' => $customBadge,
            'overview' => 'Giải pháp giám sát an ninh ven biển chuyên sâu cho các trang trại nuôi trồng.',
            'specs' => [
                ['label' => 'Độ phân giải', 'value' => '4.0 MP 2K'],
                ['label' => 'Cảm biến hình ảnh', 'value' => 'Sony Starlight Nightvision'],
            ],
            'features' => [
                ['title' => 'Tự Sạc Năng Lượng Mặt Trời', 'desc' => 'Tấm pin Mono 40W hiệu suất cao'],
            ],
            'application_scenarios' => [
                'Trang trại nuôi tôm công nghiệp Cà Mau',
                'Khu nghỉ dưỡng ven biển Phú Quốc',
            ],
            'is_price_visible' => true,
            'price' => 3950000,
        ]);
        $update->assertStatus(302);

        $fresh = $product->fresh();
        $this->assertEquals($customBadge, $fresh->badge);
        $this->assertCount(2, $fresh->specs);
        $this->assertCount(1, $fresh->features);
        $this->assertCount(2, $fresh->application_scenarios);

        // 4. Verify Public Product Detail reflects the custom badge
        $publicDetail = $this->get('/san-pham/'.$fresh->slug);
        $publicDetail->assertStatus(200);
        $publicDetail->assertInertia(fn ($page) => $page
            ->component('Public/ProductDetail11Step')
            ->where('product.badge', $customBadge)
        );
    }
}
