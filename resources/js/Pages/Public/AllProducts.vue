<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  products: {
    type: Array,
    default: () => [],
  },
  categories: {
    type: Array,
    default: () => [],
  },
  currentSector: {
    type: String,
    default: 'all',
  },
  currentCategory: {
    type: String,
    default: 'all',
  },
  searchQuery: {
    type: String,
    default: '',
  },
});

const layoutRef = ref(null);
const searchInput = ref(props.searchQuery || '');
const selectedSector = ref(props.currentSector || 'all');

const sectors = [
  { code: 'all', label: 'Tất Cả Sản Phẩm' },
  { code: 'smart_iot', label: 'Smart IoT' },
  { code: 'cloud_infra', label: 'Cloud & Máy Chủ' },
  { code: 'bluecom', label: 'Bluecom 4G' },
  { code: 'connectivity', label: 'Internet & Mạng' },
  { code: 'tech_service', label: 'Dịch Vụ Kỹ Thuật' },
];

const filteredProducts = computed(() => {
  let list = props.products;

  if (selectedSector.value !== 'all') {
    list = list.filter(p => p.sector_code === selectedSector.value);
  }

  if (searchInput.value.trim()) {
    const q = searchInput.value.toLowerCase().trim();
    list = list.filter(p => 
      (p.name && p.name.toLowerCase().includes(q)) ||
      (p.model_code && p.model_code.toLowerCase().includes(q)) ||
      (p.overview && p.overview.toLowerCase().includes(q))
    );
  }

  return list;
});

const triggerLead = (type = 'consultation_quote', product = null, service = '') => {
  if (layoutRef.value) {
    layoutRef.value.openLeadModal(type, product, service || (product ? product.name : ''));
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
      <title>Toàn Bộ Danh Mục Sản Phẩm & Giải Pháp B2B - Toàn Cầu Tech</title>
      <meta name="description" content="Danh mục đầy đủ các thiết bị công nghệ, Smart IoT Solar Camera, Máy chủ Dedicated AMD EPYC, Cloud VPS NVMe và Hệ thống truyền thanh 4G Bluecom." />
    </Head>

    <!-- Header Section - High-Tech Modern IT Banner -->
    <div class="relative bg-[#061224] text-white py-14 border-b border-slate-800 overflow-hidden">
      <!-- High-Tech Cyber Grid & Glow -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_60%,transparent_100%)] pointer-events-none"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full">
            Toàn Bộ Hệ Sinh Thái B2B
          </span>
          <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
            Danh Mục Sản Phẩm & Thiết Bị Công Nghệ
          </h1>
          <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
            Hệ sinh thái thiết bị phần cứng chính hãng, máy chủ chuyên dụng và giải pháp truyền dẫn đạt chuẩn công nghiệp cho Doanh nghiệp, Đơn vị hành chính và Nhà tích hợp SI.
          </p>
        </div>

        <!-- Live Count Badge -->
        <div class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl bg-slate-900/70 border border-cyan-500/30 backdrop-blur-md shadow-lg shrink-0">
          <div class="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></div>
          <div>
            <div class="text-[11px] font-mono text-slate-400 uppercase">Tài nguyên sẵn sàng:</div>
            <div class="text-lg font-black text-white font-mono">{{ filteredProducts.length }} Sản phẩm</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white border-b border-slate-200 sticky top-20 z-30 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <!-- Sector Filter Pills -->
          <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 scrollbar-none text-xs font-bold">
            <button
              v-for="sec in sectors"
              :key="sec.code"
              type="button"
              @click="selectedSector = sec.code"
              :class="selectedSector === sec.code ? 'bg-[#0F294A] text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
              class="px-3.5 py-2 rounded-xl transition whitespace-nowrap"
            >
              {{ sec.label }}
            </button>
          </div>

          <!-- Search Bar -->
          <div class="relative w-full md:w-72 shrink-0">
            <input
              v-model="searchInput"
              type="text"
              placeholder="Tìm theo tên hoặc Model..."
              class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs text-slate-900 focus:outline-none focus:border-[#0284C7] focus:ring-1 focus:ring-[#0284C7]"
            />
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <button
              v-if="searchInput"
              @click="searchInput = ''"
              type="button"
              class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-600 text-xs"
            >
              ✕
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Grid Section -->
    <section class="py-12 bg-slate-50 min-h-[500px]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Grid -->
        <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="prod in filteredProducts"
            :key="prod.id"
            class="group bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between transform hover:-translate-y-1"
          >
            <div>
              <!-- Image Preview -->
              <Link :href="'/san-pham/' + prod.slug" class="block overflow-hidden rounded-xl bg-slate-50 relative aspect-[4/3] mb-4">
                <img
                  v-if="prod.thumbnail"
                  :src="prod.thumbnail"
                  :alt="prod.name"
                  loading="lazy"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <div v-else class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                  <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-[#0F294A]/80 backdrop-blur-sm text-cyan-300 text-[10px] font-mono font-bold">
                  {{ prod.model_code }}
                </span>
              </Link>

              <!-- Category & Badge -->
              <div class="flex items-center justify-between text-[11px] mb-1.5">
                <span class="font-bold text-[#0284C7] uppercase tracking-wide">
                  {{ prod.category?.name || 'Thiết Bị B2B' }}
                </span>
                <span v-if="prod.badge" class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                  {{ prod.badge }}
                </span>
              </div>

              <!-- Product Title -->
              <h3 class="font-bold text-slate-900 text-sm leading-snug line-clamp-2 group-hover:text-[#0284C7] transition">
                <Link :href="'/san-pham/' + prod.slug">
                  {{ prod.name }}
                </Link>
              </h3>

              <!-- Overview Snippet -->
              <p class="text-slate-600 text-xs mt-2 line-clamp-2 leading-relaxed">
                {{ prod.overview }}
              </p>
            </div>

            <!-- Price & CTA Group -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
              <div>
                <div class="text-[10px] text-slate-400 uppercase">Giá tham khảo:</div>
                <div class="text-sm font-extrabold text-[#0F294A]">
                  <template v-if="prod.is_price_visible && prod.price">
                    {{ formatPrice(prod.price) }}
                  </template>
                  <template v-else>
                    <span class="text-[#EA580C]">Liên hệ</span>
                  </template>
                </div>
              </div>

              <div class="flex items-center gap-1.5">
                <Link
                  :href="'/san-pham/' + prod.slug"
                  class="px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold transition"
                >
                  Chi tiết
                </Link>
                <button
                  type="button"
                  @click="triggerLead('consultation_quote', prod, prod.name)"
                  class="px-3 py-1.5 rounded-lg bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold transition shadow-sm"
                >
                  Báo Giá
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 max-w-lg mx-auto">
          <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-base font-bold text-slate-900 mb-1">Không tìm thấy sản phẩm phù hợp</h3>
          <p class="text-xs text-slate-500 mb-6">
            Thử thay đổi từ khóa tìm kiếm hoặc chọn danh mục khác để xem thêm thiết bị.
          </p>
          <button
            type="button"
            @click="searchInput = ''; selectedSector = 'all'"
            class="px-5 py-2.5 rounded-xl bg-[#0F294A] text-white text-xs font-bold hover:bg-[#183F70] transition shadow"
          >
            Xem Tất Cả Sản Phẩm
          </button>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>
