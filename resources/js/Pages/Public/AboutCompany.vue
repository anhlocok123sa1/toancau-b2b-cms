<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  isLegalVerified: {
    type: Boolean,
    default: false,
  },
  legalProfile: {
    type: Object,
    default: () => ({}),
  },
  aboutSections: {
    type: Object,
    default: () => ({}),
  },
});
</script>

<template>
  <PublicLayout>
    <Head>
      <title>Về Toàn Cầu Technology - Hạ Tầng Số & Giải Pháp B2B</title>
      <meta name="description" content="Giới thiệu Công ty Cổ phần Công nghệ Toàn Cầu, hồ sơ năng lực hạ tầng điện toán đám mây, mạng Leased Line và thiết bị số tại Việt Nam." />
    </Head>

    <!-- Header Section - High-Tech Modern IT Banner -->
    <div class="relative bg-[#061224] text-white py-14 border-b border-slate-800 overflow-hidden">
      <!-- High-Tech Cyber Grid & Glow -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_60%,transparent_100%)] pointer-events-none"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full">
          {{ aboutSections?.company_intro?.badge || 'Hồ Sơ Năng Lực Doanh Nghiệp' }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
          {{ aboutSections?.company_intro?.title || 'Về Toàn Cầu Technology' }}
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
          {{ aboutSections?.company_intro?.content || '"Hạ tầng số – Kết nối thông minh – Giải pháp công nghệ". Đơn vị tiên phong cung cấp hệ sinh thái hạ tầng viễn thông và thiết bị số cho các doanh nghiệp và tổ chức tại Việt Nam.' }}
        </p>
      </div>
    </div>

    <div class="py-12 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Corporate Info Box -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <h2 class="text-2xl font-bold text-[#0F294A]">Thông Tin Pháp Nhân Doanh Nghiệp</h2>
            <span v-if="isLegalVerified" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
              <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              <span>✓ ĐÃ XÁC THỰC PHÁP NHÂN (SỞ KH&ĐT TP.HCM)</span>
            </span>
            <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
              <span>Đang Trong Tiến Trình Thẩm Định Hồ Sơ Pháp Nhân</span>
            </span>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-slate-700">
            <div>
              <div class="font-bold text-slate-900 text-sm mb-1">Tên công ty:</div>
              <p class="text-slate-600">{{ legalProfile.company_name || 'CÔNG TY CỔ PHẦN CÔNG NGHỆ TOÀN CẦU' }}</p>
            </div>
            <div>
              <div class="font-bold text-slate-900 text-sm mb-1">Mã số thuế (MST):</div>
              <p class="text-slate-600 font-mono font-bold text-[#0284C7]">{{ legalProfile.tax_id || '0318999888' }}</p>
            </div>
            <div>
              <div class="font-bold text-slate-900 text-sm mb-1">Trụ sở chính:</div>
              <p class="text-slate-600">{{ legalProfile.headquarters || 'Tầng 8, Tòa nhà Công Nghệ Số, 123 Nguyễn Thị Minh Khai, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh' }}</p>
            </div>
            <div>
              <div class="font-bold text-slate-900 text-sm mb-1">Kênh tiếp nhận hỗ trợ:</div>
              <p class="text-slate-600">Hotline: {{ legalProfile.hotline || '1900 6868' }} | Email: {{ legalProfile.lead_email || 'lead@toancau.vn' }}</p>
            </div>
          </div>
        </div>

        <!-- Vision & Core Strengths -->
        <div v-if="aboutSections?.strengths?.metadata?.pillars?.length" class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div 
            v-for="(pil, pIdx) in aboutSections.strengths.metadata.pillars" 
            :key="pIdx" 
            class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-3"
          >
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">{{ pIdx + 1 }}</div>
            <h3 class="text-base font-bold text-slate-900">{{ pil.title }}</h3>
            <p class="text-xs text-slate-600 leading-relaxed">{{ pil.desc }}</p>
          </div>
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">1</div>
            <h3 class="text-base font-bold text-slate-900">Hạ Tầng Độc Quyền</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              Sở hữu kho tài nguyên địa chỉ IPv4 sạch trên APNIC, cụm máy chủ vật lý AMD EPYC và kết nối trực tiếp các Data Center Tier 3 lớn nhất nước.
            </p>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">2</div>
            <h3 class="text-base font-bold text-slate-900">Kỹ Sư Trực Tiếp 24/7</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              Không qua tổng đài trung gian ảo. Mọi yêu cầu hỗ trợ kỹ thuật đều được xử lý trực tiếp bởi đội ngũ kỹ sư mạng có chứng chỉ quốc tế.
            </p>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">3</div>
            <h3 class="text-base font-bold text-slate-900">Cam Kết SLA Khắt Khe</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              Mức độ sẵn sàng mạng 99.9%, hỗ trợ tiếp nhận Lead và ứng cứu sự cố trong vòng 15-30 phút được ràng buộc rõ ràng trong hợp đồng pháp lý.
            </p>
          </div>
        </div>

        <!-- Navigation Links to Projects & News -->
        <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
          <Link href="/du-an-tieu-bieu" class="px-6 py-3 rounded-xl bg-[#0F294A] text-white text-xs font-bold hover:bg-[#183F70] transition shadow">
            Xem Dự Án Tiêu Biểu →
          </Link>
          <Link href="/tin-tuc" class="px-6 py-3 rounded-xl bg-slate-200 text-slate-800 text-xs font-bold hover:bg-slate-300 transition">
            Tin Tức & Kiến Thức Kỹ Thuật
          </Link>
          <Link href="/chinh-sach-bao-mat" class="px-6 py-3 rounded-xl bg-slate-200 text-slate-800 text-xs font-bold hover:bg-slate-300 transition">
            Chính Sách Bảo Mật Dữ Liệu
          </Link>
        </div>

      </div>
    </div>
  </PublicLayout>
</template>
