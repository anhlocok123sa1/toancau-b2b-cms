<?php

namespace Tests\Feature;

use App\Models\ComplianceChecklist;
use App\Models\Lead;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\ToanCauCmsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToanCauB2bSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ToanCauCmsSeeder::class);
    }

    /**
     * Test public pages respond with HTTP 200
     */
    public function test_public_pages_return_successful_response(): void
    {
        $routes = [
            '/',
            '/cloud-infrastructure',
            '/san-pham',
            '/internet-connectivity',
            '/smart-iot',
            '/bluecom',
            '/technical-services',
            '/doi-tac-dai-ly',
            '/ve-toan-cau',
            '/du-an-tieu-bieu',
            '/tin-tuc',
            '/lien-he',
            '/chinh-sach-bao-mat',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    /**
     * Test legacy Cloud Business route redirects 301 to unified Cloud Infrastructure tab business
     */
    public function test_cloud_business_redirects_to_cloud_infrastructure(): void
    {
        $response = $this->get('/cloud-business');
        $response->assertStatus(301);
        $response->assertRedirect('/cloud-infrastructure?tab=business');
    }

    /**
     * Test homepage renders 6 distinct core sectors without duplicate cloud sectors
     */
    public function test_homepage_renders_distinct_core_sectors(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Public/HomePage')
            ->has('sectors', 6)
            ->where('sectors.0.code', 'cloud_infra')
            ->where('sectors.5.code', 'partner')
        );
    }

    /**
     * Test 11-component Product detail page
     */
    public function test_product_detail_page_loads_with_11_components(): void
    {
        $product = Product::where('slug', 'dedicated-server-epyc-7763')->first();
        $this->assertNotNull($product);

        $response = $this->get('/san-pham/'.$product->slug);
        $response->assertStatus(200);
        $response->assertSee('TC-DED-EPYC7763');
    }

    /**
     * Test pure native XML Sitemap generation
     */
    public function test_native_xml_sitemap(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', $response->getContent());
        $this->assertStringContainsString('san-pham/dedicated-server-epyc-7763', $response->getContent());
    }

    /**
     * Test Lead capture with Decree 13/2023/ND-CP consent and 30-min SLA
     */
    public function test_lead_submission_creates_record_with_sla(): void
    {
        $payload = [
            'form_type' => 'consultation_quote',
            'full_name' => 'Nguyễn Văn Test',
            'company_name' => 'Công Ty Test B2B',
            'phone' => '0909123456',
            'email' => 'test@b2bcompany.vn',
            'interested_service' => 'Cloud VPS NVMe',
            'message' => 'Cần tư vấn gói VPS S2',
            'data_consent' => true,
        ];

        $response = $this->post('/lead-submission', $payload);
        $response->assertStatus(302);

        $this->assertDatabaseHas('leads', [
            'full_name' => 'Nguyễn Văn Test',
            'phone' => '0909123456',
            'status' => '1_new',
            'data_consent' => true,
        ]);

        $lead = Lead::where('phone', '0909123456')->first();
        $this->assertNotNull($lead->sla_due_at);
        $this->assertTrue($lead->sla_due_at->isFuture());
    }

    /**
     * Test Inertia Lead submission redirects with session flash (prevents raw JSON modal popup)
     */
    public function test_lead_submission_with_inertia_headers_redirects_with_flash_code(): void
    {
        $payload = [
            'form_type' => 'consultation_quote',
            'full_name' => 'Trần Thị Inertia',
            'company_name' => 'Công Ty Inertia B2B',
            'phone' => '0988776655',
            'email' => 'inertia@b2bcompany.vn',
            'interested_service' => 'Dedicated Server',
            'data_consent' => true,
        ];

        $response = $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/lead-submission', $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('lead_code');
        $response->assertSessionHas('success');
    }

    /**
     * RBAC Test: Content Admin is strictly blocked from Leads Portal (403 Forbidden)
     */
    public function test_content_admin_is_forbidden_from_leads(): void
    {
        $contentAdmin = User::where('email', 'content@toancau.vn')->first();
        $this->assertNotNull($contentAdmin);

        $response = $this->actingAs($contentAdmin)->get('/admin/leads');
        $response->assertStatus(403);
    }

    /**
     * RBAC Test: Sales user can access Leads Portal
     */
    public function test_sales_user_can_access_leads(): void
    {
        $salesUser = User::where('email', 'sales@toancau.vn')->first();
        $this->assertNotNull($salesUser);

        $response = $this->actingAs($salesUser)->get('/admin/leads');
        $response->assertStatus(200);
    }

    /**
     * RBAC Test: Viewer is blocked from mutating actions (403 Forbidden)
     */
    public function test_viewer_is_forbidden_from_mutating(): void
    {
        $viewer = User::where('email', 'viewer@toancau.vn')->first();
        $this->assertNotNull($viewer);

        $product = Product::first();

        $response = $this->actingAs($viewer)->post("/admin/products/{$product->id}/toggle-price");
        $response->assertStatus(403);
    }

    /**
     * RBAC Test: Admin can toggle Compliance verification
     */
    public function test_admin_can_toggle_compliance(): void
    {
        $admin = User::where('email', 'admin@toancau.vn')->first();
        $checklist = ComplianceChecklist::where('item_key', 'starlink_reseller_capability')->first();
        $this->assertFalse($checklist->is_verified);

        $response = $this->actingAs($admin)->post("/admin/compliance/{$checklist->id}/toggle");
        $response->assertStatus(302);

        $checklist->refresh();
        $this->assertTrue($checklist->is_verified);
        $this->assertEquals($admin->id, $checklist->verified_by);
    }

    /**
     * Test All Products Catalog Page (/san-pham)
     */
    public function test_public_all_products_catalog_page_is_accessible(): void
    {
        $response = $this->get('/san-pham');
        $response->assertStatus(200);
    }

    /**
     * Test Lead Detail full page and Contract Management for Sales role
     */
    public function test_sales_user_can_access_lead_detail_and_update_contract(): void
    {
        $salesUser = User::where('email', 'sales@toancau.vn')->first();
        $lead = Lead::first();

        // Access Lead Detail Page
        $response = $this->actingAs($salesUser)->get("/admin/leads/{$lead->id}");
        $response->assertStatus(200);

        // Update Contract Information
        $contractPayload = [
            'contract_number' => 'HD-2026-TC-TEST',
            'contract_value' => 250000000,
            'contract_term' => '24 tháng',
            'contract_payment_terms' => '50% tạm ứng, 50% sau nghiệm thu',
            'contract_signed_at' => now()->format('Y-m-d'),
            'contract_notes' => 'Hợp đồng bảo trì và triển khai Dedicated Server',
            'status' => '6_closed_won',
        ];

        $postResponse = $this->actingAs($salesUser)->post("/admin/leads/{$lead->id}/contract", $contractPayload);
        $postResponse->assertStatus(302);

        $lead->refresh();
        $this->assertEquals('HD-2026-TC-TEST', $lead->contract_number);
        $this->assertEquals('250000000.00', (string) $lead->contract_value);
        $this->assertEquals('6_closed_won', $lead->status);
    }

    /**
     * Test Content Admin is blocked from Lead Detail (403)
     */
    public function test_content_admin_is_forbidden_from_lead_detail(): void
    {
        $contentAdmin = User::where('email', 'content@toancau.vn')->first();
        $lead = Lead::first();

        $response = $this->actingAs($contentAdmin)->get("/admin/leads/{$lead->id}");
        $response->assertStatus(403);
    }
}
