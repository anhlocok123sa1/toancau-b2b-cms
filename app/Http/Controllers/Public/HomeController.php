<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ComplianceChecklist;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\SystemSetting;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $homeSections = PageSection::getPageSections('home');

        // 1. Core sector navigation cards (Dynamic from PageSection, fallback to default)
        $sectors = $homeSections['core_sectors']['metadata']['items'] ?? [
            [
                'code' => 'cloud_infra',
                'name' => 'Cloud & Infrastructure',
                'title' => 'Hạ Tầng Điện Toán Đám Mây',
                'desc' => 'Máy chủ vật lý Dedicated Server, Cloud VPS NVMe hiệu năng cao, Private Cloud và dịch vụ tài nguyên IP sạch.',
                'href' => route('public.cloud_infra'),
                'icon' => 'ServerStackIcon',
                'badge' => 'Tier 3 Data Center',
            ],
            [
                'code' => 'internet',
                'name' => 'Internet & Connectivity',
                'title' => 'Internet Doanh Nghiệp & Kết Nối Đa Điểm',
                'desc' => 'Kênh thuê riêng Leased Line SLA 99.9%, mạng SD-WAN, giải pháp vệ tinh Starlink & SIM IoT.',
                'href' => route('public.internet'),
                'icon' => 'GlobeAltIcon',
                'badge' => 'SLA 99.9%',
            ],
            [
                'code' => 'smart_iot',
                'name' => 'Smart IoT',
                'title' => 'Hệ Sinh Thái IoT & Giám Sát Thông Minh',
                'desc' => 'Solar Camera 4G, camera ven biển IP67, IoT Gateway Modbus/MQTT và cảm biến công/nông nghiệp.',
                'href' => route('public.smart_iot'),
                'icon' => 'CpuChipIcon',
                'badge' => 'Solar & 4G/AI',
            ],
            [
                'code' => 'bluecom',
                'name' => 'Bluecom',
                'title' => 'Truyền Thanh Thông Minh 4G Bluecom',
                'desc' => 'Bộ thu phát truyền thanh số 4G, router công nghiệp và nền tảng quản lý tập trung đám mây.',
                'href' => route('public.bluecom'),
                'icon' => 'SpeakerWaveIcon',
                'badge' => 'Phân phối chính hãng',
            ],
            [
                'code' => 'tech_services',
                'name' => 'Technical Services',
                'title' => 'Dịch Vụ Kỹ Thuật & Khảo Sát Hiện Trường',
                'desc' => 'Khảo sát hiện trường, thi công hạ tầng mạng, camera, phòng server và bảo trì chẩn đoán khẩn cấp.',
                'href' => route('public.tech_services'),
                'icon' => 'WrenchScrewdriverIcon',
                'badge' => 'Phản hồi 15-30p',
            ],
            [
                'code' => 'partner',
                'name' => 'Partner & Agency Network',
                'title' => 'Mạng Lưới Đối Tác & Đại Lý Phân Phối',
                'desc' => 'Chính sách chiết khấu linh hoạt, bảo vệ quyền lợi dự án, hỗ trợ kỹ thuật 24/7 và đào tạo chuyên sâu.',
                'href' => route('public.partner'),
                'icon' => 'UserGroupIcon',
                'badge' => 'Hợp Tác Phát Triển',
            ],
        ];

        // 2. Showcase Featured Products (max 8 admin-tagged featured)
        $maxFeatured = (int) SystemSetting::get('max_featured_products_home', 8);
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('category:id,name,slug')
            ->limit($maxFeatured)
            ->get([
                'id',
                'category_id',
                'sector_code',
                'name',
                'slug',
                'model_code',
                'thumbnail',
                'overview',
                'warranty_policy',
                'support_commitment',
                'is_price_visible',
                'price',
                'price_unit',
            ]);

        // 3. Infrastructure & Capabilities block (CMS Toggle + IP compliance check)
        $showInfraBlock = (bool) SystemSetting::get('show_infra_stats_home', true);
        $isIpVerified = ComplianceChecklist::isItemVerified('ip_resource_4000');
        $isBluecomVerified = ComplianceChecklist::isItemVerified('bluecom_distribution_status');

        $infraStats = [
            'visible' => $showInfraBlock,
            'server_clusters' => '100+ Cụm Server Tier 3',
            'ip_resource' => $isIpVerified ? '4.000+ Địa chỉ IPv4 sạch độc quyền' : 'Tài nguyên mạng IP Doanh nghiệp',
            'sla_network' => '99.9% Cam kết thời gian vận hành',
            'support_engineers' => 'Đội ngũ Kỹ sư 24/7/365 trên toàn quốc',
            'bluecom_status' => $isBluecomVerified ? 'Trung tâm giải pháp Bluecom chính thức' : 'Giải pháp truyền thanh số',
        ];

        // 4. Featured Projects & News
        $featuredProjects = Project::where('is_featured', true)
            ->orderBy('display_order')
            ->limit(4)
            ->get();

        $latestNews = Post::where('is_published', true)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return Inertia::render('Public/HomePage', [
            'sectors' => $sectors,
            'homeSections' => $homeSections,
            'featuredProducts' => $featuredProducts,
            'infraStats' => $infraStats,
            'featuredProjects' => $featuredProjects,
            'latestNews' => $latestNews,
        ]);
    }
}
