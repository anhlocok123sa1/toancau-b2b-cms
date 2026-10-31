<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  products: {
    type: Array,
    default: () => [],
  },
  isBluecomVerified: {
    type: Boolean,
    default: false,
  },
  bluecomSections: {
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

const formatPrice = (price) => {
  if (!price) return 'Liên hệ';
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
};
</script>

<template>
  <PublicLayout ref="layoutRef">
    <Head>
      <title>Truyền Thanh Thông Minh 4G Bluecom - Toàn Cầu Tech</title>
      <meta name="description" content="Hệ thống truyền thanh thông minh Bluecom 4G: Bộ thu phát 4G chống nước IP66, chuyển văn bản thành giọng nói AI, router công nghiệp Dual-SIM." />
    </Head>

    <!-- Header Section - High-Tech Modern IT Banner -->
    <div class="relative bg-[#061224] text-white py-14 border-b border-slate-800 overflow-hidden">
      <!-- High-Tech Cyber Grid & Glow -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_60%,transparent_100%)] pointer-events-none"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
          <div 
            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all"
            :class="isBluecomVerified ? 'bg-amber-400/20 text-amber-300 border border-amber-400/40 shadow-[0_0_15px_rgba(251,191,36,0.25)]' : 'bg-cyan-400/10 text-cyan-400 border border-cyan-400/20'"
          >
            <span v-if="isBluecomVerified" class="flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
              <span>✓ CHỨNG NHẬN NHÀ PHÂN PHỐI CHÍNH HÃNG BLUECOM 4G (BC-DIST-VN-2026)</span>
            </span>
            <span v-else>HỆ THỐNG TRUYỀN THANH SỐ 4G</span>
          </div>
          <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
            Truyền Thanh Thông Minh 4G & Thiết Bị Mạng Bluecom
          </h1>
          <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
            Giải pháp hiện đại hóa truyền thanh cơ sở, xóa bỏ hoàn toàn dây dẫn và sóng FM nhiễu. Tích hợp trí tuệ nhân tạo (AI Text-To-Speech) đọc bản tin tự động và quản lý tập trung từ xa qua Web/App.
          </p>
        </div>

        <button 
          type="button" 
          @click="triggerLead('technical_service', null, 'Đăng ký khảo sát hiện trường hệ thống truyền thanh Bluecom 4G')"
          class="px-6 py-3.5 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold shadow-xl shadow-[#EA580C]/25 transition shrink-0"
        >
          Đăng Ký Khảo Sát Hiện Trường
        </button>
      </div>
    </div>

    <div class="py-12 bg-slate-50 space-y-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- System Topology Diagram Representation -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
          <div class="max-w-3xl mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-[#0284C7] bg-cyan-50 px-3 py-1 rounded-full">
              {{ bluecomSections?.topology?.badge || 'Sơ Đồ Hệ Thống Hoạt Động' }}
            </span>
            <h2 class="text-2xl font-extrabold text-[#0F294A] mt-2">
              {{ bluecomSections?.topology?.title || 'Mô Hình Vận Hành Truyền Thanh Thông Minh Bluecom 4G Đám Mây' }}
            </h2>
            <p class="text-xs text-slate-600 mt-1">
              {{ bluecomSections?.topology?.content || 'Dữ liệu âm thanh và kịch bản phát thanh được mã hóa an toàn truyền từ Trung tâm điều hành tới từng cụm loa xã/phường qua mạng di động 4G.' }}
            </p>
          </div>

          <div v-if="bluecomSections?.topology?.metadata?.steps?.length" class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
            <div 
              v-for="(st, sIdx) in bluecomSections.topology.metadata.steps" 
              :key="sIdx"
              class="p-5 rounded-2xl border flex flex-col items-center"
              :class="sIdx === 1 ? 'bg-cyan-50 border-cyan-100' : sIdx === 3 ? 'bg-emerald-50 border-emerald-100' : 'bg-slate-50 border-slate-200'"
            >
              <div 
                class="w-12 h-12 rounded-xl text-white flex items-center justify-center font-bold mb-3"
                :class="sIdx === 1 ? 'bg-[#0284C7]' : sIdx === 3 ? 'bg-emerald-600' : sIdx === 2 ? 'bg-slate-800' : 'bg-[#0F294A]'"
              >
                {{ st.step || (sIdx + 1) }}
              </div>
              <div class="font-bold text-sm text-slate-900">{{ st.title }}</div>
              <p class="text-xs text-slate-500 mt-1">{{ st.desc }}</p>
            </div>
          </div>
          <div v-else class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col items-center">
              <div class="w-12 h-12 rounded-xl bg-[#0F294A] text-white flex items-center justify-center font-bold mb-3">1</div>
              <div class="font-bold text-sm text-slate-900">Ban Lãnh Đạo / Đài TT</div>
              <p class="text-xs text-slate-500 mt-1">Nhập văn bản bản tin hoặc thu âm trực tiếp trên Điện thoại/Máy tính.</p>
            </div>

            <div class="p-5 rounded-2xl bg-cyan-50 border border-cyan-100 flex flex-col items-center">
              <div class="w-12 h-12 rounded-xl bg-[#0284C7] text-white flex items-center justify-center font-bold mb-3">2</div>
              <div class="font-bold text-sm text-[#0F294A]">Bluecom Cloud CMS & AI</div>
              <p class="text-xs text-slate-600 mt-1">AI chuyển văn bản thành giọng đọc truyền cảm, lập lịch phát thanh tự động.</p>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col items-center">
              <div class="w-12 h-12 rounded-xl bg-slate-800 text-white flex items-center justify-center font-bold mb-3">3</div>
              <div class="font-bold text-sm text-slate-900">Hạ Tầng Sóng 4G LTE</div>
              <p class="text-xs text-slate-500 mt-1">Truyền dẫn không dây qua mạng Viettel, VinaPhone, MobiFone đa điểm.</p>
            </div>

            <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100 flex flex-col items-center">
              <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold mb-3">4</div>
              <div class="font-bold text-sm text-emerald-900">Cụm Thu Bluecom & Loa</div>
              <p class="text-xs text-emerald-700 mt-1">Giải mã âm thanh Hi-Fi số trong trẻo, chống mưa bão IP66 ngoài trời.</p>
            </div>
          </div>
        </div>

        <!-- Products List -->
        <div>
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-extrabold text-[#0F294A]">Thiết Bị Phần Cứng & Phần Mềm Bluecom</h2>
            <span class="text-xs text-slate-500 font-medium">Bảo hành 24 tháng chính hãng</span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="prod in products" 
              :key="prod.id"
              class="bg-white rounded-3xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition"
            >
              <div>
                <div class="h-44 bg-slate-100 rounded-2xl flex items-center justify-center relative overflow-hidden mb-4">
                  <div class="w-16 h-16 rounded-2xl bg-[#0F294A]/10 flex items-center justify-center text-[#0F294A]">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                    </svg>
                  </div>
                  <div class="absolute top-3 left-3 bg-[#0F294A] text-white text-[10px] font-mono px-2 py-0.5 rounded font-bold">
                    {{ prod.model_code }}
                  </div>
                </div>

                <h3 class="text-base font-extrabold text-slate-900 leading-snug line-clamp-2">{{ prod.name }}</h3>
                <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">{{ prod.overview }}</p>

                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-xs">
                  <div v-for="(spec, sidx) in (prod.specs || []).slice(0, 3)" :key="sidx" class="flex justify-between">
                    <span class="text-slate-500">{{ spec.label }}:</span>
                    <span class="font-bold text-slate-800">{{ spec.value }}</span>
                  </div>
                </div>
              </div>

              <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <div>
                  <div class="text-[10px] text-slate-400">Giá B2B:</div>
                  <div class="text-base font-black text-[#0F294A]">
                    {{ prod.is_price_visible ? formatPrice(prod.price) : 'Liên hệ' }}
                  </div>
                </div>

                <div class="flex items-center gap-2">
                  <Link :href="'/san-pham/' + prod.slug" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                    11 Thành Phần
                  </Link>
                  <button 
                    type="button" 
                    @click="triggerLead('consultation_quote', prod)"
                    class="px-4 py-2 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold transition shadow-sm"
                  >
                    Báo Giá
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </PublicLayout>
</template>
