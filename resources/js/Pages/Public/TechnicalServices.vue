<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  techSections: {
    type: Object,
    default: () => ({}),
  },
});

const layoutRef = ref(null);

const triggerTechnicalForm = (serviceCategory = 'Thi công & Lắp đặt hệ thống mới') => {
  if (layoutRef.value) {
    layoutRef.value.openLeadModal('technical_service', null, serviceCategory);
  }
};
</script>

<template>
  <PublicLayout ref="layoutRef">
    <Head>
      <title>Dịch Vụ Kỹ Thuật & Khảo Sát Hiện Trường - Toàn Cầu Tech</title>
      <meta name="description" content="Dịch vụ thi công lắp đặt mạng cáp quang, phòng server, hệ thống camera, IoT, truyền thanh thông minh và bảo trì sửa chữa khẩn cấp SLA 15-30 phút." />
    </Head>

    <!-- Header Section - High-Tech Modern IT Banner -->
    <div class="relative bg-[#061224] text-white py-14 border-b border-slate-800 overflow-hidden">
      <!-- High-Tech Cyber Grid & Glow -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_60%,transparent_100%)] pointer-events-none"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full">
          Field Engineering & Emergency Maintenance
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
          Dịch Vụ Kỹ Thuật Chuyên Nghiệp & Khảo Sát Hiện Trường
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
          Đội ngũ kỹ sư mạng (CCNA/CCNP) và kỹ sư cơ điện giàu kinh nghiệm, sẵn sàng tiếp nhận khảo sát công trình, thi công hạ tầng viễn thông và khắc phục sự cố khẩn cấp trên toàn quốc.
        </p>
      </div>
    </div>

    <div class="py-12 bg-slate-50 space-y-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Two Core Service Blocks -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          
          <!-- Block 1: Deployment & Installation -->
          <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-slate-900">{{ techSections?.installation?.title || 'Dịch Vụ Thi Công & Lắp Đặt Trọn Gói' }}</h2>
              <p class="text-xs text-slate-600 leading-relaxed">
                {{ techSections?.installation?.content || 'Thiết kế bản vẽ, đo kiểm suy hao quang học và thi công lắp đặt theo tiêu chuẩn công nghiệp:' }}
              </p>
              <ul class="text-xs text-slate-700 space-y-2">
                <template v-if="techSections?.installation?.metadata?.items?.length">
                  <li v-for="(it, iIdx) in techSections.installation.metadata.items" :key="iIdx" class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0284C7]"></span>
                    <span>{{ it }}</span>
                  </li>
                </template>
                <template v-else>
                  <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0284C7]"></span>
                    <span>Hạ tầng mạng LAN / Wi-Fi công nghiệp tòa nhà & KCN</span>
                  </li>
                  <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0284C7]"></span>
                    <span>Hệ thống Solar Camera ngoài trời không dây</span>
                  </li>
                  <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0284C7]"></span>
                    <span>Hệ thống truyền thanh thông minh Bluecom 4G cơ sở</span>
                  </li>
                  <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0284C7]"></span>
                    <span>Phòng máy chủ Data Center, tủ Rack & hệ thống PDU</span>
                  </li>
                </template>
              </ul>
            </div>

            <button 
              type="button" 
              @click="triggerTechnicalForm('Thi công & Lắp đặt hạ tầng mới')"
              class="w-full py-3 bg-[#0F294A] hover:bg-[#183F70] text-white rounded-xl text-xs font-bold transition shadow"
            >
              Yêu Cầu Thi Công Mới (SLA 30p)
            </button>
          </div>

          <!-- Block 2: Maintenance & Diagnostic -->
          <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="w-12 h-12 rounded-2xl bg-orange-50 text-[#EA580C] flex items-center justify-center font-bold">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-slate-900">{{ techSections?.maintenance?.title || 'Bảo Trì, Chẩn Đoán & Sửa Chữa Khẩn Cấp' }}</h2>
              <p class="text-xs text-slate-600 leading-relaxed">
                {{ techSections?.maintenance?.content || 'Đội cơ động kỹ thuật có mặt tại hiện trường xử lý các tình huống đứt cáp quang, chập cháy, sét đánh hoặc lỗi thiết bị viễn thông:' }}
              </p>
              <ul class="text-xs text-slate-700 space-y-2">
                <template v-if="techSections?.maintenance?.metadata?.items?.length">
                  <li v-for="(it, mIdx) in techSections.maintenance.metadata.items" :key="mIdx" class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#EA580C]"></span>
                    <span>{{ it }}</span>
                  </li>
                </template>
                <template v-else>
                  <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#EA580C]"></span>
                    <span>Đo kiểm chẩn đoán điểm đứt cáp quang OTDR chính xác</span>
                  </li>
                  <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#EA580C]"></span>
                    <span>Thay thế thiết bị dự phòng Router/Switch trong 2 giờ</span>
                  </li>
                  <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#EA580C]"></span>
                    <span>Bảo trì định kỳ chống sét lan truyền và hệ thống nguồn</span>
                  </li>
                  <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#EA580C]"></span>
                    <span>Hỗ trợ kỹ thuật 24/7/365 qua Hotline riêng biệt</span>
                  </li>
                </template>
              </ul>
            </div>

            <button 
              type="button" 
              @click="triggerTechnicalForm('Sửa chữa & Chẩn đoán sự cố khẩn cấp')"
              class="w-full py-3 bg-[#EA580C] hover:bg-[#C2410C] text-white rounded-xl text-xs font-bold transition shadow-lg shadow-[#EA580C]/25"
            >
              Yêu Cầu Ứng Cứu Sự Cố Khẩn Cấp (SLA 15p)
            </button>
          </div>

        </div>

      </div>
    </div>
  </PublicLayout>
</template>
