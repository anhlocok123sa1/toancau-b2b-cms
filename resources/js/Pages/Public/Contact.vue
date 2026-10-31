<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  legalProfile: {
    type: Object,
    default: () => ({}),
  },
});

const layoutRef = ref(null);

const triggerLead = (service = 'Liên hệ tư vấn tổng hợp') => {
  if (layoutRef.value) {
    layoutRef.value.openLeadModal('consultation_quote', null, service);
  }
};
</script>

<template>
  <PublicLayout ref="layoutRef">
    <Head>
      <title>Liên Hệ & Bản Đồ Trụ Sở - Toàn Cầu Tech</title>
      <meta name="description" content="Thông tin liên hệ, hotline hỗ trợ 24/7 và địa chỉ trụ sở chính Công ty Cổ phần Công nghệ Toàn Cầu tại TP. Hồ Chí Minh." />
    </Head>

    <!-- Header Section - High-Tech Modern IT Banner -->
    <div class="relative bg-[#061224] text-white py-14 border-b border-slate-800 overflow-hidden">
      <!-- High-Tech Cyber Grid & Glow -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_60%,transparent_100%)] pointer-events-none"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full">
          24/7/365 Direct Support
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
          Liên Hệ Toàn Cầu Technology
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
          Đội ngũ chuyên viên tư vấn giải pháp và kỹ sư mạng sẵn sàng tiếp nhận yêu cầu 24/7, cam kết phản hồi trong tối đa 15-30 phút.
        </p>
      </div>
    </div>

    <div class="py-12 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          
          <!-- Contact Info -->
          <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
            <h2 class="text-2xl font-bold text-[#0F294A]">Thông Tin Trụ Sở Chính</h2>
            
            <div class="space-y-4 text-xs text-slate-700">
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-50 text-[#0284C7] flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                  </svg>
                </div>
                <div>
                  <div class="font-bold text-slate-900 text-sm">Pháp nhân:</div>
                  <div class="text-slate-600 mt-0.5">{{ legalProfile.company_name || 'CÔNG TY CỔ PHẦN CÔNG NGHỆ TOÀN CẦU' }}</div>
                  <div class="text-slate-500 font-mono mt-0.5">MST: {{ legalProfile.tax_id || '0318999888' }}</div>
                </div>
              </div>

              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-50 text-[#0284C7] flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
                <div>
                  <div class="font-bold text-slate-900 text-sm">Địa chỉ trụ sở:</div>
                  <div class="text-slate-600 mt-0.5">{{ legalProfile.headquarters || 'Tầng 8, Tòa nhà Công Nghệ Số, 123 Nguyễn Thị Minh Khai, P. Bến Thành, Quận 1, TP. Hồ Chí Minh' }}</div>
                </div>
              </div>

              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-50 text-[#0284C7] flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                </div>
                <div>
                  <div class="font-bold text-slate-900 text-sm">Hotline kỹ thuật:</div>
                  <a :href="'tel:' + (legalProfile.hotline || '19006868')" class="text-[#EA580C] font-extrabold text-base hover:underline mt-0.5 block">
                    {{ legalProfile.hotline || '1900 6868 - 028 7300 8888' }}
                  </a>
                </div>
              </div>

              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-50 text-[#0284C7] flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div>
                  <div class="font-bold text-slate-900 text-sm">Email nhận Lead & Báo giá:</div>
                  <div class="text-slate-600 mt-0.5">{{ legalProfile.lead_email || 'lead@toancau.vn' }}</div>
                </div>
              </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
              <button 
                type="button" 
                @click="triggerLead('Yêu cầu gặp chuyên gia tư vấn tại trụ sở')"
                class="w-full py-3 bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold rounded-xl transition shadow"
              >
                Gửi Yêu Cầu Hẹn Lịch Làm Việc (SLA 30p)
              </button>
            </div>
          </div>

          <!-- HQ Map representation -->
          <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold text-[#0F294A] mb-4">Bản Đồ Vị Trí Trụ Sở & Điểm POP Viễn Thông</h3>
            <div class="h-80 bg-slate-100 rounded-2xl flex flex-col items-center justify-center border border-slate-200 text-slate-500 p-6 text-center space-y-3">
              <div class="w-16 h-16 rounded-full bg-cyan-100 text-[#0284C7] flex items-center justify-center font-bold">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
              </div>
              <div class="font-bold text-slate-900 text-sm">
                123 Nguyễn Thị Minh Khai, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh
              </div>
              <p class="text-xs text-slate-500 max-w-md">
                Tòa nhà kết nối trực tiếp tuyến cáp quang metro VNPT/Viettel tới các Data Center Q9 & Tân Bình.
              </p>
            </div>
          </div>

        </div>

      </div>
    </div>
  </PublicLayout>
</template>
