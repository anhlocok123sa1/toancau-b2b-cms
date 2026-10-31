<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ToanCauCmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -------------------------------------------------------------
        // 1. Roles & Permissions (Spatie)
        // -------------------------------------------------------------
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'Admin',
            'Content Admin',
            'Sales',
            'Partner Manager',
            'Viewer',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Default Users for each role
        $usersData = [
            [
                'name' => 'Toàn Cầu Administrator',
                'email' => 'admin@toancau.vn',
                'password' => Hash::make('Password@123'),
                'role' => 'Admin',
            ],
            [
                'name' => 'Nguyễn Quản Trị Nội Dung',
                'email' => 'content@toancau.vn',
                'password' => Hash::make('Password@123'),
                'role' => 'Content Admin',
            ],
            [
                'name' => 'Trần Trưởng Ban Kinh Doanh',
                'email' => 'sales@toancau.vn',
                'password' => Hash::make('Password@123'),
                'role' => 'Sales',
            ],
            [
                'name' => 'Lê Quản Lý Đối Tác & Đại Lý',
                'email' => 'partner@toancau.vn',
                'password' => Hash::make('Password@123'),
                'role' => 'Partner Manager',
            ],
            [
                'name' => 'Phạm Chuyên Viên Báo Cáo',
                'email' => 'viewer@toancau.vn',
                'password' => Hash::make('Password@123'),
                'role' => 'Viewer',
            ],
        ];

        foreach ($usersData as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => $u['password'],
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles([$u['role']]);
        }

        $adminUser = User::where('email', 'admin@toancau.vn')->first();

        // -------------------------------------------------------------
        // 2. Six Mandatory Legal & Sensitive Checklists
        // -------------------------------------------------------------
        $checklists = [
            [
                'item_key' => 'legal_corporate_profile',
                'title' => '1. Thông tin pháp nhân, MST, trụ sở, hotline, Zalo OA, email nhận lead, logo',
                'description' => 'Kiểm định tính xác thực thông tin đăng ký doanh nghiệp Toàn Cầu Technology và các kênh tiếp nhận khách hàng.',
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => $adminUser->id,
                'metadata' => [
                    'company_name' => 'CÔNG TY CỔ PHẦN CÔNG NGHỆ TOÀN CẦU',
                    'tax_id' => '0318999888',
                    'headquarters' => 'Tầng 8, Tòa nhà Công Nghệ Số, 123 Nguyễn Thị Minh Khai, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh',
                    'hotline' => '1900 6868 - 028 7300 8888',
                    'zalo_oa' => 'https://zalo.me/toancautech',
                    'lead_email' => 'lead@toancau.vn',
                    'support_email' => 'support@toancau.vn',
                    'brand_tagline' => 'Hạ tầng số – Kết nối thông minh – Giải pháp công nghệ',
                ],
            ],
            [
                'item_key' => 'ip_resource_4000',
                'title' => '2. Tài nguyên 4.000 địa chỉ IP',
                'description' => 'Chỉ hiển thị con số 4.000 IP sau khi có xác nhận duyệt trên CMS; tuyệt đối KHÔNG lưu hoặc công khai danh sách dải IP / subnet nhạy cảm ra ngoài API Public.',
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => $adminUser->id,
                'metadata' => [
                    'ip_count_display' => 4000,
                    'allow_public_display' => true,
                    'security_policy' => 'Subnet and CIDR routing tables are internal-only. Never expose ASN or prefix breakdown in public endpoints.',
                ],
            ],
            [
                'item_key' => 'starlink_reseller_capability',
                'title' => '3. Tư cách giải pháp Starlink',
                'description' => 'Chỉ giới thiệu ứng dụng, thiết bị, năng lực tư vấn, khảo sát và triển khai; tuyệt đối KHÔNG tự nhận là "Đại lý ủy quyền chính thức" khi chưa bật cờ phê duyệt.',
                'is_verified' => false, // Default UNVERIFIED per strict legal instructions
                'verified_at' => null,
                'verified_by' => null,
                'metadata' => [
                    'official_badge_approved' => false,
                    'safe_wording' => 'Đơn vị tư vấn kỹ thuật, khảo sát hiện trường và triển khai giải pháp kết nối vệ tinh Starlink cho doanh nghiệp vùng sâu & hải đảo.',
                    'prohibited_term' => 'Đại lý ủy quyền chính thức Starlink (Chỉ bật khi có chứng nhận ký kết)',
                ],
            ],
            [
                'item_key' => 'bluecom_distribution_status',
                'title' => '4. Quyền phân phối Bluecom',
                'description' => 'Kiểm soát hiển thị chứng nhận phân phối Bluecom 4G, LAN/Wi-Fi và phần mềm quản lý.',
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => $adminUser->id,
                'metadata' => [
                    'certified_partner' => true,
                    'certificate_code' => 'BC-DIST-VN-2026',
                    'scope' => 'Thiết bị truyền thanh 4G, Router công nghiệp và CMS phát thanh đám mây',
                ],
            ],
            [
                'item_key' => 'product_datasheets_warranty',
                'title' => '5. Danh mục sản phẩm thực tế, ảnh WebP, bảng thông số, file PDF Datasheet và bảo hành',
                'description' => 'Quy chuẩn hóa 11 thành phần chi tiết sản phẩm, chuẩn ảnh WebP nén không suy hao và cam kết bảo hành 12-36 tháng.',
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => $adminUser->id,
                'metadata' => [
                    'standard_warranty_months' => 24,
                    'support_sla_minutes' => 15,
                    'replacement_window_days' => 30,
                ],
            ],
            [
                'item_key' => 'lead_sla_privacy_nd13',
                'title' => '6. Quy trình xử lý Lead SLA (15-30 phút), email xác nhận tự động & tuân thủ Nghị định 13/2023/NĐ-CP',
                'description' => 'Bảo vệ dữ liệu cá nhân khách hàng, minh bạch thu thập thông tin và cam kết phản hồi trong tối đa 30 phút.',
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => $adminUser->id,
                'metadata' => [
                    'sla_target_minutes' => 30,
                    'decree_13_compliant' => true,
                    'consent_text' => 'Tôi đồng ý để Toàn Cầu Technology lưu trữ và xử lý thông tin liên hệ theo Nghị định 13/2023/NĐ-CP để phục vụ tư vấn báo giá.',
                ],
            ],
        ];

        foreach ($checklists as $chk) {
            DB::table('compliance_checklists')->updateOrInsert(
                ['item_key' => $chk['item_key']],
                [
                    'title' => $chk['title'],
                    'description' => $chk['description'],
                    'is_verified' => $chk['is_verified'],
                    'verified_at' => $chk['verified_at'],
                    'verified_by' => $chk['verified_by'],
                    'metadata' => json_encode($chk['metadata'], JSON_UNESCAPED_UNICODE),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------------------------------------------
        // 3. Categories (8 Main Menus + Sub-categories)
        // -------------------------------------------------------------
        $parentCategories = [
            [
                'name' => 'Cloud & Hạ Tầng',
                'slug' => 'cloud-infrastructure',
                'sector_code' => 'cloud_infra',
                'description' => 'Máy chủ vật lý Dedicated Server, Cloud VPS hiệu năng cao NVMe, Private Cloud và Cho thuê dải IPv4 sạch',
                'icon' => 'ServerIcon',
                'display_order' => 1,
            ],
            [
                'name' => 'Internet & Kết Nối Doanh Nghiệp',
                'slug' => 'internet-connectivity',
                'sector_code' => 'internet',
                'description' => 'Internet Leased Line SLA 99.9%, Kết nối đa điểm SD-WAN, Giải pháp vệ tinh Starlink & SIM IoT Multi-WAN',
                'icon' => 'GlobeAltIcon',
                'display_order' => 2,
            ],
            [
                'name' => 'Smart IoT & Giám Sát',
                'slug' => 'smart-iot',
                'sector_code' => 'smart_iot',
                'description' => 'Hệ sinh thái Solar Camera, Camera 4G/Wi-Fi, IoT Gateway công nghiệp, cảm biến và điều khiển từ xa',
                'icon' => 'CpuChipIcon',
                'display_order' => 3,
            ],
            [
                'name' => 'Bluecom Truyền Thanh Số',
                'slug' => 'bluecom',
                'sector_code' => 'bluecom',
                'description' => 'Bộ thu phát truyền thanh thông minh Bluecom 4G, bộ định tuyến công nghiệp và phần mềm điều khiển tập trung',
                'icon' => 'SpeakerWaveIcon',
                'display_order' => 4,
            ],
            [
                'name' => 'Dịch Vụ Kỹ Thuật Chuyên Nghiệp',
                'slug' => 'technical-services',
                'sector_code' => 'tech_services',
                'description' => 'Thi công lắp đặt mạng, camera, IoT, phòng server và bảo trì, chẩn đoán, sửa chữa khẩn cấp',
                'icon' => 'WrenchScrewdriverIcon',
                'display_order' => 5,
            ],
            [
                'name' => 'Đối Tác & Đại Lý',
                'slug' => 'doi-tac-dai-ly',
                'sector_code' => 'partner',
                'description' => 'Chính sách hợp tác phân phối và phát triển hệ sinh thái công nghệ cùng Toàn Cầu Technology',
                'icon' => 'UserGroupIcon',
                'display_order' => 6,
            ],
            [
                'name' => 'Về Toàn Cầu',
                'slug' => 've-toan-cau',
                'sector_code' => 'about',
                'description' => 'Năng lực công nghệ, tầm nhìn sứ mệnh, đội ngũ chuyên gia, dự án tiêu biểu và tin tức ngành',
                'icon' => 'BuildingOfficeIcon',
                'display_order' => 7,
            ],
        ];

        $catIds = [];
        foreach ($parentCategories as $cat) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $cat['slug']],
                [
                    'parent_id' => null,
                    'name' => $cat['name'],
                    'sector_code' => $cat['sector_code'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                    'display_order' => $cat['display_order'],
                    'is_active' => true,
                    'updated_at' => now(),
                ]
            );
            $catIds[$cat['sector_code']] = DB::table('categories')->where('slug', $cat['slug'])->value('id');
        }

        // Sub categories for Smart IoT (5 categories required in spec)
        $iotSubCategories = [
            ['name' => 'Solar Camera (Năng lượng mặt trời)', 'slug' => 'solar-camera', 'order' => 1],
            ['name' => 'Camera 4G / Wi-Fi Giám Sát', 'slug' => 'camera-4g-wifi', 'order' => 2],
            ['name' => 'IoT Gateway Công Nghiệp', 'slug' => 'iot-gateway', 'order' => 3],
            ['name' => 'Cảm Biến Công / Nông Nghiệp', 'slug' => 'cam-bien-cong-nong-nghiep', 'order' => 4],
            ['name' => 'Thiết Bị Giám Sát & Điều Khiển Từ Xa', 'slug' => 'giam-sat-dieu-khien-tu-xa', 'order' => 5],
        ];
        foreach ($iotSubCategories as $sub) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $sub['slug']],
                [
                    'parent_id' => $catIds['smart_iot'],
                    'name' => $sub['name'],
                    'sector_code' => 'smart_iot',
                    'description' => 'Danh mục thiết bị '.$sub['name'],
                    'icon' => 'DeviceTabletIcon',
                    'display_order' => $sub['order'],
                    'is_active' => true,
                    'updated_at' => now(),
                ]
            );
        }

        // Sub categories for Cloud & Infra
        $cloudSubCategories = [
            ['name' => 'Dedicated Server Vật Lý', 'slug' => 'dedicated-server', 'order' => 1],
            ['name' => 'Cloud VPS NVMe Hiệu Năng Cao', 'slug' => 'cloud-vps', 'order' => 2],
            ['name' => 'Dịch Vụ Cho Thuê Dải IP Sạch (IPv4/IPv6)', 'slug' => 'ip-services', 'order' => 3],
            ['name' => 'Private Cloud Cho SME & Doanh Nghiệp', 'slug' => 'private-cloud', 'order' => 4],
        ];
        foreach ($cloudSubCategories as $sub) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $sub['slug']],
                [
                    'parent_id' => $catIds['cloud_infra'],
                    'name' => $sub['name'],
                    'sector_code' => 'cloud_infra',
                    'description' => $sub['name'].' tiêu chuẩn Enterprise Tier 3',
                    'icon' => 'ServerStackIcon',
                    'display_order' => $sub['order'],
                    'is_active' => true,
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------------------------------------------
        // 4. VPS Plans (Comparison Matrix & Phase 2 Provisioning Hooks)
        // -------------------------------------------------------------
        $vpsPlans = [
            [
                'name' => 'Cloud VPS S1 (Khởi Tạo)',
                'slug' => 'vps-s1-basic',
                'vcpu' => 2,
                'ram_gb' => 4,
                'storage_nvme_gb' => 60,
                'bandwidth' => 'Không giới hạn (Port 1Gbps, Trong nước 100Mbps cam kết)',
                'port_speed' => '1 Gbps Network Port',
                'ipv4_count' => 1,
                'price_monthly' => 180000,
                'price_yearly' => 1836000,
                'discount_percent_yearly' => 15,
                'features' => [
                    'CPU Intel Xeon / AMD EPYC',
                    'Ổ cứng Pure NVMe Enterprise RAID-10',
                    'Miễn phí Anti-DDoS Layer 3/4/7',
                    'Backup tự động hàng tuần',
                    'Uptime SLA 99.9% cam kết',
                    'Hỗ trợ kỹ thuật 24/7/365',
                ],
                'is_popular' => false,
                'display_order' => 1,
                'phase2_template_id' => 'pmx-tpl-vps-s1-ubuntu24',
            ],
            [
                'name' => 'Cloud VPS S2 (Doanh Nghiệp - Khuyên dùng)',
                'slug' => 'vps-s2-standard',
                'vcpu' => 4,
                'ram_gb' => 8,
                'storage_nvme_gb' => 120,
                'bandwidth' => 'Không giới hạn (Port 10Gbps Uplink, Trong nước 200Mbps cam kết)',
                'port_speed' => '10 Gbps Uplink / 1 Gbps Port',
                'ipv4_count' => 1,
                'price_monthly' => 350000,
                'price_yearly' => 3570000,
                'discount_percent_yearly' => 15,
                'features' => [
                    '4 vCPU AMD EPYC 7763 xung nhịp cao',
                    '8 GB RAM ECC DDR4 Registered',
                    '120 GB NVMe Gen4 đọc ghi > 5000MB/s',
                    'Anti-DDoS Enterprise 100Gbps',
                    'Snapshot tức thời theo yêu cầu',
                    'Bàn giao cài đặt OS tùy chọn trong 15 phút',
                    'Hỗ trợ kỹ thuật 24/7/365 SLA 15 phút',
                ],
                'is_popular' => true,
                'display_order' => 2,
                'phase2_template_id' => 'pmx-tpl-vps-s2-enterprise',
            ],
            [
                'name' => 'Cloud VPS S3 (Chuyên Sâu / ERP / Database)',
                'slug' => 'vps-s3-pro',
                'vcpu' => 8,
                'ram_gb' => 16,
                'storage_nvme_gb' => 240,
                'bandwidth' => 'Không giới hạn (Port 10Gbps Uplink, Trong nước 300Mbps cam kết)',
                'port_speed' => '10 Gbps Uplink Dedicated',
                'ipv4_count' => 1,
                'price_monthly' => 690000,
                'price_yearly' => 7038000,
                'discount_percent_yearly' => 15,
                'features' => [
                    '8 vCPU Dedicated AMD EPYC',
                    '16 GB RAM ECC High-Performance',
                    '240 GB Enterprise NVMe RAID-10',
                    'IP sạch dải Doanh nghiệp uy tín cao',
                    'Backup kép hàng ngày (Daily Auto Backup)',
                    'Private VLAN kết nối nội bộ miễn phí',
                    'Hỗ trợ kỹ thuật 24/7/365 Kỹ sư cấp cao',
                ],
                'is_popular' => false,
                'display_order' => 3,
                'phase2_template_id' => 'pmx-tpl-vps-s3-db',
            ],
            [
                'name' => 'Cloud VPS Enterprise Max (Tải Lớn & Đa Chi Nhánh)',
                'slug' => 'vps-enterprise-max',
                'vcpu' => 16,
                'ram_gb' => 32,
                'storage_nvme_gb' => 500,
                'bandwidth' => 'Không giới hạn (Port 10Gbps Uplink, Băng thông riêng biệt)',
                'port_speed' => '10 Gbps Dedicated Uplink',
                'ipv4_count' => 2,
                'price_monthly' => 1350000,
                'price_yearly' => 13770000,
                'discount_percent_yearly' => 15,
                'features' => [
                    '16 vCPU AMD EPYC 64-Thread Master',
                    '32 GB RAM ECC DDR4 Server',
                    '500 GB NVMe Enterprise U.2/U.3 siêu tốc',
                    '2 Địa chỉ IPv4 tĩnh riêng biệt',
                    'Chống DDoS chuyên dụng Layer 7 Web Application',
                    'Cam kết SLA 99.99% bằng hợp đồng pháp lý',
                    'Dedicated Account Manager & Hotline trực tiếp',
                ],
                'is_popular' => false,
                'display_order' => 4,
                'phase2_template_id' => 'pmx-tpl-vps-ent-max',
            ],
        ];

        foreach ($vpsPlans as $plan) {
            DB::table('vps_plans')->updateOrInsert(
                ['slug' => $plan['slug']],
                [
                    'name' => $plan['name'],
                    'vcpu' => $plan['vcpu'],
                    'ram_gb' => $plan['ram_gb'],
                    'storage_nvme_gb' => $plan['storage_nvme_gb'],
                    'bandwidth' => $plan['bandwidth'],
                    'port_speed' => $plan['port_speed'],
                    'ipv4_count' => $plan['ipv4_count'],
                    'price_monthly' => $plan['price_monthly'],
                    'price_yearly' => $plan['price_yearly'],
                    'discount_percent_yearly' => $plan['discount_percent_yearly'],
                    'features' => json_encode($plan['features'], JSON_UNESCAPED_UNICODE),
                    'is_popular' => $plan['is_popular'],
                    'display_order' => $plan['display_order'],
                    'phase2_template_id' => $plan['phase2_template_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------------------------------------------
        // 5. Products with 11 Mandatory Components
        // -------------------------------------------------------------
        $allCatIds = DB::table('categories')->pluck('id', 'slug')->toArray();

        $products = [
            // Product 1: Dedicated Server
            [
                'sector_code' => 'cloud_infra',
                'category_id' => $catIds['cloud_infra'],
                'name' => 'Máy Chủ Vật Lý Dedicated Server AMD EPYC 7763',
                'slug' => 'dedicated-server-epyc-7763',
                'model_code' => 'TC-DED-EPYC7763',
                'thumbnail' => '/images/products/server-epyc-thumb.webp',
                'gallery' => [
                    '/images/products/server-epyc-front.webp',
                    '/images/products/server-epyc-inside.webp',
                    '/images/products/server-datacenter-rack.webp',
                ],
                'overview' => 'Máy chủ vật lý chuyên dụng Dedicated Server trang bị vi xử lý AMD EPYC 7763 (64 nhân, 128 luồng) đặt tại Trung tâm dữ liệu chuẩn Tier 3 tại TP.HCM và Hà Nội. Cung cấp tài nguyên phần cứng độc quyền 100%, bảo mật tuyệt đối cho các hệ thống ERP, ngân hàng, fintech và cơ sở dữ liệu lớn.',
                'features' => [
                    ['icon' => 'CpuChipIcon', 'title' => '64 Cores / 128 Threads', 'desc' => 'Hiệu năng xử lý đa nhiệm vượt trội với kiến trúc Zen 3 tiên tiến'],
                    ['icon' => 'CircleStackIcon', 'title' => '128GB RAM ECC DDR4', 'desc' => 'Tự sửa lỗi phần cứng, vận hành liên tục 24/7/365 không gián đoạn'],
                    ['icon' => 'BoltIcon', 'title' => '2x 1.92TB NVMe Enterprise', 'desc' => 'Cấu hình RAID 1 đảm bảo an toàn dữ liệu và tốc độ truy xuất cực đại'],
                    ['icon' => 'ShieldCheckIcon', 'title' => 'Chống DDoS Layer 3/4/7', 'desc' => 'Hệ thống tường lửa chuyên dụng lọc sạch lưu lượng tấn công mạng'],
                    ['icon' => 'ClockIcon', 'title' => 'SLA 99.98% & Thay thế linh kiện < 2h', 'desc' => 'Đội ngũ kỹ sư túc trực 24/7 tại Data Center'],
                ],
                'specs' => [
                    ['label' => 'Bộ vi xử lý (CPU)', 'value' => 'AMD EPYC 7763 (64 Cores / 128 Threads, 2.45GHz up to 3.50GHz, 256MB Cache)'],
                    ['label' => 'Bộ nhớ trong (RAM)', 'value' => '128GB DDR4 ECC Registered 3200MHz (Nâng cấp tối đa 1TB)'],
                    ['label' => 'Ổ cứng lưu trữ', 'value' => '2 x 1.92TB NVMe Enterprise Read Intensive U.2/U.3 Hardware RAID 1'],
                    ['label' => 'Băng thông mạng trong nước', 'value' => '300 Mbps Dedicated (Cam kết 100% không nghẽn)'],
                    ['label' => 'Băng thông quốc tế', 'value' => '20 Mbps Dedicated'],
                    ['label' => 'Cổng kết nối vật lý (Port)', 'value' => 'Dual 10Gbps SFP+ Redundant Uplink'],
                    ['label' => 'Địa chỉ IP khả dụng', 'value' => '1 IPv4 tĩnh riêng biệt (Hỗ trợ cấp dải /29, /28 theo nhu cầu)'],
                    ['label' => 'Hệ điều hành hỗ trợ', 'value' => 'Ubuntu Server, AlmaLinux, Rocky Linux, Windows Server Standard, VMware ESXi, Proxmox VE'],
                    ['label' => 'Vị trí đặt máy chủ', 'value' => 'Data Center VNPT IDC / Viettel IDC chuẩn Tier 3 Quốc Tế'],
                    ['label' => 'Quản trị từ xa (KVM)', 'value' => 'iLO / iDRAC / IPMI chuyên dụng toàn quyền truy cập BIOS'],
                ],
                'application_scenarios' => [
                    'Hệ thống phần mềm quản trị doanh nghiệp tổng thể ERP (SAP, Odoo, Oracle EBS)',
                    'Cơ sở dữ liệu trung tâm ngân hàng số, ví điện tử, sàn thương mại điện tử giao dịch lớn',
                    'Cụm ảo hóa Private Cloud Proxmox / OpenStack cho tập đoàn đa chi nhánh',
                    'Hạ tầng xử lý phân tích dữ liệu lớn (Big Data) và huấn luyện mô hình AI nội bộ',
                ],
                'datasheet_pdf_path' => '/datasheets/toancau-dedicated-epyc7763.pdf',
                'warranty_policy' => 'Cam kết thay thế linh kiện phần cứng hỏng hóc trong vòng 1-2 giờ kể từ khi phát hiện lỗi; Bồi thường SLA theo hợp đồng.',
                'support_commitment' => 'Đội ngũ Kỹ sư Hạ tầng Toàn Cầu hỗ trợ trực tiếp 24/7/365 qua Hotline kỹ thuật riêng, Zalo Group hỗ trợ khẩn cấp và Ticket phản hồi dưới 15 phút.',
                'related_product_ids' => [2, 3, 4],
                'is_price_visible' => true,
                'price' => 5800000,
                'price_unit' => 'tháng',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Thuê Máy Chủ Vật Lý Dedicated Server AMD EPYC 7763 | Toàn Cầu Tech',
                'meta_description' => 'Dịch vụ cho thuê máy chủ vật lý Dedicated Server EPYC 7763 chính hãng, 128GB RAM ECC, 2x 1.92TB NVMe Enterprise tại Data Center Tier 3 Toàn Cầu Technology.',
                'provisioning_driver' => 'manual',
                'provisioning_metadata' => ['rack' => 'DC-Q9-R12', 'pdu' => 'A+B', 'kvm_ip' => '10.200.0.12'],
            ],

            // Product 2: Cloud VPS
            [
                'sector_code' => 'cloud_infra',
                'category_id' => $catIds['cloud_infra'],
                'name' => 'Máy Chủ Ảo Cloud VPS NVMe Doanh Nghiệp S2',
                'slug' => 'cloud-vps-pro-nvme-s2',
                'model_code' => 'TC-VPS-PRO-S2',
                'thumbnail' => '/images/products/vps-pro-thumb.webp',
                'gallery' => [
                    '/images/products/vps-pro-cloud.webp',
                    '/images/products/vps-pro-dashboard.webp',
                ],
                'overview' => 'Giải pháp máy chủ ảo Cloud VPS thế hệ mới xây dựng trên nền tảng điện toán đám mây phân tán Ceph Storage và vi xử lý AMD EPYC xung nhịp cao. Khởi tạo trong 15 giây, khả năng mở rộng linh hoạt không làm gián đoạn dịch vụ, tích hợp sẵn chống DDoS tầng mạng và tầng ứng dụng.',
                'features' => [
                    ['icon' => 'CpuChipIcon', 'title' => '4 vCPU EPYC Tối Ưu', 'desc' => 'Tài nguyên vCPU cô lập 100%, không bị ảnh hưởng bởi máy chủ ảo lân cận'],
                    ['icon' => 'ServerIcon', 'title' => '8GB RAM ECC & 120GB NVMe', 'desc' => 'Tốc độ IOPS lên tới 100.000 IOPS cho các tác vụ Web/App chuyên sâu'],
                    ['icon' => 'ShieldCheckIcon', 'title' => 'Tường Lửa Chống DDoS Miễn Phí', 'desc' => 'Bảo vệ website trước các đợt tấn công SYN Flood, UDP Flood, HTTP Flood'],
                    ['icon' => 'ArrowPathIcon', 'title' => 'Snapshot & Backup Đám Mây', 'desc' => 'Khôi phục hệ thống trong 1 cú nhấp chuột khi có sự cố ứng dụng'],
                ],
                'specs' => [
                    ['label' => 'Số lượng vCPU', 'value' => '4 vCPU AMD EPYC High Frequency (2.8GHz+)'],
                    ['label' => 'Dung lượng RAM', 'value' => '8 GB DDR4 ECC'],
                    ['label' => 'Dung lượng lưu trữ', 'value' => '120 GB Enterprise NVMe RAID-10 (Ceph 3 bản sao)'],
                    ['label' => 'Lưu lượng truyền tải (Data Transfer)', 'value' => 'Không giới hạn (Unmetered Bandwidth)'],
                    ['label' => 'Băng thông cổng mạng', 'value' => '1 Gbps Shared (Tối thiểu 150 Mbps cam kết trong nước)'],
                    ['label' => 'Địa chỉ IP tĩnh', 'value' => '1 IPv4 sạch riêng biệt'],
                    ['label' => 'Hệ điều hành', 'value' => 'Windows Server 2019/2022, Ubuntu 22.04/24.04, Debian 12, CentOS 7, AlmaLinux 9'],
                    ['label' => 'Bảng điều khiển (Control Panel)', 'value' => 'Giao diện tự phục vụ: Khởi động, Khởi động lại, Cài lại OS, Đổi mật khẩu root'],
                    ['label' => 'Thời gian cam kết hoạt động (SLA)', 'value' => '99.95%'],
                ],
                'application_scenarios' => [
                    'Website thương mại điện tử, cổng thông tin báo chí có lưu lượng truy cập hàng triệu lượt/tháng',
                    'Hệ thống Backend ứng dụng di động Mobile API, máy chủ xác thực tài khoản',
                    'Máy chủ VPN nội bộ kết nối bảo mật các chi nhánh văn phòng',
                    'Môi trường chạy Docker Containers, Gitlab CI/CD Runner',
                ],
                'datasheet_pdf_path' => '/datasheets/toancau-cloud-vps-matrix.pdf',
                'warranty_policy' => 'Cam kết tính liên tục dịch vụ SLA 99.95%. Hoàn tiền theo tỷ lệ thời gian gián đoạn nếu vi phạm cam kết.',
                'support_commitment' => 'Hỗ trợ kỹ thuật 24/7 qua cổng Ticket và Zalo hỗ trợ. Xử lý sự cố hạ tầng khẩn cấp trong 15 phút.',
                'related_product_ids' => [1, 3, 5],
                'is_price_visible' => true,
                'price' => 350000,
                'price_unit' => 'tháng',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Thuê Cloud VPS NVMe Doanh Nghiệp S2 | Toàn Cầu Tech',
                'meta_description' => 'Máy chủ ảo Cloud VPS NVMe S2: 4 vCPU, 8GB RAM, 120GB NVMe, chống DDoS toàn diện, SLA 99.95% tại Toàn Cầu Technology.',
                'provisioning_driver' => 'proxmox',
                'provisioning_metadata' => ['template_id' => 'pmx-tpl-vps-s2-enterprise', 'pool' => 'pool-b2b-shared'],
            ],

            // Product 3: Clean IP Service
            [
                'sector_code' => 'cloud_infra',
                'category_id' => $catIds['cloud_infra'],
                'name' => 'Dịch Vụ Cho Thuê Dải IPv4 Sạch Doanh Nghiệp (/24 Clean Subnet)',
                'slug' => 'cho-thue-dai-ipv4-sach-doanh-nghiep',
                'model_code' => 'TC-IP-V4-SUBNET24',
                'thumbnail' => '/images/products/ip-services-thumb.webp',
                'gallery' => [
                    '/images/products/ip-network-routing.webp',
                    '/images/products/ipam-portal.webp',
                ],
                'overview' => 'Dịch vụ cho thuê tài nguyên địa chỉ IPv4 sạch được cấp phát chính ngạch từ APNIC / VNNIC. Toàn Cầu Technology quản lý hơn 4.000 địa chỉ IP uy tín, 100% sạch trên các tổ chức chống spam quốc tế (Spamhaus, Barracuda, SORBS). Hỗ trợ định tuyến BGP công bố qua số ASN riêng của khách hàng hoặc cấp phát trực tiếp trên hạ tầng Data Center Toàn Cầu.',
                'features' => [
                    ['icon' => 'ShieldCheckIcon', 'title' => '100% Clean IP Reputation', 'desc' => 'Được kiểm tra rà soát blacklist định kỳ trước khi bàn giao cho doanh nghiệp'],
                    ['icon' => 'ArrowsRightLeftIcon', 'title' => 'Hỗ trợ LOA & BGP Routing', 'desc' => 'Cung cấp Thư ủy quyền (LOA) để thông báo định tuyến trên ASN độc lập của quý khách'],
                    ['icon' => 'DocumentTextIcon', 'title' => 'Hợp Đồng Pháp Lý Đầy Đủ', 'desc' => 'Xuất hóa đơn VAT, cam kết tuân thủ quy định quản lý tài nguyên Internet Việt Nam'],
                    ['icon' => 'FolderIcon', 'title' => 'Cổng Quản Trị rDNS / PTR Record', 'desc' => 'Hỗ trợ cấu hình bản ghi phân giải ngược rDNS linh hoạt qua đội ngũ kỹ sư'],
                ],
                'specs' => [
                    ['label' => 'Quy mô dải IP (Subnet)', 'value' => '/24 (Gồm 256 địa chỉ IPv4 khả dụng)'],
                    ['label' => 'Nguồn gốc tài nguyên', 'value' => 'Được cấp phát từ RIR APNIC / VNNIC chính ngạch'],
                    ['label' => 'Tình trạng Blacklist', 'value' => 'Sạch 100% (Clean Spamhaus, SpamCop, Barracuda, v.v.)'],
                    ['label' => 'Hình thức công bố định tuyến', 'value' => 'Route Object + LOA ủy quyền BGP Announce hoặc Route qua hạ tầng Toàn Cầu'],
                    ['label' => 'Hỗ trợ rDNS / PTR Record', 'value' => 'Có (Hỗ trợ cấu hình nhanh trong 30 phút)'],
                    ['label' => 'Chu kỳ thanh toán linh hoạt', 'value' => '3 tháng, 6 tháng, 12 tháng (Ưu đãi chiết khấu theo năm)'],
                    ['label' => 'Điều kiện sử dụng bắt buộc', 'value' => 'Chỉ phục vụ mục đích hợp pháp (Web, VPN, Mail Server, Cloud); Tuyệt đối cấm Spam, Phishing, Tấn công mạng'],
                ],
                'application_scenarios' => [
                    'Doanh nghiệp viễn thông, ISP địa phương cần mở rộng pool IP cho khách hàng',
                    'Công ty công nghệ triển khai cụm Mail Server doanh nghiệp yêu cầu IP có độ uy tín cực cao',
                    'Đơn vị xây dựng hạ tầng Cloud / VPS / Hosting bán lẻ cần dải IP sạch dài hạn',
                    'Các tập đoàn đa quốc gia cần IP Việt Nam cố định để đấu nối VPN Site-to-Site và hệ thống tài chính',
                ],
                'datasheet_pdf_path' => '/datasheets/toancau-ipam-policy.pdf',
                'warranty_policy' => 'Bảo đảm quyền sở hữu sử dụng hợp pháp trong suốt thời gian hợp đồng; Hỗ trợ thay thế IP nếu phát sinh lỗi khách quan từ hệ thống mạng quốc tế.',
                'support_commitment' => 'Kỹ sư mạng CCIE/JNCIP của Toàn Cầu trực tiếp hỗ trợ phối hợp nhà mạng ISP định tuyến BGP và khai báo ROA/RPKI.',
                'related_product_ids' => [1, 2, 4],
                'is_price_visible' => false, // Set to false to trigger "Liên hệ báo giá"
                'price' => null,
                'price_unit' => 'tháng',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Cho Thuê Dải IPv4 Sạch /24 Doanh Nghiệp | Toàn Cầu Tech',
                'meta_description' => 'Dịch vụ cho thuê dải địa chỉ IPv4 sạch /24, /23 từ APNIC với hơn 4.000 IP uy tín, hỗ trợ LOA công bố BGP ASN tại Toàn Cầu Technology.',
                'provisioning_driver' => 'ipam',
                'provisioning_metadata' => ['subnet_size' => '/24', 'rir' => 'APNIC'],
            ],

            // Product 4: Internet Leased Line
            [
                'sector_code' => 'internet',
                'category_id' => $catIds['internet'],
                'name' => 'Kênh Thuê Riêng Internet Leased Line Doanh Nghiệp Cam Kết SLA 99.9%',
                'slug' => 'internet-leased-line-doanh-nghiep-sla',
                'model_code' => 'TC-NET-LL-1000',
                'thumbnail' => '/images/products/leasedline-thumb.webp',
                'gallery' => [
                    '/images/products/leasedline-fiber.webp',
                    '/images/products/network-monitoring-noc.webp',
                ],
                'overview' => 'Kênh truyền Internet cáp quang đối xứng riêng biệt dành cho trụ sở tập đoàn, khu công nghiệp và tòa nhà văn phòng hạng A. Cam kết 100% băng thông tải lên (Upload) bằng tải xuống (Download), độ trễ thấp dưới 3ms, không chia sẻ với người dùng khác và được bảo đảm bằng Hợp đồng cam kết mức dịch vụ SLA 99.9%.',
                'features' => [
                    ['icon' => 'ArrowsUpDownIcon', 'title' => 'Băng Thông Đối Xứng 1:1', 'desc' => 'Tốc độ Upload = Download tuyệt đối, ổn định 24/7 ngay cả trong giờ cao điểm'],
                    ['icon' => 'SignalIcon', 'title' => 'Định Tuyến Đa Hướng (Multi-Homing)', 'desc' => 'Kết nối đồng thời qua nhiều tuyến cáp quang biển quốc tế (APG, AAG, IA, AAE-1)'],
                    ['icon' => 'ShieldCheckIcon', 'title' => 'SLA 99.9% Khắt Khe', 'desc' => 'Cam kết xử lý sự cố đứt cáp hoặc suy hao trong thời gian dưới 120 phút'],
                    ['icon' => 'SparklesIcon', 'title' => 'Trang Bị Router Cisco/Mikrotik', 'desc' => 'Cung cấp miễn phí thiết bị định tuyến công nghiệp cấu hình dự phòng High Availability'],
                ],
                'specs' => [
                    ['label' => 'Băng thông trong nước (Domestic)', 'value' => '100 Mbps – 10 Gbps (Theo gói đăng ký)'],
                    ['label' => 'Băng thông quốc tế cam kết (International)', 'value' => '10 Mbps – 1 Gbps (Cam kết tối thiểu 100% không nghẽn)'],
                    ['label' => 'Phương thức truyền dẫn', 'value' => 'Cáp quang đơn mốt (Single-mode Fiber) kéo trực tiếp từ tổng đài POP tới phòng Server khách hàng'],
                    ['label' => 'Số lượng IP tĩnh miễn phí', 'value' => 'Tặng kèm 08 địa chỉ IPv4 tĩnh (/29)'],
                    ['label' => 'Giao thức hỗ trợ', 'value' => 'Static Routing, BGP Multi-homing, OSPF, MPLS VPN'],
                    ['label' => 'Giám sát chủ động (Proactive NOC)', 'value' => 'Hệ thống giám sát 24/7, tự động cảnh báo SMS/Email trước khi khách hàng phát hiện sự cố'],
                    ['label' => 'Thời gian triển khai', 'value' => '3 - 5 ngày làm việc trên toàn quốc'],
                ],
                'application_scenarios' => [
                    'Trụ sở chính các ngân hàng, công ty chứng khoán, bảo hiểm cần giao dịch liên tục thời gian thực',
                    'Các nhà máy sản xuất trong Khu công nghiệp (KCN) ứng dụng tự động hóa và kết nối ERP với nhà máy mẹ ở nước ngoài',
                    'Doanh nghiệp gia công phần mềm quốc tế, Studio Render đồ họa dung lượng lớn',
                    'Khách sạn 5 sao, Trung tâm hội nghị truyền hình trực tuyến 4K/8K',
                ],
                'datasheet_pdf_path' => '/datasheets/toancau-leased-line-sla.pdf',
                'warranty_policy' => 'Bảo trì đường truyền cáp quang trọn đời hợp đồng; Thay thế thiết bị modem/converter trong vòng 2 giờ nếu xảy ra hỏng hóc phần cứng.',
                'support_commitment' => 'Đội cơ động ứng cứu thông tin mặt đất có mặt tại hiện trường trong vòng 60 phút tại các thành phố lớn.',
                'related_product_ids' => [1, 3, 5],
                'is_price_visible' => false,
                'price' => null,
                'price_unit' => 'tháng',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Internet Leased Line Doanh Nghiệp SLA 99.9% | Toàn Cầu Tech',
                'meta_description' => 'Dịch vụ Kênh thuê riêng Internet Leased Line doanh nghiệp băng thông đối xứng 1:1, cam kết SLA 99.9%, hỗ trợ kỹ thuật 24/7 bởi Toàn Cầu Technology.',
                'provisioning_driver' => 'manual',
                'provisioning_metadata' => ['pop_site' => 'POP-HCM-Q1', 'switch_port' => 'Te0/1/2'],
            ],

            // Product 5: Bluecom Smart Radio
            [
                'sector_code' => 'bluecom',
                'category_id' => $catIds['bluecom'],
                'name' => 'Bộ Thu Phát Truyền Thanh Thông Minh Bluecom 4G Thế Hệ Mới',
                'slug' => 'bo-thu-phat-truyen-thanh-thong-minh-bluecom-4g',
                'model_code' => 'BC-4G-SMART-RX',
                'thumbnail' => '/images/products/bluecom-rx-thumb.webp',
                'gallery' => [
                    '/images/products/bluecom-speaker-setup.webp',
                    '/images/products/bluecom-cms-screen.webp',
                ],
                'overview' => 'Thiết bị truyền thanh thông minh ứng dụng công nghệ viễn thông 4G LTE và điện toán đám mây Bluecom. Thay thế hoàn toàn hệ thống loa truyền thanh có dây và FM truyền thống dễ nhiễu sóng. Cho phép phát thanh trực tiếp từ điện thoại thông minh, chuyển văn bản thành giọng nói trí tuệ nhân tạo (AI Text-To-Speech) và quản lý phân cấp xã/huyện/tỉnh.',
                'features' => [
                    ['icon' => 'SignalIcon', 'title' => 'Kết Nối SIM 4G Đa Mạng', 'desc' => 'Tự động bắt sóng Viettel, VinaPhone, MobiFone; vận hành ngay cả khi mất điện thoại cố định'],
                    ['icon' => 'SpeakerWaveIcon', 'title' => 'Chất Lượng Âm Thanh Hi-Fi Trong Trẻo', 'desc' => 'Bộ giải mã âm thanh số chuẩn MP3/AAC không tạp âm, không lẫn sóng FM'],
                    ['icon' => 'DevicePhoneMobileIcon', 'title' => 'Phát Thanh Khẩn Cấp Bằng Điện Thoại', 'desc' => 'Lãnh đạo cơ quan có thể phát thông báo phòng chống bão lũ trực tiếp qua điện thoại'],
                    ['icon' => 'SparklesIcon', 'title' => 'AI Đọc Bản Tin Tự Động', 'desc' => 'Tự động chuyển văn bản thông báo thành giọng đọc truyền cảm chuẩn Bắc / Trung / Nam'],
                    ['icon' => 'WrenchIcon', 'title' => 'Vỏ Hợp Kim Đúc Chống Nước IP66', 'desc' => 'Chống sét lan truyền, chịu được nắng gắt mưa bão nhiệt đới tại Việt Nam'],
                ],
                'specs' => [
                    ['label' => 'Mã thiết bị (Model)', 'value' => 'BC-4G-SMART-RX / Indoor-Outdoor Edition'],
                    ['label' => 'Module mạng viễn thông', 'value' => 'Quectel LTE Cat 4 (B1/B3/B5/B8) hỗ trợ khe cắm Nano-SIM kép'],
                    ['label' => 'Công suất ngõ ra âm thanh', 'value' => 'Hỗ trợ kết nối cụm loa phóng thanh 60W - 120W (Line 100V / 70V / 8 Ohm)'],
                    ['label' => 'Bộ vi xử lý trung tâm', 'value' => 'ARM Cortex Quad-Core chuyên dụng cho xử lý DSP Audio thời gian thực'],
                    ['label' => 'Hỗ trợ bộ nhớ đệm Offline', 'value' => 'Bộ nhớ trong 16GB lưu trữ bản tin phát lại ngay cả khi mất sóng 4G tạm thời'],
                    ['label' => 'Tiêu chuẩn bảo vệ kháng nước', 'value' => 'IP66 chống nước mưa, bụi cát, chống sét 6kV tích hợp'],
                    ['label' => 'Phần mềm điều khiển tập trung', 'value' => 'Bluecom Cloud Web CMS + Mobile App (Android/iOS) phân quyền 4 cấp'],
                    ['label' => 'Nguồn điện hoạt động', 'value' => 'AC 180V - 240V / 50Hz (Có tùy chọn cấp nguồn qua Pin Mặt Trời Solar)'],
                ],
                'application_scenarios' => [
                    'Hệ thống truyền thanh cơ sở hiện đại hóa cho Ủy ban Nhân dân cấp Xã, Phường, Thị trấn',
                    'Hệ thống phát thanh thông báo khẩn cấp, hướng dẫn sơ tán trong Nhà máy, Khu công nghiệp rộng lớn',
                    'Truyền thanh điều hành tại các Hợp tác xã Nông nghiệp, Cảng cá ven biển, Nông trường cao su',
                    'Hệ thống thông báo tại các Trường đại học, Bệnh viện, Khu du lịch sinh thái',
                ],
                'datasheet_pdf_path' => '/datasheets/bluecom-4g-smart-broadcasting.pdf',
                'warranty_policy' => 'Bảo hành chính hãng 24 tháng theo tiêu chuẩn nhà sản xuất Bluecom; 1 đổi 1 trong vòng 30 ngày đối với lỗi phần cứng do nhà sản xuất.',
                'support_commitment' => 'Toàn Cầu cử chuyên viên kỹ thuật khảo sát hiện trường miễn phí, tập huấn chuyển giao công nghệ cho cán bộ vận hành và hỗ trợ từ xa qua Hotline 24/7.',
                'related_product_ids' => [6, 7, 8],
                'is_price_visible' => true,
                'price' => 4850000,
                'price_unit' => 'bộ',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Bộ Thu Phát Truyền Thanh Thông Minh Bluecom 4G | Toàn Cầu Tech',
                'meta_description' => 'Thiết bị truyền thanh thông minh Bluecom 4G chống nước IP66, chuyển văn bản thành giọng nói AI, điều khiển qua điện thoại cho xã phường và nhà máy.',
                'provisioning_driver' => 'manual',
                'provisioning_metadata' => ['device_type' => 'bluecom_rx', 'firmware' => 'v3.4.1-rc2'],
            ],

            // Product 6: Solar Camera 4G
            [
                'sector_code' => 'smart_iot',
                'category_id' => $allCatIds['solar-camera'] ?? $catIds['smart_iot'],
                'name' => 'Camera Giám Sát Năng Lượng Mặt Trời Solar 4G Chuyên Dụng Ven Biển IP67',
                'slug' => 'solar-camera-4g-giam-sat-ven-bien-ip67',
                'model_code' => 'TC-IOT-SOLAR-4G',
                'thumbnail' => '/images/products/solar-cam-thumb.webp',
                'gallery' => [
                    '/images/products/solar-cam-farm.webp',
                    '/images/products/solar-cam-nightvision.webp',
                ],
                'overview' => 'Giải pháp giám sát an ninh độc lập hoàn toàn không cần kéo dây điện và dây mạng. Tích hợp tấm pin năng lượng mặt trời công suất cao Mono-crystalline kết hợp pin lưu trữ Lithium LiFePO4 dung lượng lớn, bảo đảm ghi hình liên tục 365 ngày kể cả trong điều kiện mưa bão kéo dài 5 ngày liên tiếp.',
                'features' => [
                    ['icon' => 'SunIcon', 'title' => 'Độc Lập 100% Nguồn & Mạng', 'desc' => 'Tự sạc năng lượng mặt trời và truyền dữ liệu video sắc nét qua sóng di động 4G LTE'],
                    ['icon' => 'EyeIcon', 'title' => 'Độ Phân Giải 2K Super HD & Màu Ban Đêm', 'desc' => 'Cảm biến Sony Starlight ghi lại hình ảnh có màu rõ nét trong bóng tối hoàn toàn'],
                    ['icon' => 'ShieldCheckIcon', 'title' => 'Chống Nước Mặn Ăn Mòn IP67', 'desc' => 'Vỏ hợp kim sơn tĩnh điện chuyên dụng kháng sương muối ven biển và bụi cát công trình'],
                    ['icon' => 'UserIcon', 'title' => 'AI Nhận Diện Người & Cảnh Báo Còi Hú', 'desc' => 'Phát hiện xâm nhập chính xác, kích hoạt đèn chớp cảnh báo và gửi thông báo tới điện thoại'],
                ],
                'specs' => [
                    ['label' => 'Độ phân giải cảm biến', 'value' => '4.0 Megapixel (2560 x 1440) @ 25fps'],
                    ['label' => 'Tấm pin năng lượng mặt trời', 'value' => 'Mono 40W hiệu suất chuyển đổi quang năng > 22%'],
                    ['label' => 'Pin lưu trữ tích hợp', 'value' => 'Lithium LiFePO4 dung lượng 20.000mAh (Tuổi thọ trên 5 năm)'],
                    ['label' => 'Thời gian duy trì khi không có nắng', 'value' => 'Lên tới 120 giờ (tương đương 5 ngày mưa bão liên tục)'],
                    ['label' => 'Tầm nhìn ban đêm (Hồng ngoại & LED)', 'value' => 'Khoảng cách quan sát 30 - 40 mét trong đêm tối'],
                    ['label' => 'Góc quay quét (PTZ)', 'value' => 'Xoay ngang 355 độ, xoay dọc 90 độ, điều khiển mượt mà qua ứng dụng di động'],
                    ['label' => 'Chuẩn nén video', 'value' => 'H.265+ tiết kiệm 70% dung lượng data 4G và thẻ nhớ'],
                    ['label' => 'Lưu trữ hỗ trợ', 'value' => 'Khe cắm thẻ nhớ MicroSD tối đa 256GB + Lưu trữ Cloud bảo mật'],
                ],
                'application_scenarios' => [
                    'Các trang trại nuôi trồng thủy hải sản, ao tôm, lồng bè nuôi cá ven biển và hải đảo',
                    'Khu vực công trường xây dựng, san lấp mặt bằng chưa có hạ tầng điện lưới và cáp mạng',
                    'Vườn cây ăn trái, trang trại sầu riêng, cao su, trang trại điện mặt trời diện tích hàng trăm hecta',
                    'Trạm kiểm lâm, chốt bảo vệ rừng phòng hộ và hồ chứa thủy điện',
                ],
                'datasheet_pdf_path' => '/datasheets/toancau-solar-cam-4g.pdf',
                'warranty_policy' => 'Bảo hành chính hãng 24 tháng cho toàn bộ thân camera, tấm pin solar và pack pin lưu trữ LiFePO4.',
                'support_commitment' => 'Tư vấn lựa chọn vị trí đón nắng tối ưu, hướng dẫn cài đặt qua video call 1-1 và hỗ trợ kỹ thuật tận nơi cho các dự án quy mô.',
                'related_product_ids' => [5, 7, 8],
                'is_price_visible' => true,
                'price' => 3890000,
                'price_unit' => 'bộ',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Camera Năng Lượng Mặt Trời Solar 4G Ven Biển IP67 | Toàn Cầu Tech',
                'meta_description' => 'Camera năng lượng mặt trời Solar 4G ngoài trời chống nước IP67, pin Lithium 20.000mAh duy trì 5 ngày mưa, cảm biến hình ảnh 2K ban đêm có màu.',
                'provisioning_driver' => 'manual',
                'provisioning_metadata' => ['device_family' => 'solar_camera', 'sensor' => 'sony_imx335'],
            ],

            // Product 7: Industrial IoT Gateway
            [
                'sector_code' => 'smart_iot',
                'category_id' => $allCatIds['iot-gateway'] ?? $catIds['smart_iot'],
                'name' => 'Bộ Thu Thập Dữ Liệu IoT Gateway Công Nghiệp Modbus RTU / MQTT / 4G',
                'slug' => 'iot-gateway-cong-nghiep-modbus-mqtt-4g',
                'model_code' => 'TC-GW-IND-4G',
                'thumbnail' => '/images/products/iot-gw-thumb.webp',
                'gallery' => [
                    '/images/products/iot-gw-diagram.webp',
                    '/images/products/iot-gw-cabinet.webp',
                ],
                'overview' => 'Thiết bị Gateway IoT công nghiệp chuẩn DIN-Rail chuyên dụng cho chuyển đổi dữ liệu từ các cảm biến, đồng hồ đo điện nước, PLC về máy chủ đám mây qua giao thức MQTT / HTTP / Modbus TCP. Thiết kế chống nhiễu điện từ cấp công nghiệp, dải nhiệt độ hoạt động rộng từ -40°C đến +85°C.',
                'features' => [
                    ['icon' => 'CpuChipIcon', 'title' => 'Đa Dạng Giao Tiếp Công Nghiệp', 'desc' => 'Tích hợp 2 cổng RS485 cách ly quang học, 1 cổng RS232, 2 cổng Ethernet RJ45 và 4 ngõ DI/DO'],
                    ['icon' => 'CloudArrowUpIcon', 'title' => 'Chuyển Đổi MQTT / JSON Đám Mây', 'desc' => 'Tự động đóng gói dữ liệu Modbus thành bản tin JSON gửi lên AWS IoT, Azure IoT hoặc Private Server'],
                    ['icon' => 'ShieldCheckIcon', 'title' => 'Độ Tin Cậy Cao Watchdog Kép', 'desc' => 'Phần cứng Watchdog độc lập tự động khởi động lại thiết bị khi mất kết nối viễn thông'],
                    ['icon' => 'CircleStackIcon', 'title' => 'Lưu Đệm Dữ Liệu Khi Mất Mạng', 'desc' => 'Bộ nhớ Flash nội bộ lưu trữ dữ liệu đo lường khi mất sóng 4G và tự động gửi bù khi có mạng'],
                ],
                'specs' => [
                    ['label' => 'Giao diện truyền thông nối tiếp', 'value' => '2 x RS485 (Bảo vệ chống sét lan truyền 15kV ESD, cách ly quang điện 2.5kV) + 1 x RS232'],
                    ['label' => 'Giao tiếp mạng viễn thông', 'value' => '4G LTE-FDD / LTE-TDD + 1 cổng WAN/LAN 10/100Mbps Ethernet'],
                    ['label' => 'Ngõ vào / ra số (I/O)', 'value' => '4 x Digital Input (DI cách ly khô/ướt) + 4 x Digital Output (Relay 250VAC/5A)'],
                    ['label' => 'Giao thức truyền thông hỗ trợ', 'value' => 'Modbus RTU Master/Slave, Modbus TCP, MQTT Client, CoAP, HTTP POST, OPC UA'],
                    ['label' => 'Điện áp hoạt động', 'value' => 'DC 9V - 36V (Bảo vệ đấu ngược cực và quá áp)'],
                    ['label' => 'Phương thức lắp đặt', 'value' => 'Gá thanh cài chuẩn công nghiệp 35mm DIN-Rail hoặc bắt vít bảng điện'],
                    ['label' => 'Dải nhiệt độ môi trường', 'value' => 'Hoạt động ổn định từ -40°C đến +85°C, độ ẩm 5% - 95% không đọng sương'],
                ],
                'application_scenarios' => [
                    'Giám sát trạm quan trắc nước thải tự động, trạm quan trắc khí thải nhà máy KCN',
                    'Hệ thống quản lý năng lượng giám sát đồng hồ điện thông minh (Smart Metering) trong cao ốc',
                    'Thu thập thông số độ ẩm đất, EC, pH, nhiệt độ trong hệ thống nhà màng nông nghiệp thông minh',
                    'Điều khiển đóng ngắt máy bơm, quạt thông gió từ xa qua điện thoại hoặc lập lịch tự động',
                ],
                'datasheet_pdf_path' => '/datasheets/toancau-iot-gateway-modbus.pdf',
                'warranty_policy' => 'Bảo hành công nghiệp 36 tháng; Hỗ trợ đổi mới trong 60 ngày nếu có lỗi phần cứng.',
                'support_commitment' => 'Đội ngũ kỹ sư tự động hóa Toàn Cầu hỗ trợ cấu hình sơ đồ truyền thông, kết nối thử nghiệm cảm biến và cung cấp tài liệu API tích hợp miễn phí.',
                'related_product_ids' => [5, 6, 8],
                'is_price_visible' => true,
                'price' => 2950000,
                'price_unit' => 'bộ',
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'IoT Gateway Công Nghiệp Modbus MQTT 4G | Toàn Cầu Tech',
                'meta_description' => 'Thiết bị IoT Gateway công nghiệp RS485/Modbus sang MQTT/4G DIN-Rail dải nhiệt độ -40°C đến 85°C chính hãng Toàn Cầu Technology.',
                'provisioning_driver' => 'manual',
                'provisioning_metadata' => ['protocol' => 'mqtt', 'io_channels' => 8],
            ],

            // Product 8: Industrial Router Bluecom Dual SIM
            [
                'sector_code' => 'bluecom',
                'category_id' => $catIds['bluecom'],
                'name' => 'Bộ Định Tuyến Công Nghiệp Bluecom Dual-SIM 4G/Wi-Fi 6',
                'slug' => 'router-dinh-tuyen-cong-nghiep-bluecom-dual-sim',
                'model_code' => 'BC-RT-IND-W6',
                'thumbnail' => '/images/products/bluecom-router-thumb.webp',
                'gallery' => [
                    '/images/products/bluecom-router-ports.webp',
                    '/images/products/bluecom-router-dinrail.webp',
                ],
                'overview' => 'Router định tuyến mạng công nghiệp hiệu năng cao thiết kế vỏ nhôm đúc nguyên khối tản nhiệt tự nhiên. Trang bị khe cắm Dual-SIM tự động chuyển mạch dự phòng (Failover), kết nối chuẩn Wi-Fi 6 tốc độ cao cùng 5 cổng Gigabit Ethernet. Phù hợp tuyệt đối cho môi trường xe tự hành AGV, trạm thu phí không dừng, ATM ngân hàng và tàu du lịch.',
                'features' => [
                    ['icon' => 'ArrowsRightLeftIcon', 'title' => 'Dự Phòng Dual-SIM Tự Động', 'desc' => 'Tự động chuyển tiếp thông minh giữa mạng Viettel và VinaPhone trong vòng dưới 3 giây khi 1 SIM yếu sóng'],
                    ['icon' => 'WifiIcon', 'title' => 'Công Nghệ Wi-Fi 6 Dual-Band', 'desc' => 'Băng thông lên tới 1800Mbps cho phép hơn 100 thiết bị kết nối ổn định đồng thời'],
                    ['icon' => 'LockClosedIcon', 'title' => 'Bảo Mật VPN Doanh Nghiệp', 'desc' => 'Tích hợp sẵn IPsec, OpenVPN, WireGuard, GRE, PPTP bảo đảm an toàn dữ liệu nội bộ'],
                    ['icon' => 'WrenchIcon', 'title' => 'Khung Nhôm Chuẩn Công Nghiệp IP40', 'desc' => 'Chống rung động theo chuẩn quân sự, chịu dải điện áp đầu vào linh hoạt từ 9V đến 48V DC'],
                ],
                'specs' => [
                    ['label' => 'Cổng mạng Ethernet', 'value' => '1 x Gigabit WAN + 4 x Gigabit LAN (Hỗ trợ chuyển đổi linh hoạt WAN/LAN)'],
                    ['label' => 'Giao tiếp 4G LTE', 'value' => 'LTE Cat 6 (Tốc độ tải xuống 300Mbps, tải lên 50Mbps) kèm 2 Anten ngoài độ nhạy cao'],
                    ['label' => 'Chuẩn Wi-Fi', 'value' => '802.11ax/ac/b/g/n (Wi-Fi 6) 2.4GHz & 5.8GHz 2x2 MU-MIMO'],
                    ['label' => 'Khe cắm thẻ SIM', 'value' => '2 x Khe cắm Standard SIM hỗ trợ tự động dò sóng chuyển mạng'],
                    ['label' => 'Bộ vi xử lý & Bộ nhớ', 'value' => 'MediaTek Dual-Core 880MHz Network Processor, 256MB DDR3 RAM, 128MB Flash'],
                    ['label' => 'Tính năng mạng thông minh', 'value' => 'Cân bằng tải đa đường truyền (Multi-WAN Load Balancing), Giám sát Ping Watchdog, QoS lưu lượng'],
                    ['label' => 'Hệ thống quản lý từ xa', 'value' => 'Hỗ trợ Bluecom RMS Cloud: Giám sát GPS, Cập nhật Firmware hàng loạt, Cấu hình từ xa qua Web'],
                ],
                'application_scenarios' => [
                    'Trạm giao dịch ngân hàng tự động ATM / CDM và quầy dịch vụ di động',
                    'Hệ thống thu phí không dừng ETC và camera giám sát đường cao tốc',
                    'Đoàn tàu hỏa, tàu thủy chở khách, du thuyền cung cấp Wi-Fi giải trí cho hành khách',
                    'Trụ sở văn phòng dã chiến, ban chỉ huy công trình xây dựng cầu đường dự án trọng điểm',
                ],
                'datasheet_pdf_path' => '/datasheets/bluecom-industrial-router-w6.pdf',
                'warranty_policy' => 'Bảo hành chính hãng 24 tháng theo cam kết phân phối Bluecom tại Việt Nam.',
                'support_commitment' => 'Đổi ngay thiết bị dự phòng tương đương trong 24 giờ làm việc nếu phát sinh lỗi phần cứng phải gửi bảo hành hãng.',
                'related_product_ids' => [5, 6, 7],
                'is_price_visible' => true,
                'price' => 3450000,
                'price_unit' => 'bộ',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Router Công Nghiệp Bluecom Dual-SIM 4G Wi-Fi 6 | Toàn Cầu Tech',
                'meta_description' => 'Router định tuyến công nghiệp Bluecom 4G Dual-SIM tự động chuyển mạng dự phòng, Wi-Fi 6, 5 cổng Gigabit Ethernet chuẩn DIN-Rail bảo hành 24 tháng.',
                'provisioning_driver' => 'manual',
                'provisioning_metadata' => ['device_type' => 'industrial_router', 'ports' => 5],
            ],

            // Product 9: Camera 4G / Wi-Fi Công Trình
            [
                'sector_code' => 'smart_iot',
                'category_id' => $allCatIds['camera-4g-wifi'] ?? $catIds['smart_iot'],
                'name' => 'Camera PTZ 4G / Wi-Fi Công Trình Xây Dựng 360 Độ Zoom Quang 20X',
                'slug' => 'camera-ptz-4g-wifi-cong-trinh-xay-dung-zoom-20x',
                'model_code' => 'TC-CAM-4G-CONST',
                'thumbnail' => '/images/products/solar-cam-thumb.webp',
                'gallery' => [
                    '/images/products/solar-cam-farm.webp',
                ],
                'overview' => 'Dòng Camera PTZ chuyên dụng giám sát đại công trình xây dựng, cầu đường và khai khoáng. Tích hợp khe cắm Nano-SIM 4G LTE tốc độ cao, khả năng zoom quang học 20X nhìn rõ biển số xe và khuôn mặt ở cự ly 150m, vỏ kim loại chuẩn kháng nước va đập IK10/IP67.',
                'features' => [
                    ['icon' => 'EyeIcon', 'title' => 'Zoom Quang Học 20X', 'desc' => 'Ống kính cơ học tự động lấy nét, quan sát chi tiết công trường ở khoảng cách xa'],
                    ['icon' => 'SignalIcon', 'title' => 'Kết Nối SIM 4G Tốc Độ Cao', 'desc' => 'Truyền hình ảnh mượt mà 60fps qua mạng 4G không cần kéo dây cáp quang'],
                    ['icon' => 'ShieldCheckIcon', 'title' => 'Tiêu Chuẩn Chống Va Đập IK10', 'desc' => 'Vỏ nhôm đúc chịu được va đập công trường và bụi bẩn thời tiết khắc nghiệt'],
                    ['icon' => 'BellAlertIcon', 'title' => 'Còi Hú & Đèn Chớp Cảnh Báo', 'desc' => 'Tự động phát loa cảnh báo an toàn lao động khi phát hiện công nhân vào vùng nguy hiểm'],
                ],
                'specs' => [
                    ['label' => 'Độ phân giải', 'value' => '5.0 Megapixel Ultra HD (2880 x 1620)'],
                    ['label' => 'Khả năng Zoom', 'value' => 'Zoom quang 20X + Zoom số 16X'],
                    ['label' => 'Góc xoay', 'value' => 'Xoay ngang 360 độ liên tục, xoay dọc -15 đến 90 độ'],
                    ['label' => 'Tầm xa hồng ngoại ban đêm', 'value' => 'Laser IR 150 mét rõ nét trong bóng tối hoàn toàn'],
                    ['label' => 'Nguồn điện', 'value' => 'DC 12V/3A hoặc PoE+ công nghiệp'],
                ],
                'application_scenarios' => [
                    'Giám sát tiến độ và an toàn thi công các công trình xây dựng nhà xưởng, cao ốc',
                    'Khu vực khai thác khoáng sản, bến cảng, bãi tập kết vật liệu',
                    'Giám sát giao thông các nút giao tuyến đường cao tốc đang thi công',
                ],
                'datasheet_pdf_path' => '/datasheets/toancau-cam-4g-construction.pdf',
                'warranty_policy' => 'Bảo hành 24 tháng chính hãng Toàn Cầu Tech, 1 đổi 1 trong 30 ngày.',
                'support_commitment' => 'Khảo sát và hướng dẫn lắp đặt trực tiếp tại công trường theo yêu cầu.',
                'related_product_ids' => [6, 7],
                'is_price_visible' => true,
                'price' => 4250000,
                'price_unit' => 'bộ',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Camera PTZ 4G Công Trình Xây Dựng Zoom 20X | Toàn Cầu Tech',
                'meta_description' => 'Camera PTZ 4G công trường xây dựng zoom quang 20X hồng ngoại 150m, vỏ kim loại IK10 chống nước IP67 Toàn Cầu Technology.',
                'provisioning_driver' => 'manual',
                'provisioning_metadata' => ['device_family' => 'ptz_camera'],
            ],

            // Product 10: Cảm Biến Công / Nông Nghiệp
            [
                'sector_code' => 'smart_iot',
                'category_id' => $allCatIds['cam-bien-cong-nong-nghiep'] ?? $catIds['smart_iot'],
                'name' => 'Cảm Biến Đa Chỉ Số Môi Trường Đất & Nước 7-in-1 Chuẩn RS485 Modbus',
                'slug' => 'cam-bien-da-chi-so-moi-truong-dat-nuoc-7in1-rs485',
                'model_code' => 'TC-SEN-SOIL-7IN1',
                'thumbnail' => '/images/products/iot-gw-thumb.webp',
                'gallery' => [
                    '/images/products/iot-gw-diagram.webp',
                ],
                'overview' => 'Cảm biến tích hợp 7 chỉ số trong 1 đầu đo chuyên dùng cho nông nghiệp công nghệ cao và quan trắc môi trường công nghiệp. Đo đồng thời: Nhiệt độ, Độ ẩm đất, Độ dẫn điện EC, pH, Nitơ (N), Photpho (P) và Kali (K) với độ sai số cực thấp, truyền dữ liệu qua giao thức công nghiệp Modbus RTU RS485.',
                'features' => [
                    ['icon' => 'SparklesIcon', 'title' => 'Đo 7 Chỉ Số Trong 1 Que Thử', 'desc' => 'Tiết kiệm chi phí đầu tư thiết bị đo kiểm môi trường đất và phân bón'],
                    ['icon' => 'ShieldCheckIcon', 'title' => 'Đầu Kim Thép Không Gỉ 316L', 'desc' => 'Chống ăn mòn axit, kiềm, phân bón hóa học và nước mặn ven biển'],
                    ['icon' => 'CpuChipIcon', 'title' => 'Giao Thức Modbus RTU RS485', 'desc' => 'Dễ dàng tích hợp với PLC, IoT Gateway và máy tính nhúng công nghiệp'],
                    ['icon' => 'CheckBadgeIcon', 'title' => 'Tiêu Chuẩn Đúc Keo Kín IP68', 'desc' => 'Chôn ngầm dưới lòng đất 24/7/365 liên tục trong 5 năm không thấm nước'],
                ],
                'specs' => [
                    ['label' => 'Dải đo nhiệt độ', 'value' => '-40 đến 80°C (Độ chính xác ±0.5°C)'],
                    ['label' => 'Dải đo độ ẩm đất', 'value' => '0 - 100% RH (Độ chính xác ±3%)'],
                    ['label' => 'Dải đo độ dẫn điện EC', 'value' => '0 - 20.000 us/cm'],
                    ['label' => 'Dải đo độ pH đất', 'value' => '3 - 9 pH (Độ chính xác ±0.3 pH)'],
                    ['label' => 'Chỉ số dinh dưỡng NPK', 'value' => '1 - 1999 mg/kg (mg/L)'],
                    ['label' => 'Tín hiệu ngõ ra', 'value' => 'RS485 chuẩn Modbus RTU Master/Slave'],
                ],
                'application_scenarios' => [
                    'Nhà màng nông nghiệp công nghệ cao trồng dưa lưới, hoa lan, dâu tây',
                    'Trang trại sầu riêng, cà phê xuất khẩu canh tác theo tiêu chuẩn VietGAP/GlobalGAP',
                    'Quan trắc chống mặn xâm nhập tại vùng đồng bằng sông Cửu Long',
                ],
                'datasheet_pdf_path' => '/datasheets/toancau-sensor-soil-7in1.pdf',
                'warranty_policy' => 'Bảo hành 24 tháng theo tiêu chuẩn thiết bị công nghiệp.',
                'support_commitment' => 'Cung cấp thư viện code đọc Modbus mẫu và hỗ trợ đấu nối kỹ thuật từ xa.',
                'related_product_ids' => [7, 6],
                'is_price_visible' => true,
                'price' => 1850000,
                'price_unit' => 'bộ',
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Cảm Biến Đất 7-in-1 Modbus RS485 NPK pH EC | Toàn Cầu Tech',
                'meta_description' => 'Cảm biến đất 7 trong 1 đo nhiệt độ độ ẩm EC pH NPK chuẩn RS485 Modbus RTU chống nước IP68 Toàn Cầu Technology.',
                'provisioning_driver' => 'manual',
                'provisioning_metadata' => ['device_family' => 'soil_sensor', 'protocol' => 'modbus_rtu'],
            ],

            // Product 11: Thiết Bị Giám Sát & Điều Khiển Từ Xa
            [
                'sector_code' => 'smart_iot',
                'category_id' => $allCatIds['giam-sat-dieu-khien-tu-xa'] ?? $catIds['smart_iot'],
                'name' => 'Bộ Điều Khiển Đóng Ngắt Bơm & Van Điện Từ Xa 8 Kênh 4G LTE',
                'slug' => 'bo-dieu-khien-dong-ngat-bom-van-tu-xa-8-kenh-4g',
                'model_code' => 'TC-CTRL-8CH-4G',
                'thumbnail' => '/images/products/iot-gw-cabinet.webp',
                'gallery' => [
                    '/images/products/iot-gw-diagram.webp',
                ],
                'overview' => 'Bộ điều khiển công nghiệp 8 kênh ngõ ra Relay tải cao 250V/10A kết hợp 8 ngõ vào số DI. Cho phép nông dân và kỹ sư vận hành đóng ngắt máy bơm tưới, quạt thông gió, van điện từ xa qua ứng dụng di động smartphone hoặc hẹn giờ tự động mà không cần mạng internet cáp quang.',
                'features' => [
                    ['icon' => 'DevicePhoneMobileIcon', 'title' => 'Điều Khiển Qua Điện Thoại & Cuộc Gọi', 'desc' => 'Bật/tắt thiết bị tức thì qua Mobile App, gọi điện thoại hoặc gửi tin nhắn SMS'],
                    ['icon' => 'BoltIcon', 'title' => '8 Relay Tải Cao 10A Độc Lập', 'desc' => 'Đóng ngắt trực tiếp máy bơm công suất lớn, van từ, đèn sưởi trang trại'],
                    ['icon' => 'ClockIcon', 'title' => 'Lập Lịch Tưới Thông Minh Tự Động', 'desc' => 'Hẹn giờ bật tắt theo chu kỳ hàng ngày hoặc theo kịch bản độ ẩm cảm biến'],
                    ['icon' => 'ShieldCheckIcon', 'title' => 'Phản Hồi Trạng Thái Thực Thời Gian Thực', 'desc' => 'Thông báo báo động mất điện nguồn và cảnh báo khi máy bơm gặp sự cố quá tải'],
                ],
                'specs' => [
                    ['label' => 'Ngõ ra điều khiển', 'value' => '8 kênh Relay 250VAC/10A (NO/NC/COM)'],
                    ['label' => 'Ngõ vào giám sát', 'value' => '8 kênh Digital Input (DI cách ly quang)'],
                    ['label' => 'Module mạng', 'value' => 'Quectel 4G LTE-FDD / GSM băng tần kép'],
                    ['label' => 'Phần mềm quản lý', 'value' => 'ToanCau Smart Cloud + Ứng dụng iOS / Android'],
                    ['label' => 'Điện áp hoạt động', 'value' => 'AC 220V hoặc DC 12V/24V'],
                ],
                'application_scenarios' => [
                    'Hệ thống tưới tự động nông trại sầu riêng, cây ăn quả hàng chục hecta',
                    'Điều khiển quạt sục khí hồ tôm, quạt hút làm mát chuồng trại chăn nuôi',
                    'Hệ thống chiếu sáng đô thị, cảnh quan sân vườn và nhà xưởng công nghiệp',
                ],
                'datasheet_pdf_path' => '/datasheets/toancau-relay-controller-4g.pdf',
                'warranty_policy' => 'Bảo hành 24 tháng chính hãng Toàn Cầu Tech, hỗ trợ kỹ thuật trọn đời.',
                'support_commitment' => 'Đội ngũ kỹ thuật hỗ trợ lập trình kịch bản và cài đặt ứng dụng tận tình.',
                'related_product_ids' => [7, 10],
                'is_price_visible' => true,
                'price' => 2450000,
                'price_unit' => 'bộ',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Bộ Điều Khiển Bơm Từ Xa 8 Kênh 4G LTE | Toàn Cầu Tech',
                'meta_description' => 'Bộ điều khiển đóng ngắt từ xa 8 kênh relay 4G LTE qua điện thoại cho máy bơm tưới và van điện Toàn Cầu Technology.',
                'provisioning_driver' => 'manual',
                'provisioning_metadata' => ['device_family' => 'io_controller', 'channels' => 8],
            ],
        ];

        foreach ($products as $p) {
            DB::table('products')->updateOrInsert(
                ['slug' => $p['slug']],
                [
                    'category_id' => $p['category_id'],
                    'sector_code' => $p['sector_code'],
                    'name' => $p['name'],
                    'model_code' => $p['model_code'],
                    'badge' => $p['badge'] ?? 'Sản Phẩm B2B Tiêu Chuẩn',
                    'thumbnail' => $p['thumbnail'],
                    'gallery' => json_encode($p['gallery'], JSON_UNESCAPED_UNICODE),
                    'overview' => $p['overview'],
                    'features' => json_encode($p['features'], JSON_UNESCAPED_UNICODE),
                    'specs' => json_encode($p['specs'], JSON_UNESCAPED_UNICODE),
                    'application_scenarios' => json_encode($p['application_scenarios'], JSON_UNESCAPED_UNICODE),
                    'datasheet_pdf_path' => $p['datasheet_pdf_path'],
                    'warranty_policy' => $p['warranty_policy'],
                    'support_commitment' => $p['support_commitment'],
                    'related_product_ids' => json_encode($p['related_product_ids']),
                    'is_price_visible' => $p['is_price_visible'],
                    'price' => $p['price'],
                    'price_unit' => $p['price_unit'],
                    'is_featured' => $p['is_featured'],
                    'is_active' => $p['is_active'],
                    'meta_title' => $p['meta_title'],
                    'meta_description' => $p['meta_description'],
                    'provisioning_driver' => $p['provisioning_driver'],
                    'provisioning_metadata' => json_encode($p['provisioning_metadata']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------------------------------------------
        // 6. Featured Projects
        // -------------------------------------------------------------
        $projects = [
            [
                'title' => 'Hiện đại hóa Hệ thống Truyền thanh Thông minh 4G cho 45 Xã Huyện',
                'client_name' => 'UBND Tỉnh Đồng Tháp & Đài Truyền thanh Huyện',
                'sector_code' => 'bluecom',
                'image_url' => '/images/projects/project-dongthap-broadcasting.webp',
                'description' => 'Triển khai 350 bộ thu phát Bluecom 4G và cụm loa phóng thanh kỹ thuật số, xóa bỏ hoàn toàn vùng lõm thông tin, tích hợp AI Text-to-Speech phát thanh tự động.',
                'is_featured' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'Cụm Máy Chủ Ảo Hóa Private Cloud & Kênh Leased Line 1Gbps',
                'client_name' => 'Tập đoàn Thủy Hải Sản Nam Việt (Navico)',
                'sector_code' => 'cloud_infra',
                'image_url' => '/images/projects/project-navico-cloud.webp',
                'description' => 'Thiết lập hạ tầng máy chủ vật lý EPYC cụm Proxmox HA, kết nối Leased Line đối xứng tới 6 nhà máy chế biến xuất khẩu, đảm bảo hoạt động liên tục 24/7.',
                'is_featured' => true,
                'display_order' => 2,
            ],
            [
                'title' => 'Hệ thống Solar Camera 4G Giám sát Trang trại Công nghệ cao 150 Hecta',
                'client_name' => 'Công ty Nông Nghiệp Xanh Vinaseed Đắk Lắk',
                'sector_code' => 'smart_iot',
                'image_url' => '/images/projects/project-farm-solar-cam.webp',
                'description' => 'Lắp đặt 42 camera Solar 4G năng lượng mặt trời tại các khu vực không có lưới điện, nhận diện xâm nhập ban đêm bằng AI, bảo vệ mùa màng thu hoạch.',
                'is_featured' => true,
                'display_order' => 3,
            ],
            [
                'title' => 'Triển khai Mạng Dự Phòng Kết Nối Vệ Tinh Starlink cho Trạm Xăng Dầu Ven Biển',
                'client_name' => 'Công ty Cổ phần Vận Tải Xăng Dầu Biển Đông',
                'sector_code' => 'internet',
                'image_url' => '/images/projects/project-starlink-maritime.webp',
                'description' => 'Khảo sát hiện trường và triển khai đường truyền dự phòng vệ tinh Starlink kết hợp Router công nghiệp Multi-WAN, duy trì kết nối POS thanh toán khi bão đứt cáp quang.',
                'is_featured' => true,
                'display_order' => 4,
            ],
        ];

        foreach ($projects as $prj) {
            DB::table('projects')->updateOrInsert(
                ['title' => $prj['title']],
                [
                    'client_name' => $prj['client_name'],
                    'sector_code' => $prj['sector_code'],
                    'image_url' => $prj['image_url'],
                    'description' => $prj['description'],
                    'is_featured' => $prj['is_featured'],
                    'display_order' => $prj['display_order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------------------------------------------
        // 7. News & Technical Insights
        // -------------------------------------------------------------
        $posts = [
            [
                'title' => 'Xu hướng Chuyển Đổi Số Truyền Thanh Cơ Sở: Ứng dụng Công Nghệ 4G và AI Giọng Nói',
                'slug' => 'chuyen-doi-so-truyen-thanh-co-so-4g-ai',
                'summary' => 'Phân tích lợi ích kinh tế và tính ưu việt của giải pháp truyền thanh thông minh Bluecom 4G so với hệ thống loa có dây và phát thanh FM truyền thống.',
                'content' => '<p>Truyền thanh cơ sở là kênh thông tin thiết yếu kết nối chính quyền và người dân tại địa phương. Tuy nhiên, hệ thống loa FM và dây dẫn truyền thống gặp nhiều nhược điểm như dễ suy hao tín hiệu, chi phí kéo dây tốn kém, dễ bị chập cháy do sét đánh và khó khăn trong kiểm soát nội dung phát...</p><p>Hệ sinh thái Toàn Cầu Technology kết hợp giải pháp Bluecom 4G đã mang lại bước chuyển mình đột phá: phát sóng trực tiếp tức thời, tích hợp trí tuệ nhân tạo AI chuyển văn bản thành giọng nói mượt mà, tiết kiệm đến 65% chi phí đầu tư và vận hành cho địa phương.</p>',
                'thumbnail' => '/images/news/news-broadcasting-ai.webp',
                'author_name' => 'Ban Kỹ thuật Toàn Cầu',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Bảo Mật Dải Địa Chỉ IPv4 Doanh Nghiệp: Chiến Lược RPKI và Chống Chiếm Đoạt Định Tuyến BGP Hijacking',
                'slug' => 'bao-mat-ipv4-doanh-nghiep-rpki-bgp-hijacking',
                'summary' => 'Hướng dẫn chi tiết phương thức khai báo ROA/RPKI tại APNIC/VNNIC nhằm ngăn chặn triệt để nguy cơ chiếm đoạt dải IP định tuyến công cộng.',
                'content' => '<p>Trong bối cảnh tài nguyên IPv4 toàn cầu ngày càng khan hiếm và đắt đỏ, các sự cố rò rỉ hoặc chiếm đoạt dải IP (BGP Hijacking) diễn ra ngày càng tinh vi và đe dọa nghiêm trọng đến uy tín của các định chế tài chính, ngân hàng và doanh nghiệp viễn thông...</p><p>Toàn Cầu Technology cam kết quy trình kiểm định dải IP nghiêm ngặt, hỗ trợ khách hàng đăng ký ROA (Route Origin Authorization) chính ngạch, bảo vệ 100% độ sạch của tài nguyên trong suốt thời gian vận hành.</p>',
                'thumbnail' => '/images/news/news-bgp-security.webp',
                'author_name' => 'Nguyễn Minh Tuấn - Senior Network Architect',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
        ];

        foreach ($posts as $pst) {
            DB::table('posts')->updateOrInsert(
                ['slug' => $pst['slug']],
                [
                    'title' => $pst['title'],
                    'summary' => $pst['summary'],
                    'content' => $pst['content'],
                    'thumbnail' => $pst['thumbnail'],
                    'author_name' => $pst['author_name'],
                    'is_published' => $pst['is_published'],
                    'published_at' => $pst['published_at'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------------------------------------------
        // 8. Sample Leads Across the 5 Lifecycle States
        // -------------------------------------------------------------
        $sampleLeads = [
            [
                'form_type' => 'consultation_quote',
                'code' => 'TC-LEAD-202610-001',
                'full_name' => 'Hoàng Văn Dũng',
                'company_name' => 'Công ty TNHH Vận Tải Logistics Biển Vàng',
                'phone' => '0912345678',
                'email' => 'dung.hoang@goldenlogistics.vn',
                'city_province' => 'Hải Phòng',
                'interested_service' => 'Internet Leased Line & Dự Phòng Kết Nối',
                'product_id' => 4,
                'message' => 'Cần tư vấn đường truyền Leased Line 200Mbps cho văn phòng tại Cảng Đình Vũ và giải pháp kết nối VPN đa điểm về chi nhánh Hà Nội.',
                'status' => '1_new',
                'sla_due_at' => Carbon::now()->addMinutes(25),
                'assigned_to' => null,
                'closed_reason' => null,
                'notes' => json_encode([
                    ['time' => now()->toIso8601String(), 'actor' => 'System', 'text' => 'Khách hàng gửi yêu cầu tư vấn báo giá qua Website. SLA phản hồi trong 30 phút.'],
                ]),
            ],
            [
                'form_type' => 'consultation_quote',
                'code' => 'TC-LEAD-202610-002',
                'full_name' => 'Vũ Thị Thanh Hằng',
                'company_name' => 'Hệ thống Phòng Khám Đa Khoa An Bình',
                'phone' => '0988776655',
                'email' => 'hang.vu@anbinhclinic.vn',
                'city_province' => 'TP. Hồ Chí Minh',
                'interested_service' => 'Cloud VPS NVMe',
                'product_id' => 2,
                'message' => 'Cần thuê cụm Cloud VPS chạy phần mềm quản lý bệnh án điện tử PACS và cơ sở dữ liệu SQL Server.',
                'status' => '2_acknowledged',
                'sla_due_at' => Carbon::now()->addMinutes(10),
                'assigned_to' => 3, // Sales user
                'closed_reason' => null,
                'notes' => json_encode([
                    ['time' => now()->subMinutes(15)->toIso8601String(), 'actor' => 'System', 'text' => 'Tiếp nhận Lead.'],
                    ['time' => now()->subMinutes(5)->toIso8601String(), 'actor' => 'Trần Trưởng Ban Kinh Doanh', 'text' => 'Đã gọi điện lần 1, khách hẹn gửi bảng phân tích tài nguyên qua email trong buổi sáng.'],
                ]),
            ],
            [
                'form_type' => 'partner_registration',
                'code' => 'TC-LEAD-202610-003',
                'full_name' => 'Nguyễn Quốc Bảo',
                'company_name' => 'Công ty CP Giải Pháp Công Nghệ Bảo An',
                'phone' => '0903112233',
                'email' => 'bao.nguyen@baoantech.vn',
                'city_province' => 'Cần Thơ',
                'interested_service' => 'Đại lý phân phối Bluecom 4G & Smart IoT',
                'product_id' => 5,
                'message' => 'Chúng tôi là đơn vị chuyên tích hợp giải pháp CNTT cho các cơ quan nhà nước tại Tây Nam Bộ, mong muốn trở thành Đại lý cấp 1 phân phối thiết bị Bluecom và camera Solar.',
                'status' => '3_in_progress',
                'sla_due_at' => Carbon::now()->subMinutes(40),
                'assigned_to' => 4, // Partner manager
                'closed_reason' => null,
                'notes' => json_encode([
                    ['time' => now()->subHours(2)->toIso8601String(), 'actor' => 'System', 'text' => 'Tiếp nhận hồ sơ đăng ký đại lý.'],
                    ['time' => now()->subHours(1)->toIso8601String(), 'actor' => 'Lê Quản Lý Đối Tác & Đại Lý', 'text' => 'Đã thẩm định hồ sơ năng lực (Profile), doanh nghiệp có 8 năm kinh nghiệm đấu thầu công nghệ. Đang hẹn lịch họp trực tuyến ngày mai để thống nhất mức chiết khấu và hợp đồng nguyên tắc.'],
                ]),
            ],
            [
                'form_type' => 'consultation_quote',
                'code' => 'TC-LEAD-202610-004',
                'full_name' => 'Lê Hoàng Nam',
                'company_name' => 'CTCP Dược Phẩm Quốc Tế Minh Tâm',
                'phone' => '0938445566',
                'email' => 'nam.le@minhtampharma.com',
                'city_province' => 'Bình Dương',
                'interested_service' => 'Dedicated Server AMD EPYC',
                'product_id' => 1,
                'message' => 'Yêu cầu báo giá máy chủ EPYC thuê 12 tháng, thanh toán hàng quý kèm dịch vụ backup hàng ngày.',
                'status' => '4_quoted',
                'sla_due_at' => Carbon::now()->subHours(3),
                'assigned_to' => 3, // Sales user
                'closed_reason' => null,
                'notes' => json_encode([
                    ['time' => now()->subHours(4)->toIso8601String(), 'actor' => 'System', 'text' => 'Tiếp nhận Lead.'],
                    ['time' => now()->subHours(3)->toIso8601String(), 'actor' => 'Trần Trưởng Ban Kinh Doanh', 'text' => 'Đã tư vấn chi tiết cấu hình và gửi Báo giá chính thức mã số BG-2026-10-TC04 trị giá 69.600.000 VNĐ/năm. Đang chờ phê duyệt từ Ban Giám đốc khách hàng.'],
                ]),
            ],
            [
                'form_type' => 'technical_service',
                'code' => 'TC-LEAD-202610-005',
                'full_name' => 'Đặng Anh Khoa',
                'company_name' => 'Khu Nghỉ Dưỡng Biển Sunset Marina',
                'phone' => '0979888999',
                'email' => 'khoa.dang@sunsetmarina.vn',
                'city_province' => 'Kiên Giang (Phú Quốc)',
                'interested_service' => 'Khảo sát & Thi công Mạng Wi-Fi / Camera Ngoài Trời',
                'product_id' => null,
                'message' => 'Cần dịch vụ kỹ thuật khảo sát hiện trường lắp đặt 20 camera giám sát bờ biển và hệ thống truyền thanh cảnh báo thời tiết xấu.',
                'status' => '5_closed_won',
                'sla_due_at' => Carbon::now()->subDays(1),
                'assigned_to' => 3,
                'closed_reason' => null,
                'notes' => json_encode([
                    ['time' => now()->subDays(2)->toIso8601String(), 'actor' => 'System', 'text' => 'Tiếp nhận yêu cầu dịch vụ kỹ thuật.'],
                    ['time' => now()->subDays(1)->toIso8601String(), 'actor' => 'Trần Trưởng Ban Kinh Doanh', 'text' => 'Kỹ sư đã hoàn tất khảo sát thực địa tại Phú Quốc. Đã ký hợp đồng triển khai trọn gói số HD-2026-KT19.'],
                ]),
            ],
            [
                'form_type' => 'consultation_quote',
                'code' => 'TC-LEAD-202610-006',
                'full_name' => 'Trần Tuấn Kiệt',
                'company_name' => 'Công ty CP Truyền Thông Alpha',
                'phone' => '0945678901',
                'email' => 'kiet.tran@alphamedia.vn',
                'city_province' => 'Đà Nẵng',
                'interested_service' => 'Thuê IPv4 /24',
                'product_id' => 3,
                'message' => 'Hỏi thuê IP dải /24 để gửi email marketing.',
                'status' => '5_closed_lost',
                'sla_due_at' => Carbon::now()->subDays(3),
                'assigned_to' => 3,
                'closed_reason' => 'Khách hàng có mục đích gửi email spam số lượng lớn hàng loạt không có sự đồng ý của người nhận, vi phạm nghiêm trọng chính sách sử dụng tài nguyên IP sạch của Toàn Cầu Technology.',
                'notes' => json_encode([
                    ['time' => now()->subDays(3)->toIso8601String(), 'actor' => 'System', 'text' => 'Tiếp nhận Lead.'],
                    ['time' => now()->subDays(3)->toIso8601String(), 'actor' => 'Trần Trưởng Ban Kinh Doanh', 'text' => 'Thẩm định mục đích sử dụng IP: Khách hàng không cung cấp được danh sách opt-in hợp lệ và thừa nhận gửi marketing danh bạ mua ngoài. Từ chối cung cấp dịch vụ để bảo vệ uy tín ASN.'],
                ]),
            ],
        ];

        foreach ($sampleLeads as $lead) {
            DB::table('leads')->updateOrInsert(
                ['code' => $lead['code']],
                [
                    'form_type' => $lead['form_type'],
                    'full_name' => $lead['full_name'],
                    'company_name' => $lead['company_name'],
                    'phone' => $lead['phone'],
                    'email' => $lead['email'],
                    'city_province' => $lead['city_province'],
                    'interested_service' => $lead['interested_service'],
                    'product_id' => $lead['product_id'],
                    'message' => $lead['message'],
                    'status' => $lead['status'],
                    'sla_due_at' => $lead['sla_due_at'],
                    'assigned_to' => $lead['assigned_to'],
                    'closed_reason' => $lead['closed_reason'],
                    'notes' => $lead['notes'],
                    'data_consent' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------------------------------------------
        // 9. System Settings (SEO, Analytics, Hotline)
        // -------------------------------------------------------------
        $settings = [
            ['key' => 'site_name', 'value' => 'Toàn Cầu Technology', 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Hạ tầng số – Kết nối thông minh – Giải pháp công nghệ', 'type' => 'string', 'group' => 'general'],
            ['key' => 'hotline', 'value' => '1900 6868 - 028 7300 8888', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'hotline_raw', 'value' => '19006868', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'zalo_oa_url', 'value' => 'https://zalo.me/toancautech', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'email_support', 'value' => 'support@toancau.vn', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'email_lead', 'value' => 'lead@toancau.vn', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'google_analytics_id', 'value' => 'G-TOANCAU2026', 'type' => 'string', 'group' => 'analytics'],
            ['key' => 'google_search_console_token', 'value' => 'tc-gsc-verification-code-2026', 'type' => 'string', 'group' => 'analytics'],
            ['key' => 'show_infra_stats_home', 'value' => '1', 'type' => 'boolean', 'group' => 'home_display'],
            ['key' => 'max_featured_products_home', 'value' => '8', 'type' => 'number', 'group' => 'home_display'],
        ];

        foreach ($settings as $st) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $st['key']],
                [
                    'value' => $st['value'],
                    'type' => $st['type'],
                    'group' => $st['group'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------------------------------------------
        // 10. Dynamic Page Sections (Visual CMS for Zero-Code Management)
        // -------------------------------------------------------------
        $sections = [
            // Home Page
            [
                'page_key' => 'home',
                'section_key' => 'hero',
                'title' => 'KẾT NỐI HẠ TẦNG – KIẾN TẠO GIẢI PHÁP SỐ',
                'subtitle' => 'HẠ TẦNG SỐ – KẾT NỐI THÔNG MINH – GIẢI PHÁP CÔNG NGHỆ',
                'badge' => 'SLA 99.9% UPTIME',
                'content' => 'Cung cấp hệ sinh thái toàn diện từ Máy chủ chuyên dụng, Cloud VPS NVMe, Tài nguyên IP uy tín, Kênh truyền Internet Leased Line SLA 99.9% đến Giải pháp Smart IoT và Thiết bị truyền thanh 4G Bluecom cho Doanh nghiệp, Đơn vị hành chính và Đối tác công nghệ toàn quốc.',
                'metadata' => [
                    'cta_primary' => 'Khám Phá Giải Pháp',
                    'cta_secondary' => 'Liên Hệ Tư Vấn',
                    'cta_partner' => 'Hợp Tác Kinh Doanh',
                ],
                'display_order' => 1,
            ],
            [
                'page_key' => 'home',
                'section_key' => 'core_sectors',
                'title' => 'Năng Lực Công Nghệ Cốt Lõi Của Toàn Cầu',
                'subtitle' => 'Hạ tầng viễn thông và số hóa toàn diện được xây dựng theo tiêu chuẩn công nghiệp khắt khe.',
                'badge' => 'Hệ Sinh Thái 6 Trụ Cột',
                'content' => 'Hệ thống 6 lĩnh vực sản phẩm & dịch vụ chủ lực được quản lý tập trung và tùy biến linh hoạt.',
                'metadata' => [
                    'items' => [
                        [
                            'code' => 'cloud_infra',
                            'name' => 'Cloud & Infrastructure',
                            'title' => 'Hạ Tầng Điện Toán Đám Mây',
                            'desc' => 'Máy chủ vật lý Dedicated Server, Cloud VPS NVMe hiệu năng cao và dịch vụ tài nguyên IP sạch.',
                            'href' => '/cloud-infrastructure',
                            'badge' => 'Tier 3 Data Center',
                        ],
                        [
                            'code' => 'internet',
                            'name' => 'Internet & Connectivity',
                            'title' => 'Internet Doanh Nghiệp & Kết Nối Đa Điểm',
                            'desc' => 'Kênh thuê riêng Leased Line SLA 99.9%, mạng SD-WAN, giải pháp vệ tinh Starlink & SIM IoT.',
                            'href' => '/internet-connectivity',
                            'badge' => 'SLA 99.9%',
                        ],
                        [
                            'code' => 'smart_iot',
                            'name' => 'Smart IoT',
                            'title' => 'Hệ Sinh Thái IoT & Giám Sát Thông Minh',
                            'desc' => 'Solar Camera 4G, camera ven biển IP67, IoT Gateway Modbus/MQTT và cảm biến công/nông nghiệp.',
                            'href' => '/smart-iot',
                            'badge' => 'Solar & 4G/AI',
                        ],
                        [
                            'code' => 'bluecom',
                            'name' => 'Bluecom',
                            'title' => 'Truyền Thanh Thông Minh 4G Bluecom',
                            'desc' => 'Bộ thu phát truyền thanh số 4G, router công nghiệp và nền tảng quản lý tập trung đám mây.',
                            'href' => '/bluecom',
                            'badge' => 'Phân phối chính hãng',
                        ],
                        [
                            'code' => 'tech_services',
                            'name' => 'Technical Services',
                            'title' => 'Dịch Vụ Kỹ Thuật & Khảo Sát Hiện Trường',
                            'desc' => 'Khảo sát hiện trường, thi công hạ tầng mạng, camera, phòng server và bảo trì chẩn đoán khẩn cấp.',
                            'href' => '/technical-services',
                            'badge' => 'Phản hồi 15-30p',
                        ],
                        [
                            'code' => 'partner',
                            'name' => 'Partner & Agency Network',
                            'title' => 'Mạng Lưới Đối Tác & Đại Lý Phân Phối',
                            'desc' => 'Chính sách chiết khấu linh hoạt, bảo vệ quyền lợi dự án, hỗ trợ kỹ thuật 24/7 và đào tạo chuyên sâu.',
                            'href' => '/doi-tac-dai-ly',
                            'badge' => 'Hợp Tác Phát Triển',
                        ],
                    ],
                ],
                'display_order' => 2,
            ],

            // Cloud Infrastructure Page
            [
                'page_key' => 'cloud',
                'section_key' => 'cloud_business',
                'title' => 'Giải Pháp Đám Mây Doanh Nghiệp Tùy Biến (SME, SI & Đại Lý)',
                'subtitle' => 'Hạ tầng máy chủ ảo hóa chuyên dụng cho tập đoàn và các nhà tích hợp',
                'badge' => 'Private & Hybrid Cloud Solutions',
                'content' => 'Xây dựng cụm máy chủ ảo hóa riêng biệt trên Proxmox VE Cluster hoặc OpenStack. Tích hợp mạng riêng ảo Private VLAN, phân bổ tài nguyên vCPU/RAM động và hệ thống lưu trữ phân tán Ceph NVMe với tính sẵn sàng cao (High Availability).',
                'metadata' => [
                    'cards' => [
                        [
                            'title' => 'Private Cloud Riêng Biệt',
                            'desc' => 'Dành cho tập đoàn yêu cầu bảo mật 100% dữ liệu tài chính, ERP và cơ sở dữ liệu nội bộ.',
                        ],
                        [
                            'title' => 'White-label Reseller Cloud',
                            'desc' => 'Dành cho các công ty phần mềm SI muốn tự cấp phát VPS cho khách hàng dưới thương hiệu riêng.',
                        ],
                    ],
                ],
                'display_order' => 1,
            ],
            [
                'page_key' => 'cloud',
                'section_key' => 'ip_services',
                'title' => 'Dịch Vụ Cho Thuê Dải Địa Chỉ IPv4 / IPv6 Doanh Nghiệp',
                'subtitle' => 'Cấp phát chính ngạch APNIC, bảo vệ 100% độ sạch của tài nguyên',
                'badge' => 'Chính Sách Tài Nguyên Mạng IP Sạch',
                'content' => 'Toàn Cầu Technology quản lý kho tài nguyên IP uy tín từ APNIC và VNNIC. Chúng tôi cung cấp thư ủy quyền LOA cho phép công bố định tuyến BGP trên ASN riêng của khách hàng hoặc cấp phát trực tiếp trên hệ thống Data Center.',
                'metadata' => [
                    'warning_note' => 'Vì lý do an ninh mạng và bảo vệ hạ tầng định tuyến quốc gia, Toàn Cầu Technology tuyệt đối không công khai danh sách dải IP/CIDR nội bộ ra ngoài cổng Public. Mọi yêu cầu thuê dải IP đều phải qua quy trình thẩm định mục đích hợp pháp và ký cam kết chống Spam/Phishing.',
                ],
                'display_order' => 2,
            ],

            // Internet Page
            [
                'page_key' => 'internet',
                'section_key' => 'starlink',
                'title' => 'Giải Pháp Kết Nối Vệ Tinh Starlink Cho Doanh Nghiệp',
                'subtitle' => 'Tư vấn kỹ thuật, khảo sát góc mở bầu trời và tích hợp Multi-WAN',
                'badge' => 'Năng Lực Khảo Sát & Ứng Dụng Thực Tế',
                'content' => 'Toàn Cầu Technology cung cấp dịch vụ kỹ thuật chuyên sâu: khảo sát thực địa góc mở bầu trời, tư vấn kiến trúc tích hợp đa đường truyền (Multi-WAN Load Balancing) giữa cáp quang mặt đất và đường truyền vệ tinh Starlink cho các tàu du lịch, giàn khoan ven biển, trạm kiểm lâm và công trình vùng sâu vùng xa.',
                'metadata' => [
                    'compliance_note' => 'Toàn Cầu Technology tập trung vào năng lực tư vấn giải pháp, tích hợp thiết bị mạng doanh nghiệp, đo kiểm hiện trường và triển khai hạ tầng kỹ thuật mặt đất; cam kết tuân thủ đầy đủ quy định quản lý viễn thông tại Việt Nam.',
                ],
                'display_order' => 1,
            ],
            [
                'page_key' => 'internet',
                'section_key' => 'leased_line',
                'title' => 'Internet Leased Line Đối Xứng',
                'subtitle' => 'Băng thông đối xứng 1:1, SLA 99.9%',
                'badge' => 'SLA 99.9%',
                'content' => 'Băng thông Upload = Download đối xứng 1:1, độ trễ thấp dưới 3ms, không chia sẻ lưu lượng và định tuyến đa hướng quốc tế qua 4 tuyến cáp quang biển.',
                'metadata' => [
                    'bullets' => ['Băng thông 100Mbps - 10Gbps', 'Tặng kèm dải /29 IPv4 tĩnh', 'SLA 99.9% khắt khe'],
                ],
                'display_order' => 2,
            ],
            [
                'page_key' => 'internet',
                'section_key' => 'sdwan',
                'title' => 'Mạng Riêng Ảo SD-WAN Đa Điểm',
                'subtitle' => 'Kết nối chi nhánh bảo mật cao',
                'badge' => 'Bảo Mật Enterprise',
                'content' => 'Kết nối an toàn giữa trụ sở chính, các chi nhánh, nhà xưởng và trung tâm dữ liệu với mã hóa IPsec/WireGuard tự động cân bằng tải thông minh.',
                'metadata' => [
                    'bullets' => ['Tự động chọn đường truyền tối ưu', 'Quản trị tập trung qua Cloud Portal', 'Giảm 45% chi phí so với MPLS cũ'],
                ],
                'display_order' => 3,
            ],
            [
                'page_key' => 'internet',
                'section_key' => 'sim_iot',
                'title' => 'SIM IoT & Dự Phòng Multi-WAN',
                'subtitle' => 'Chuyển mạch tự động trong 3 giây',
                'badge' => 'Multi-WAN Failover',
                'content' => 'Hệ thống SIM viễn thông dữ liệu lớn cho máy POS, trạm quan trắc, xe vận tải và cơ chế tự động chuyển mạng dự phòng khi đường cáp quang gặp sự cố.',
                'metadata' => [
                    'bullets' => ['SIM chuyên dụng công nghiệp M2M', 'Chuyển mạch Failover < 3 giây', 'Tương thích mọi Router 4G công nghiệp'],
                ],
                'display_order' => 4,
            ],

            // Smart IoT Page
            [
                'page_key' => 'smart_iot',
                'section_key' => 'solutions_overview',
                'title' => 'Hệ Sinh Thái IoT & Thiết Bị Giám Sát Thông Minh',
                'subtitle' => 'Giải pháp Solar Camera 4G, IoT Gateway và Cảm biến công nghiệp đạt chuẩn IP67',
                'badge' => 'IoT Công Nghiệp & Nông Nghiệp',
                'content' => 'Toàn Cầu Technology cung cấp hệ sinh thái thiết bị IoT hoàn chỉnh từ tầng thu thập dữ liệu (cảm biến nhiệt độ, độ ẩm, đất, nước), tầng truyền dẫn không dây (4G/NB-IoT/LoRaWAN Gateway) đến tầng camera giám sát năng lượng mặt trời không phụ thuộc lưới điện.',
                'metadata' => [
                    'highlights' => [
                        'Solar Camera 4G hoạt động độc lập không cần điện lưới',
                        'IoT Gateway hỗ trợ chuẩn Modbus RTU/TCP, MQTT, RS485',
                        'Cảm biến môi trường, thủy hải sản & nông nghiệp công nghệ cao',
                        'Thiết bị điều khiển relay từ xa qua mạng di động và đám mây',
                    ],
                ],
                'display_order' => 1,
            ],

            // Bluecom Page
            [
                'page_key' => 'bluecom',
                'section_key' => 'topology',
                'title' => 'Mô Hình Vận Hành Truyền Thanh Thông Minh Bluecom 4G Đám Mây',
                'subtitle' => 'Hiện đại hóa truyền thanh cơ sở qua sóng di động và trí tuệ nhân tạo',
                'badge' => 'Sơ Đồ Hệ Thống Hoạt Động',
                'content' => 'Dữ liệu âm thanh và kịch bản phát thanh được mã hóa an toàn truyền từ Trung tâm điều hành tới từng cụm loa xã/phường qua mạng di động 4G.',
                'metadata' => [
                    'steps' => [
                        [
                            'step' => '1',
                            'title' => 'Ban Lãnh Đạo / Đài TT',
                            'desc' => 'Nhập văn bản bản tin hoặc thu âm trực tiếp trên Điện thoại/Máy tính.',
                        ],
                        [
                            'step' => '2',
                            'title' => 'Bluecom Cloud CMS & AI',
                            'desc' => 'AI chuyển văn bản thành giọng đọc truyền cảm, lập lịch phát thanh tự động.',
                        ],
                        [
                            'step' => '3',
                            'title' => 'Hạ Tầng Sóng 4G LTE',
                            'desc' => 'Truyền dẫn không dây qua mạng Viettel, VinaPhone, MobiFone đa điểm.',
                        ],
                        [
                            'step' => '4',
                            'title' => 'Cụm Thu Bluecom & Loa',
                            'desc' => 'Giải mã âm thanh Hi-Fi số trong trẻo, chống mưa bão IP66 ngoài trời.',
                        ],
                    ],
                ],
                'display_order' => 1,
            ],

            // Technical Services Page
            [
                'page_key' => 'tech_services',
                'section_key' => 'installation',
                'title' => 'Dịch Vụ Thi Công & Lắp Đặt Trọn Gói',
                'subtitle' => 'Tiêu chuẩn kỹ thuật công nghiệp',
                'badge' => 'Thi Công Trọn Gói',
                'content' => 'Thiết kế bản vẽ, đo kiểm suy hao quang học và thi công lắp đặt theo tiêu chuẩn công nghiệp.',
                'metadata' => [
                    'items' => [
                        'Hạ tầng mạng LAN / Wi-Fi công nghiệp tòa nhà & KCN',
                        'Hệ thống Solar Camera ngoài trời không dây',
                        'Hệ thống truyền thanh thông minh Bluecom 4G cơ sở',
                        'Phòng máy chủ Data Center, tủ Rack & hệ thống PDU',
                    ],
                ],
                'display_order' => 1,
            ],
            [
                'page_key' => 'tech_services',
                'section_key' => 'maintenance',
                'title' => 'Bảo Trì, Chẩn Đoán & Sửa Chữa Khẩn Cấp',
                'subtitle' => 'Ứng cứu sự cố hiện trường 24/7/365',
                'badge' => 'Phản Hồi Khẩn Cấp',
                'content' => 'Đội cơ động kỹ thuật có mặt tại hiện trường xử lý các tình huống đứt cáp quang, chập cháy, sét đánh hoặc lỗi thiết bị viễn thông.',
                'metadata' => [
                    'items' => [
                        'Đo kiểm chẩn đoán điểm đứt cáp quang OTDR chính xác',
                        'Thay thế thiết bị dự phòng Router/Switch trong 2 giờ',
                        'Bảo trì định kỳ chống sét lan truyền và hệ thống nguồn',
                        'Hỗ trợ kỹ thuật 24/7/365 qua Hotline riêng biệt',
                    ],
                ],
                'display_order' => 2,
            ],

            // About Page
            [
                'page_key' => 'about',
                'section_key' => 'company_intro',
                'title' => 'Về Toàn Cầu Technology',
                'subtitle' => 'Hạ tầng số – Kết nối thông minh – Giải pháp công nghệ',
                'badge' => 'Hồ Sơ Năng Lực Doanh Nghiệp',
                'content' => 'Công ty Cổ phần Công nghệ Toàn Cầu là đơn vị tiên phong cung cấp hệ sinh thái hạ tầng viễn thông, điện toán đám mây và thiết bị số cho các doanh nghiệp và tổ chức tại Việt Nam.',
                'metadata' => [
                    'tagline' => 'Hạ tầng số – Kết nối thông minh – Giải pháp công nghệ',
                ],
                'display_order' => 1,
            ],
            [
                'page_key' => 'about',
                'section_key' => 'strengths',
                'title' => 'Thế Mạnh Năng Lực Vượt Trội',
                'subtitle' => '3 Trụ cột cốt lõi tạo nên sự khác biệt của Toàn Cầu',
                'badge' => '3 Trụ Cột Năng Lực',
                'content' => 'Hạ tầng độc quyền, đội ngũ kỹ sư trực tiếp không qua trung gian và cam kết chất lượng dịch vụ SLA bằng hợp đồng pháp lý.',
                'metadata' => [
                    'pillars' => [
                        [
                            'title' => 'Hạ Tầng Độc Quyền',
                            'desc' => 'Sở hữu kho tài nguyên địa chỉ IPv4 sạch trên APNIC, cụm máy chủ vật lý AMD EPYC và kết nối trực tiếp các Data Center Tier 3 lớn nhất nước.',
                        ],
                        [
                            'title' => 'Kỹ Sư Trực Tiếp 24/7',
                            'desc' => 'Không qua tổng đài trung gian ảo. Mọi yêu cầu hỗ trợ kỹ thuật đều được xử lý trực tiếp bởi đội ngũ kỹ sư mạng có chứng chỉ quốc tế.',
                        ],
                        [
                            'title' => 'Cam Kết SLA Khắt Khe',
                            'desc' => 'Mức độ sẵn sàng mạng 99.9%, hỗ trợ tiếp nhận Lead và ứng cứu sự cố trong vòng 15-30 phút được ràng buộc rõ ràng trong hợp đồng pháp lý.',
                        ],
                    ],
                ],
                'display_order' => 2,
            ],
        ];

        foreach ($sections as $sec) {
            DB::table('page_sections')->updateOrInsert(
                [
                    'page_key' => $sec['page_key'],
                    'section_key' => $sec['section_key'],
                ],
                [
                    'title' => $sec['title'],
                    'subtitle' => $sec['subtitle'],
                    'badge' => $sec['badge'],
                    'content' => $sec['content'],
                    'metadata' => json_encode($sec['metadata'], JSON_UNESCAPED_UNICODE),
                    'display_order' => $sec['display_order'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
