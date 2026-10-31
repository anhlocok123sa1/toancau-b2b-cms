<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  vpsPlans: {
    type: Array,
    default: () => [],
  },
  dedicatedServers: {
    type: Array,
    default: () => [],
  },
  ipServices: {
    type: Array,
    default: () => [],
  },
  isIpVerified: {
    type: Boolean,
    default: false,
  },
  activeTab: {
    type: String,
    default: 'vps',
  },
  cloudSections: {
    type: Object,
    default: () => ({}),
  },
});

const currentTab = ref(props.activeTab);
const layoutRef = ref(null);

const triggerLead = (type = 'consultation_quote', product = null, service = '') => {
  if (layoutRef.value) {
    layoutRef.value.openLeadModal(type, product, service);
  }
};

const openComparison = () => {
  if (layoutRef.value) {
    layoutRef.value.openVpsModal();
  }
};

const formatVnd = (val) => {
  if (!val) return 'Liên hệ';
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
};
</script>

<template>
  <PublicLayout ref="layoutRef">
    <Head>
      <title>{{ currentTab === 'business' ? 'Cloud Business - Giải Pháp Đám Mây Doanh Nghiệp & White-label | Toàn Cầu Tech' : 'Hạ Tầng Điện Toán Đám Mây & Máy Chủ - Toàn Cầu Tech' }}</title>
      <meta name="description" content="Dedicated Server AMD EPYC 7763, Cloud VPS NVMe Ceph Storage SLA 99.95%, Cho thuê dải IPv4 sạch APNIC /24 tại Data Center Tier 3 Toàn Cầu Technology." />
    </Head>

    <!-- Header Section - High-Tech Modern IT Banner -->
    <div class="relative bg-[#061224] text-white py-14 border-b border-slate-800 overflow-hidden">
      <!-- High-Tech Cyber Grid & Glow -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_60%,transparent_100%)] pointer-events-none"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full">
            Tier 3 Data Center Infrastructure
          </span>
          <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
            Cloud & Hạ Tầng Điện Toán Đám Mây
          </h1>
          <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
            Nền tảng máy chủ vật lý, cụm ảo hóa Cloud VPS NVMe và tài nguyên IP sạch uy tín quốc tế, đáp ứng các tiêu chuẩn bảo mật cho Doanh nghiệp, Ngân hàng và Nhà tích hợp SI.
          </p>
        </div>

        <div class="flex items-center gap-3 justify-center sm:justify-start">
          <button 
            type="button" 
            @click="openComparison"
            class="px-5 py-3 rounded-xl bg-white text-[#0F294A] hover:bg-slate-100 text-xs font-bold transition shadow-lg flex items-center gap-2"
          >
            <svg class="w-4 h-4 text-[#0284C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>So Sánh Gói VPS</span>
          </button>
          <button 
            type="button" 
            @click="triggerLead('consultation_quote', null, 'Tư vấn Hạ tầng Cloud & Dedicated')"
            class="px-5 py-3 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold transition shadow-md shadow-[#EA580C]/25"
          >
            Báo Giá Giải Pháp
          </button>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white border-b border-slate-200 sticky top-20 z-30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-8 overflow-x-auto">
          <button 
            type="button" 
            @click="currentTab = 'vps'"
            :class="currentTab === 'vps' ? 'border-[#0284C7] text-[#0284C7] font-bold' : 'border-transparent text-slate-500 hover:text-slate-800'"
            class="py-4 px-1 border-b-2 text-sm whitespace-nowrap transition"
          >
            Cloud VPS NVMe Doanh Nghiệp
          </button>
          <button 
            type="button" 
            @click="currentTab = 'dedicated'"
            :class="currentTab === 'dedicated' ? 'border-[#0284C7] text-[#0284C7] font-bold' : 'border-transparent text-slate-500 hover:text-slate-800'"
            class="py-4 px-1 border-b-2 text-sm whitespace-nowrap transition"
          >
            Dedicated Server (Máy Chủ Vật Lý)
          </button>
          <button 
            type="button" 
            @click="currentTab = 'ip'"
            :class="currentTab === 'ip' ? 'border-[#0284C7] text-[#0284C7] font-bold' : 'border-transparent text-slate-500 hover:text-slate-800'"
            class="py-4 px-1 border-b-2 text-sm whitespace-nowrap transition"
          >
            Cho Thuê Dải IPv4 / IPv6 Sạch
          </button>
          <button 
            type="button" 
            @click="currentTab = 'business'"
            :class="currentTab === 'business' ? 'border-[#0284C7] text-[#0284C7] font-bold' : 'border-transparent text-slate-500 hover:text-slate-800'"
            class="py-4 px-1 border-b-2 text-sm whitespace-nowrap transition"
          >
            Private Cloud Cho SME & SI
          </button>
        </div>
      </div>
    </div>

    <!-- Tab 1: Cloud VPS NVMe -->
    <section v-if="currentTab === 'vps'" class="py-12 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
          <div>
            <h2 class="text-2xl font-extrabold text-[#0F294A]">Bảng Giá Cấu Hình Cloud VPS NVMe</h2>
            <p class="text-xs text-slate-600 mt-1">Khởi tạo tức thì trong 15 giây, Anti-DDoS miễn phí, Snapshot đám mây tự động.</p>
          </div>
          <button 
            type="button" 
            @click="openComparison" 
            class="text-xs font-bold text-[#0284C7] hover:underline flex items-center gap-1.5"
          >
            <span>Mở Bảng So Sánh Chi Tiết Tất Cả Các Gói</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
          </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div 
            v-for="plan in vpsPlans" 
            :key="plan.id"
            :class="plan.is_popular ? 'border-2 border-[#0284C7] shadow-xl relative bg-white' : 'border border-slate-200 bg-white shadow-sm'"
            class="rounded-3xl p-6 flex flex-col justify-between hover:shadow-md transition"
          >
            <div v-if="plan.is_popular" class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#0284C7] text-white text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider shadow">
              Doanh Nghiệp Đề Xuất
            </div>

            <div>
              <div class="text-xs font-bold uppercase text-slate-500 tracking-wider font-mono">{{ plan.slug }}</div>
              <h3 class="text-xl font-black text-slate-900 mt-1">{{ plan.name }}</h3>

              <div class="mt-4 pb-4 border-b border-slate-100">
                <div class="text-3xl font-black text-[#0F294A]">
                  {{ formatVnd(plan.price_monthly) }}
                  <span class="text-xs font-normal text-slate-500">/ tháng</span>
                </div>
                <div class="text-xs text-emerald-600 font-semibold mt-1">
                  Trả năm: {{ formatVnd(plan.price_yearly) }} (-15%)
                </div>
              </div>

              <ul class="mt-4 space-y-2.5 text-xs text-slate-700">
                <li class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-[#0284C7]"></span>
                  <span><strong>{{ plan.vcpu }} Cores</strong> AMD EPYC High Frequency</span>
                </li>
                <li class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-[#0284C7]"></span>
                  <span><strong>{{ plan.ram_gb }} GB</strong> RAM ECC DDR4</span>
                </li>
                <li class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-[#0284C7]"></span>
                  <span><strong>{{ plan.storage_nvme_gb }} GB</strong> Pure NVMe Enterprise</span>
                </li>
                <li class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-[#0284C7]"></span>
                  <span><strong>{{ plan.ipv4_count }} IPv4</strong> Sạch tĩnh riêng</span>
                </li>
                <li class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-[#0284C7]"></span>
                  <span>{{ plan.port_speed }}</span>
                </li>
                <li class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-[#0284C7]"></span>
                  <span>SLA 99.95% & Backup Snapshot</span>
                </li>
              </ul>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
              <button 
                type="button" 
                @click="triggerLead('consultation_quote', null, `Thuê gói ${plan.name}`)"
                :class="plan.is_popular ? 'bg-[#EA580C] hover:bg-[#C2410C] text-white shadow-md' : 'bg-[#0F294A] hover:bg-[#183F70] text-white'"
                class="w-full py-3 rounded-xl text-xs font-bold text-center transition flex items-center justify-center gap-2"
              >
                <span>Yêu Cầu Báo Giá</span>
              </button>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- Tab 2: Dedicated Server -->
    <section v-else-if="currentTab === 'dedicated'" class="py-12 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
          <h2 class="text-2xl font-extrabold text-[#0F294A]">Máy Chủ Vật Lý Riêng Biệt (Dedicated Server)</h2>
          <p class="text-xs text-slate-600 mt-1">Toàn quyền kiểm soát tài nguyên phần cứng vật lý, đặt tại Data Center Tier 3 VNPT / Viettel.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="server in dedicatedServers" 
            :key="server.id"
            class="bg-white rounded-3xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition"
          >
            <div>
              <div class="flex items-center justify-between">
                <span class="text-xs font-mono font-bold text-[#0284C7] bg-cyan-50 px-2.5 py-1 rounded-md">
                  {{ server.model_code }}
                </span>
                <span class="text-xs text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded">Tier 3 DC</span>
              </div>

              <h3 class="text-lg font-extrabold text-slate-900 mt-3">{{ server.name }}</h3>
              <p class="text-xs text-slate-600 mt-2 leading-relaxed">{{ server.overview }}</p>

              <!-- Specs snippets -->
              <div class="mt-4 pt-3 border-t border-slate-100 space-y-2 text-xs">
                <div v-for="(spec, sidx) in server.specs.slice(0, 5)" :key="sidx" class="flex items-start justify-between gap-2">
                  <span class="text-slate-500 font-semibold">{{ spec.label }}:</span>
                  <span class="text-slate-900 font-bold text-right">{{ spec.value }}</span>
                </div>
              </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
              <div>
                <div class="text-[10px] text-slate-400">Giá thuê tháng:</div>
                <div class="text-lg font-black text-[#0F294A]">
                  {{ formatVnd(server.price) }}
                </div>
              </div>

              <div class="flex items-center gap-2">
                <Link :href="'/san-pham/' + server.slug" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-800 text-xs font-bold hover:bg-slate-200 transition">
                  11 Thông Số
                </Link>
                <button 
                  type="button" 
                  @click="triggerLead('consultation_quote', server)"
                  class="px-4 py-2 rounded-xl bg-[#EA580C] text-white text-xs font-bold hover:bg-[#C2410C] transition shadow-sm"
                >
                  Báo Giá
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Tab 3: IP Services -->
    <section v-else-if="currentTab === 'ip'" class="py-12 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
          <div class="max-w-3xl">
            <span class="text-xs font-bold uppercase tracking-wider text-[#0284C7] bg-cyan-100/60 px-3 py-1 rounded-full">
              {{ cloudSections?.ip_services?.badge || 'Chính Sách Tài Nguyên Mạng IP Sạch' }}
            </span>
            <h2 class="text-2xl font-extrabold text-[#0F294A] mt-3">
              {{ cloudSections?.ip_services?.title || 'Dịch Vụ Cho Thuê Dải Địa Chỉ IPv4 / IPv6 Doanh Nghiệp' }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
              {{ cloudSections?.ip_services?.content || 'Toàn Cầu Technology quản lý kho tài nguyên IP uy tín từ APNIC và VNNIC. Chúng tôi cung cấp thư ủy quyền LOA cho phép công bố định tuyến BGP trên ASN riêng của khách hàng hoặc cấp phát trực tiếp trên hệ thống Data Center.' }}
            </p>

            <!-- Verification Compliance Alert -->
            <div class="mt-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-start gap-3">
              <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              <div>
                <strong class="font-bold">Quy tắc bảo mật tài nguyên:</strong>
                <p class="mt-0.5 leading-relaxed">
                  {{ cloudSections?.ip_services?.metadata?.warning_note || 'Vì lý do an ninh mạng và bảo vệ hạ tầng định tuyến quốc gia, Toàn Cầu Technology tuyệt đối không công khai danh sách dải IP/CIDR nội bộ ra ngoài cổng Public. Mọi yêu cầu thuê dải IP đều phải qua quy trình thẩm định mục đích hợp pháp và ký cam kết chống Spam/Phishing.' }}
                </p>
              </div>
            </div>

            <!-- Capacity Highlight -->
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <div class="text-2xl font-black text-[#0284C7]">{{ isIpVerified ? '4.000+ IP' : 'Tài Nguyên Lớn' }}</div>
                <div class="text-xs font-bold text-slate-800 mt-1">Dải IPv4 Sạch Khả Dụng</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Không dính Blacklist Spamhaus, Barracuda</div>
              </div>
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <div class="text-2xl font-black text-emerald-600">BGP & LOA</div>
                <div class="text-xs font-bold text-slate-800 mt-1">Hỗ Trợ Định Tuyến Toàn Cầu</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Hỗ trợ khai báo ROA/RPKI tại APNIC</div>
              </div>
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <div class="text-2xl font-black text-orange-600">rDNS / PTR</div>
                <div class="text-xs font-bold text-slate-800 mt-1">Ủy Quyền Phân Giải Ngược</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Thiết lập linh hoạt cho Mail Server</div>
              </div>
            </div>

            <div class="mt-8">
              <button 
                type="button" 
                @click="triggerLead('consultation_quote', null, 'Yêu cầu thẩm định thuê dải IPv4 sạch (/24 hoặc /23)')"
                class="px-6 py-3.5 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold shadow-lg transition"
              >
                Gửi Yêu Cầu Thuê Dải IP (Thẩm Định Trong 30 Phút)
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Tab 4: Cloud Business -->
    <section v-else-if="currentTab === 'business'" class="py-12 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
          <div class="max-w-3xl space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-[#0284C7] bg-cyan-100/60 px-3 py-1 rounded-full">
              {{ cloudSections?.cloud_business?.badge || 'Private & Hybrid Cloud Solutions' }}
            </span>
            <h2 class="text-2xl font-extrabold text-[#0F294A]">
              {{ cloudSections?.cloud_business?.title || 'Giải Pháp Đám Mây Doanh Nghiệp Tùy Biến (SME, SI & Đại Lý)' }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
              {{ cloudSections?.cloud_business?.content || 'Xây dựng cụm máy chủ ảo hóa riêng biệt trên Proxmox VE Cluster hoặc OpenStack. Tích hợp mạng riêng ảo Private VLAN, phân bổ tài nguyên vCPU/RAM động và hệ thống lưu trữ phân tán Ceph NVMe với tính sẵn sàng cao (High Availability).' }}
            </p>

            <div v-if="cloudSections?.cloud_business?.metadata?.cards?.length" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
              <div 
                v-for="(card, cIdx) in cloudSections.cloud_business.metadata.cards" 
                :key="cIdx" 
                class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-1"
              >
                <div class="font-bold text-sm text-slate-900">{{ card.title }}</div>
                <p class="text-xs text-slate-600">{{ card.desc }}</p>
              </div>
            </div>
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
              <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-1">
                <div class="font-bold text-sm text-slate-900">Private Cloud Riêng Biệt</div>
                <p class="text-xs text-slate-600">Dành cho tập đoàn yêu cầu bảo mật 100% dữ liệu tài chính, ERP và cơ sở dữ liệu nội bộ.</p>
              </div>
              <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-1">
                <div class="font-bold text-sm text-slate-900">White-label Reseller Cloud</div>
                <p class="text-xs text-slate-600">Dành cho các công ty phần mềm SI muốn tự cấp phát VPS cho khách hàng dưới thương hiệu riêng.</p>
              </div>
            </div>

            <div class="pt-4">
              <button 
                type="button" 
                @click="triggerLead('consultation_quote', null, 'Tư vấn kiến trúc Private Cloud cho doanh nghiệp')"
                class="px-6 py-3.5 rounded-xl bg-[#0F294A] hover:bg-[#183F70] text-white text-xs font-bold shadow-md transition"
              >
                Gặp Chuyên Gia Kiến Trúc Đám Mây (SLA 30p)
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

  </PublicLayout>
</template>
