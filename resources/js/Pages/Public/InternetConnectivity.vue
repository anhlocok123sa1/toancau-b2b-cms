<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  products: {
    type: Array,
    default: () => [],
  },
  isStarlinkVerified: {
    type: Boolean,
    default: false,
  },
  internetSections: {
    type: Object,
    default: () => ({}),
  },
});

const layoutRef = ref(null);

const triggerLead = (type = 'consultation_quote', product = null, service = '') => {
  if (layoutRef.value) {
    layoutRef.value.openLeadModal(type, product, service);
  }
};
</script>

<template>
  <PublicLayout ref="layoutRef">
    <Head>
      <title>Internet & Kết Nối Doanh Nghiệp - Toàn Cầu Tech</title>
      <meta name="description" content="Kênh thuê riêng Internet Leased Line đối xứng SLA 99.9%, kết nối đa điểm SD-WAN, giải pháp vệ tinh Starlink và SIM IoT Multi-WAN tại Toàn Cầu Technology." />
    </Head>

    <!-- Header Section - High-Tech Modern IT Banner -->
    <div class="relative bg-[#061224] text-white py-14 border-b border-slate-800 overflow-hidden">
      <!-- High-Tech Cyber Grid & Glow -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_60%,transparent_100%)] pointer-events-none"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full">
          SLA 99.9% Cam Kết Bằng Hợp Đồng
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
          Internet Doanh Nghiệp & Kết Nối Đa Điểm
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
          Đường truyền cáp quang đối xứng riêng biệt, giải pháp kết nối mạng riêng ảo SD-WAN liên chi nhánh và mạng dự phòng viễn thông vệ tinh Starlink cho công trình ngoài khơi, hải đảo và vùng sâu.
        </p>
      </div>
    </div>

    <div class="py-12 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Leased Line & SD-WAN Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">{{ internetSections?.leased_line?.title || 'Internet Leased Line Đối Xứng' }}</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              {{ internetSections?.leased_line?.content || 'Băng thông Upload = Download đối xứng 1:1, độ trễ thấp dưới 3ms, không chia sẻ lưu lượng và định tuyến đa hướng quốc tế qua 4 tuyến cáp quang biển.' }}
            </p>
            <ul class="text-xs text-slate-700 space-y-1.5 pt-2 border-t border-slate-100">
              <template v-if="internetSections?.leased_line?.metadata?.bullets?.length">
                <li v-for="(b, bIdx) in internetSections.leased_line.metadata.bullets" :key="bIdx">• {{ b }}</li>
              </template>
              <template v-else>
                <li>• Băng thông 100Mbps - 10Gbps</li>
                <li>• Tặng kèm dải /29 IPv4 tĩnh</li>
                <li>• SLA 99.9% khắt khe</li>
              </template>
            </ul>
            <button 
              type="button" 
              @click="triggerLead('consultation_quote', null, 'Tư vấn kênh thuê riêng Leased Line')"
              class="w-full py-2.5 bg-[#EA580C] hover:bg-[#C2410C] text-white rounded-xl text-xs font-bold transition shadow"
            >
              Yêu Cầu Báo Giá Kênh Thuê Riêng
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
              </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">{{ internetSections?.sdwan?.title || 'Mạng Riêng Ảo SD-WAN Đa Điểm' }}</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              {{ internetSections?.sdwan?.content || 'Kết nối an toàn giữa trụ sở chính, các chi nhánh, nhà xưởng và trung tâm dữ liệu với mã hóa IPsec/WireGuard tự động cân bằng tải thông minh.' }}
            </p>
            <ul class="text-xs text-slate-700 space-y-1.5 pt-2 border-t border-slate-100">
              <template v-if="internetSections?.sdwan?.metadata?.bullets?.length">
                <li v-for="(b, bIdx) in internetSections.sdwan.metadata.bullets" :key="bIdx">• {{ b }}</li>
              </template>
              <template v-else>
                <li>• Tự động chọn đường truyền tối ưu</li>
                <li>• Quản trị tập trung qua Cloud Portal</li>
                <li>• Giảm 45% chi phí so với MPLS cũ</li>
              </template>
            </ul>
            <button 
              type="button" 
              @click="triggerLead('consultation_quote', null, 'Tư vấn giải pháp SD-WAN đa điểm')"
              class="w-full py-2.5 bg-[#0F294A] hover:bg-[#183F70] text-white rounded-xl text-xs font-bold transition shadow"
            >
              Tư Vấn Thiết Kế Mạng SD-WAN
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
              </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">{{ internetSections?.sim_iot?.title || 'SIM IoT & Dự Phòng Multi-WAN' }}</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              {{ internetSections?.sim_iot?.content || 'Hệ thống SIM viễn thông dữ liệu lớn cho máy POS, trạm quan trắc, xe vận tải và cơ chế tự động chuyển mạng dự phòng khi đường cáp quang gặp sự cố.' }}
            </p>
            <ul class="text-xs text-slate-700 space-y-1.5 pt-2 border-t border-slate-100">
              <template v-if="internetSections?.sim_iot?.metadata?.bullets?.length">
                <li v-for="(b, bIdx) in internetSections.sim_iot.metadata.bullets" :key="bIdx">• {{ b }}</li>
              </template>
              <template v-else>
                <li>• SIM chuyên dụng công nghiệp M2M</li>
                <li>• Chuyển mạch Failover &lt; 3 giây</li>
                <li>• Tương thích mọi Router 4G công nghiệp</li>
              </template>
            </ul>
            <button 
              type="button" 
              @click="triggerLead('consultation_quote', null, 'Tư vấn SIM IoT & Multi-WAN Failover')"
              class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition shadow"
            >
              Báo Giá Gói SIM Dự Phòng
            </button>
          </div>
        </div>

        <!-- Compliance Controlled Section: Starlink Satellite Solutions -->
        <div class="bg-gradient-to-r from-slate-900 to-[#0F294A] text-white rounded-3xl p-8 sm:p-10 border border-slate-700">
          <div class="max-w-3xl space-y-4">
            <div 
              class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all"
              :class="isStarlinkVerified ? 'bg-amber-400/20 text-amber-300 border border-amber-400/40 shadow-[0_0_15px_rgba(251,191,36,0.25)]' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-400/20'"
            >
              <span v-if="isStarlinkVerified" class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                <span>✓ ĐẠI LÝ ỦY QUYỀN CHÍNH THỨC STARLINK TẠI VIỆT NAM (ĐÃ DUYỆT CMS)</span>
              </span>
              <span v-else>
                ĐƠN VỊ TƯ VẤN & KHẢO SÁT HIỆN TRƯỜNG STARLINK (CHƯA BẬT CỜ ỦY QUYỀN)
              </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
              {{ internetSections?.starlink?.title || 'Giải Pháp Kết Nối Vệ Tinh Starlink Cho Doanh Nghiệp' }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
              {{ internetSections?.starlink?.content || 'Toàn Cầu Technology cung cấp dịch vụ kỹ thuật chuyên sâu: khảo sát thực địa góc mở bầu trời, tư vấn kiến trúc tích hợp đa đường truyền (Multi-WAN Load Balancing) giữa cáp quang mặt đất và đường truyền vệ tinh Starlink cho các tàu du lịch, giàn khoan ven biển, trạm kiểm lâm và công trình vùng sâu vùng xa.' }}
            </p>

            <!-- Legal Compliance notice per Checklist Rule 3 -->
            <div class="p-4 rounded-xl bg-white/5 border border-white/10 text-xs text-slate-300">
              <span class="font-bold text-amber-300">Lưu ý tuân thủ pháp lý:</span> {{ internetSections?.starlink?.metadata?.compliance_note || 'Toàn Cầu Technology tập trung vào năng lực tư vấn giải pháp, tích hợp thiết bị mạng doanh nghiệp, đo kiểm hiện trường và triển khai hạ tầng kỹ thuật mặt đất; cam kết tuân thủ đầy đủ quy định quản lý viễn thông tại Việt Nam.' }}
            </div>

            <div class="pt-2 flex flex-wrap gap-4">
              <button 
                type="button" 
                @click="triggerLead('technical_service', null, 'Đăng ký khảo sát hiện trường triển khai vệ tinh Starlink')"
                class="px-6 py-3 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold shadow-lg transition"
              >
                Đăng Ký Khảo Sát Hiện Trường Starlink
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </PublicLayout>
</template>
