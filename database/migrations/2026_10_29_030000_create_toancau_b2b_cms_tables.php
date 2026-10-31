<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Compliance & Legal Checklists (6 mandatory items)
        Schema::create('compliance_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('item_key')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // 2. Categories for 8 sectors & sub-sections
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sector_code')->index(); // cloud_infra, cloud_business, internet, smart_iot, bluecom, tech_services, partner, about
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Products & Services with 11 mandatory components & Phase 2 hooks
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('sector_code')->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('model_code')->index(); // Component [02]
            $table->string('thumbnail')->nullable(); // Component [03]
            $table->json('gallery')->nullable(); // Component [03] WebP images
            $table->text('overview'); // Component [04] 2-3 sentences B2B value
            $table->json('features'); // Component [05] bullet points + icons
            $table->json('specs'); // Component [06] 2-column key-value JSON
            $table->json('application_scenarios'); // Component [07] Real scenarios
            $table->string('datasheet_pdf_path')->nullable(); // Component [08]
            $table->string('warranty_policy')->default('12 tháng chính hãng'); // Component [09]
            $table->string('support_commitment')->default('Hỗ trợ kỹ thuật 24/7/365, SLA 15-30 phút'); // Component [09]
            $table->json('related_product_ids')->nullable(); // Component [10]
            $table->boolean('is_price_visible')->default(false); // Component [11] Toggle
            $table->decimal('price', 15, 2)->nullable(); // Component [11]
            $table->string('price_unit')->default('tháng');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Phase 2 Cloud / VPS Auto-Provisioning & Payment Hooks
            $table->string('provisioning_driver')->default('manual'); // proxmox, openstack, ipam, manual
            $table->json('provisioning_metadata')->nullable(); // template_id, pool_id, vlan_id, vcpu, ram

            $table->timestamps();
        });

        // 4. VPS Plans for Comparison Matrix & Quick Quote
        Schema::create('vps_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('vcpu');
            $table->integer('ram_gb');
            $table->integer('storage_nvme_gb');
            $table->string('bandwidth')->default('Không giới hạn (100Mbps cam kết)');
            $table->string('port_speed')->default('10 Gbps Domestic / 1 Gbps Shared');
            $table->integer('ipv4_count')->default(1);
            $table->decimal('price_monthly', 15, 2);
            $table->decimal('price_yearly', 15, 2);
            $table->integer('discount_percent_yearly')->default(15);
            $table->json('features')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->integer('display_order')->default(0);
            $table->string('phase2_template_id')->nullable();
            $table->timestamps();
        });

        // 5. Leads with 3 Forms, SLA, and 5 Life-Cycle States
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('form_type')->index(); // consultation_quote, partner_registration, technical_service
            $table->string('code')->unique();
            $table->string('full_name');
            $table->string('company_name')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('city_province')->nullable();
            $table->string('interested_service')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->text('message')->nullable();
            $table->string('attachment_path')->nullable(); // stored in storage/app/private
            $table->string('attachment_name')->nullable();
            $table->boolean('data_consent')->default(true); // Decree 13/2023/ND-CP
            $table->string('status')->default('1_new')->index(); // 1_new, 2_acknowledged, 3_in_progress, 4_quoted, 5_closed_won, 5_closed_lost
            $table->timestamp('sla_due_at'); // 30 minutes SLA
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('closed_reason')->nullable(); // Mandatory if 5_closed_lost
            $table->json('notes')->nullable(); // Timeline logs & notes
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        // 6. Featured Projects & Client Showcase
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('client_name');
            $table->string('sector_code');
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // 7. News & Technical Insights
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->string('author_name')->default('Ban Kỹ thuật Toàn Cầu');
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 8. System & Legal Settings (GA4, GSC, Hotline, etc.)
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, json, boolean
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('vps_plans');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('compliance_checklists');
    }
};
