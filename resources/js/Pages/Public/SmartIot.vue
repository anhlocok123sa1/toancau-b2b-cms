<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  subCategories: {
    type: Array,
    default: () => [],
  },
  products: {
    type: Array,
    default: () => [],
  },
  activeSubCategory: {
    type: String,
    default: 'all',
  },
  iotSections: {
    type: Object,
    default: () => ({}),
  },
});

const layoutRef = ref(null);
const currentFilter = ref(props.activeSubCategory || 'all');

const setFilter = (subKey) => {
  currentFilter.value = subKey;
  if (typeof window !== 'undefined') {
    const url = new URL(window.location.href);
    if (subKey === 'all') {
      url.searchParams.delete('sub');
    } else {
      url.searchParams.set('sub', subKey);
    }
    window.history.replaceState({}, '', url.toString());
  }
};

const getCategoryCount = (sub) => {
  return props.products.filter(p => {
    if (p.category_id && (p.category_id === sub.id || p.category?.id === sub.id)) {
      return true;
    }
    if (p.category?.slug === sub.slug) {
      return true;
    }
    if (p.slug && p.slug.includes(sub.slug)) {
      return true;
    }
    return false;
  }).length;
};

const filteredProducts = computed(() => {
  if (currentFilter.value === 'all') {
    return props.products;
  }
  return props.products.filter(p => {
    // 1. Match category object slug or ID
    if (p.category) {
      if (p.category.slug === currentFilter.value || p.category.id == currentFilter.value) {
        return true;
      }
    }
    // 2. Match direct category_id
    if (p.category_id && p.category_id == currentFilter.value) {
      return true;
    }
    // 3. Fallback matching subcategory ID against subCategories
    const matchedCat = props.subCategories.find(c => c.slug === currentFilter.value);
    if (matchedCat && p.category_id === matchedCat.id) {
      return true;
    }
    // 4. Fallback matching product slug containing category slug keyword
    if (p.slug && p.slug.includes(currentFilter.value)) {
      return true;
    }
    return false;
  });
});

const activeCategoryName = computed(() => {
  if (currentFilter.value === 'all') return 'Tất cả thiết bị Smart IoT';
  const match = props.subCategories.find(c => c.slug === currentFilter.value || c.id == currentFilter.value);
  return match ? match.name : 'Danh mục đã chọn';
});

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
      <title>Smart IoT & Thiết Bị Giám Sát Thông Minh - Toàn Cầu Tech</title>
      <meta name="description" content="Hệ sinh thái Smart IoT: Solar Camera 4G ngoài trời, Camera ven biển IP67, IoT Gateway Modbus/MQTT, Cảm biến công nông nghiệp chính hãng Toàn Cầu Technology." />
    </Head>

    <!-- Header Hero Banner - High-Tech Modern IT Banner -->
    <div class="relative bg-[#061224] text-white py-14 border-b border-slate-800 overflow-hidden">
      <!-- High-Tech Cyber Grid & Glow -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_60%,transparent_100%)] pointer-events-none"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full">
          {{ iotSections?.solutions_overview?.badge || 'Industrial & Agricultural IoT Ecosystem' }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
          {{ iotSections?.solutions_overview?.title || 'Smart IoT & Thiết Bị Giám Sát Không Dây' }}
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
          {{ iotSections?.solutions_overview?.content || 'Giải pháp giám sát độc lập 100% không cần điện lưới và mạng dây, ứng dụng công nghệ năng lượng mặt trời, kết nối 4G LTE và cảm biến công nghiệp độ chính xác cao.' }}
        </p>
      </div>
    </div>

    <!-- Dynamic Sub-Category Filters (Synced with Admin CMS) -->
    <div class="bg-white border-b border-slate-200 sticky top-20 z-30 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-2.5 overflow-x-auto py-3 text-xs font-bold scrollbar-none items-center">
          <!-- All Categories Tab -->
          <button 
            type="button" 
            @click="setFilter('all')"
            :class="currentFilter === 'all' ? 'bg-[#0F294A] text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
            class="px-4 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5"
          >
            <span>Tất Cả Danh Mục</span>
            <span :class="currentFilter === 'all' ? 'bg-[#0284C7] text-white' : 'bg-slate-200 text-slate-700'" class="px-2 py-0.5 rounded-full text-[10px]">
              {{ products.length }}
            </span>
          </button>

          <!-- Dynamic Sub-Categories from CMS -->
          <button 
            v-for="(sub, idx) in subCategories" 
            :key="sub.id"
            type="button" 
            @click="setFilter(sub.slug)"
            :class="currentFilter === sub.slug || currentFilter == sub.id ? 'bg-[#0F294A] text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
            class="px-4 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5"
          >
            <span>({{ idx + 1 }}) {{ sub.name }}</span>
            <span :class="currentFilter === sub.slug || currentFilter == sub.id ? 'bg-[#0284C7] text-white' : 'bg-slate-200 text-slate-700'" class="px-2 py-0.5 rounded-full text-[10px]">
              {{ getCategoryCount(sub) }}
            </span>
          </button>
        </div>
      </div>
    </div>

    <!-- Product Grid & Filter Results -->
    <div class="py-12 bg-slate-50 min-h-[500px]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Header Bar -->
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-200">
          <div>
            <h2 class="text-lg font-bold text-[#0F294A] flex items-center gap-2">
              <span>{{ activeCategoryName }}</span>
              <span class="text-xs font-normal text-slate-500">({{ filteredProducts.length }} thiết bị)</span>
            </h2>
          </div>
          <div v-if="currentFilter !== 'all'">
            <button 
              type="button" 
              @click="setFilter('all')" 
              class="text-xs font-bold text-[#0284C7] hover:underline flex items-center gap-1"
            >
              <span>✕ Bỏ bộ lọc</span>
            </button>
          </div>
        </div>

        <!-- Empty State when no products match filter -->
        <div v-if="filteredProducts.length === 0" class="py-16 text-center bg-white rounded-3xl border border-dashed border-slate-300 p-8 shadow-sm">
          <div class="w-16 h-16 rounded-2xl bg-cyan-50 text-[#0284C7] flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
          </div>
          <h3 class="text-base font-bold text-slate-900 mb-1">Đang cập nhật sản phẩm cho: {{ activeCategoryName }}</h3>
          <p class="text-xs text-slate-500 max-w-md mx-auto mb-6 leading-relaxed">
            Toàn Cầu Technology cung cấp giải pháp trọn gói và thiết bị đo kiểm, giám sát theo yêu cầu cụ thể của từng công trình. Quý khách vui lòng gửi yêu cầu để nhận giải pháp kỹ thuật chuyên biệt.
          </p>
          <div class="flex flex-wrap items-center justify-center gap-3">
            <button 
              type="button" 
              @click="triggerLead('consultation_quote', null, 'Yêu cầu tư vấn thiết bị Smart IoT: ' + activeCategoryName)"
              class="px-5 py-2.5 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white font-bold text-xs shadow-md transition"
            >
              Yêu Cầu Báo Giá Thiết Bị Này
            </button>
            <button 
              type="button" 
              @click="setFilter('all')"
              class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition"
            >
              Xem Tất Cả Sản Phẩm
            </button>
          </div>
        </div>

        <!-- Products Grid -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="prod in filteredProducts" 
            :key="prod.id"
            class="bg-white rounded-3xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition"
          >
            <div>
              <div class="h-44 bg-slate-100 rounded-2xl flex items-center justify-center relative overflow-hidden mb-4">
                <img 
                  v-if="prod.thumbnail" 
                  :src="prod.thumbnail" 
                  :alt="prod.name" 
                  class="w-full h-full object-cover" 
                />
                <div v-else class="w-16 h-16 rounded-2xl bg-[#0F294A]/10 flex items-center justify-center text-[#0F294A]">
                  <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div class="absolute top-3 left-3 bg-[#0F294A] text-white text-[10px] font-mono px-2 py-0.5 rounded font-bold">
                  {{ prod.model_code }}
                </div>
                <div class="absolute top-3 right-3 bg-emerald-600 text-white text-[10px] px-2 py-0.5 rounded font-bold">
                  {{ prod.category?.name || 'Smart IoT' }}
                </div>
              </div>

              <h3 class="text-base font-extrabold text-slate-900 leading-snug line-clamp-2">
                {{ prod.name }}
              </h3>
              <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                {{ prod.overview }}
              </p>

              <!-- Key Specs Preview -->
              <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-700">
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
  </PublicLayout>
</template>
