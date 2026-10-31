<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
  breadcrumbs: {
    type: Array,
    default: () => [],
  },
  jsonLdBreadcrumbs: {
    type: Object,
    default: () => ({}),
  },
  relatedProducts: {
    type: Array,
    default: () => [],
  },
});

const layoutRef = ref(null);
const selectedImageIndex = ref(0);

const openQuote = () => {
  if (layoutRef.value) {
    layoutRef.value.openLeadModal('consultation_quote', props.product, props.product?.name);
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
      <title>{{ product.meta_title || (product.name + ' - Toàn Cầu Tech') }}</title>
      <meta name="description" :content="product.meta_description" />
      <!-- Component [01]: JSON-LD BreadcrumbList -->
      <component is="script" type="application/ld+json">
        {{ JSON.stringify(jsonLdBreadcrumbs) }}
      </component>
    </Head>

    <div class="bg-slate-50 py-8 min-h-screen">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- [01] HIERARCHICAL BREADCRUMB -->
        <nav class="flex items-center text-xs text-slate-500 mb-6 flex-wrap gap-2" aria-label="Breadcrumb">
          <template v-for="(crumb, idx) in breadcrumbs" :key="idx">
            <span v-if="idx > 0" class="text-slate-400">/</span>
            <Link 
              v-if="idx < breadcrumbs.length - 1" 
              :href="crumb.url" 
              class="hover:text-[#0284C7] transition"
            >
              {{ crumb.name }}
            </Link>
            <span v-else class="text-slate-900 font-semibold truncate max-w-xs sm:max-w-md">
              {{ crumb.name }}
            </span>
          </template>
        </nav>

        <!-- TOP HERO: GALLERY & CORE B2B VALUE PROPOSITION -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-10 shadow-sm mb-10">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- [03] MULTI-ANGLE WEBP IMAGE GALLERY -->
            <div class="lg:col-span-5 space-y-4">
              <!-- Main Image Display with zoom effect & lazy loading -->
              <div class="relative bg-slate-100 rounded-2xl overflow-hidden aspect-square flex items-center justify-center border border-slate-200/60 group">
                <div class="p-8 flex items-center justify-center">
                  <div class="w-32 h-32 rounded-3xl bg-[#0F294A]/10 flex items-center justify-center text-[#0F294A]">
                    <svg class="w-20 h-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                  </div>
                </div>

                <!-- Model Code Badge inside image -->
                <div class="absolute bottom-4 left-4 bg-[#0F294A]/90 backdrop-blur-sm text-white text-xs font-mono font-bold px-3 py-1 rounded-lg">
                  {{ product.model_code }}
                </div>
              </div>

              <!-- Thumbnails Selector -->
              <div v-if="product.gallery && product.gallery.length > 1" class="flex items-center gap-3 overflow-x-auto pb-2">
                <button 
                  v-for="(img, idx) in product.gallery" 
                  :key="idx"
                  type="button"
                  @click="selectedImageIndex = idx"
                  :class="selectedImageIndex === idx ? 'border-[#0284C7] ring-2 ring-[#0284C7]/20' : 'border-slate-200 opacity-70 hover:opacity-100'"
                  class="w-16 h-16 rounded-xl border-2 bg-slate-100 shrink-0 flex items-center justify-center transition overflow-hidden"
                >
                  <span class="text-xs font-mono font-bold text-slate-500">0{{ idx + 1 }}</span>
                </button>
              </div>
            </div>

            <!-- RIGHT COLUMN: NAME, MODEL, OVERVIEW & CTA -->
            <div class="lg:col-span-7 flex flex-col justify-between">
              <div>
                <!-- [02] H1 NAME + OFFICIAL MODEL CODE -->
                <div class="flex items-center gap-3 flex-wrap">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-cyan-100 text-[#0284C7]">
                    Model: {{ product.model_code }}
                  </span>
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                    {{ product.badge || product.category_name || 'Sản Phẩm B2B Tiêu Chuẩn' }}
                  </span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold text-[#0F294A] mt-3 tracking-tight leading-tight">
                  {{ product.name }}
                </h1>

                <!-- [04] OVERVIEW (2-3 SENTENCES B2B VALUE) -->
                <div class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-sm text-slate-700 leading-relaxed font-normal">
                  {{ product.overview }}
                </div>

                <!-- [05] KEY FEATURES & HIGHLIGHTS -->
                <div class="mt-6">
                  <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                    Đặc Điểm & Tính Năng Nổi Bật:
                  </h3>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div 
                      v-for="(feat, idx) in product.features" 
                      :key="idx"
                      class="flex items-start gap-2.5 p-3 rounded-xl bg-cyan-50/50 border border-cyan-100"
                    >
                      <svg class="w-4 h-4 text-[#0284C7] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <div>
                        <div class="text-xs font-bold text-slate-900">{{ feat.title }}</div>
                        <div class="text-[11px] text-slate-600 mt-0.5 leading-tight">{{ feat.desc }}</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- [09] WARRANTY POLICY & TECHNICAL SLA -->
                <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                  <div class="flex items-center gap-2 text-slate-700">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Bảo hành:</strong> {{ product.warranty_policy }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-slate-700">
                    <svg class="w-4 h-4 text-[#0284C7] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Cam kết kỹ thuật:</strong> {{ product.support_commitment }}</span>
                  </div>
                </div>
              </div>

              <!-- [11] CTA YÊU CẦU BÁO GIÁ & PRICE TOGGLE -->
              <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50 -mx-6 -mb-6 sm:-mx-10 sm:-mb-10 p-6 sm:px-10 rounded-b-3xl">
                <div>
                  <div class="text-xs text-slate-500 font-semibold uppercase">Đơn giá niêm yết:</div>
                  <div class="text-2xl font-black text-[#0F294A]">
                    <template v-if="product.is_price_visible">
                      {{ formatPrice(product.price) }}
                      <span class="text-xs font-normal text-slate-500">/ {{ product.price_unit }}</span>
                    </template>
                    <template v-else>
                      <span class="text-[#EA580C]">Liên hệ nhận giá đại lý</span>
                    </template>
                  </div>
                </div>

                <div class="flex items-center gap-3">
                  <!-- [08] DATASHEET PDF DOWNLOAD BUTTON (Controlled by Compliance Flag) -->
                  <a 
                    v-if="product.datasheet_pdf_path && ($page.props.compliance?.product_datasheets_warranty !== false)" 
                    :href="product.datasheet_pdf_path" 
                    download
                    class="px-4 py-3 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-800 text-xs font-bold transition flex items-center gap-2"
                  >
                    <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                    </svg>
                    <span>Tải Datasheet (PDF)</span>
                  </a>
                  <div v-else-if="product.datasheet_pdf_path" class="px-3 py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-[11px] font-medium flex items-center gap-1.5">
                    <span>Datasheet PDF đang được cập nhật</span>
                  </div>

                  <!-- CTA Button -->
                  <button 
                    type="button" 
                    @click="openQuote"
                    class="px-6 py-3 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-[#EA580C]/25 transition flex items-center gap-2 transform hover:-translate-y-0.5"
                  >
                    <span>Yêu Cầu Báo Giá</span>
                    <span class="bg-black/20 text-white text-[10px] px-2 py-0.5 rounded-full font-medium">SLA 15p</span>
                  </button>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- [06] 2-COLUMN TECH SPECS TABLE & [07] APPLICATION SCENARIOS -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
          
          <!-- [06] 2-Column Tech Specs Table (Dynamic JSON key-value) -->
          <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-bold text-[#0F294A] flex items-center gap-2">
                <svg class="w-5 h-5 text-[#0284C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Bảng Thông Số Kỹ Thuật Chi Tiết</span>
              </h3>
              <span class="text-xs text-slate-400 font-mono">Standard 2-Column</span>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200">
              <table class="w-full text-left text-xs border-collapse">
                <tbody>
                  <tr 
                    v-for="(spec, idx) in product.specs" 
                    :key="idx"
                    :class="idx % 2 === 0 ? 'bg-slate-50/70' : 'bg-white'"
                    class="border-b border-slate-200/80 last:border-0 hover:bg-cyan-50/30 transition"
                  >
                    <td class="py-3 px-4 font-bold text-slate-700 w-1/3 border-r border-slate-200/80">
                      {{ spec.label }}
                    </td>
                    <td class="py-3 px-4 font-medium text-slate-900 w-2/3 leading-relaxed">
                      {{ spec.value }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- [07] REAL-WORLD APPLICATION SCENARIOS & ENVIRONMENTS -->
          <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm flex flex-col justify-between">
            <div>
              <h3 class="text-xl font-bold text-[#0F294A] flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-[#0284C7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Kịch Bản & Môi Trường Ứng Dụng</span>
              </h3>
              <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                Thiết kế thích ứng và tối ưu hóa chuyên sâu cho các môi trường vận hành công nghiệp và doanh nghiệp tại Việt Nam:
              </p>

              <div class="space-y-3">
                <div 
                  v-for="(scenario, idx) in product.application_scenarios" 
                  :key="idx"
                  class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800"
                >
                  <div class="w-6 h-6 rounded-lg bg-[#0F294A] text-white flex items-center justify-center font-bold shrink-0 text-[10px]">
                    {{ idx + 1 }}
                  </div>
                  <div class="font-medium leading-relaxed">{{ scenario }}</div>
                </div>
              </div>
            </div>

            <!-- Survey Call to Action -->
            <div class="mt-8 p-4 rounded-2xl bg-gradient-to-r from-[#0F294A] to-[#183F70] text-white">
              <div class="font-bold text-xs uppercase tracking-wide text-cyan-300">Khảo sát hiện trường miễn phí</div>
              <p class="text-[11px] text-slate-300 mt-1">Kỹ sư Toàn Cầu hỗ trợ đo kiểm sóng, thiết kế sơ đồ mạng và demo thực tế tại công trình của quý khách.</p>
              <button 
                type="button" 
                @click="openQuote"
                class="mt-3 w-full py-2 bg-[#EA580C] hover:bg-[#C2410C] rounded-lg text-xs font-bold text-center shadow transition"
              >
                Đăng Ký Khảo Sát Hiện Trường
              </button>
            </div>
          </div>

        </div>

        <!-- [10] RELATED PRODUCTS / CROSS-SELLING (3-4 ITEMS) -->
        <div v-if="relatedProducts && relatedProducts.length > 0" class="mb-12">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-extrabold text-[#0F294A]">
              Sản Phẩm & Thiết Bị Liên Quan (Cross-Selling)
            </h3>
            <span class="text-xs text-slate-500 font-medium">Bổ trợ hoàn hảo cho hệ thống</span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div 
              v-for="rel in relatedProducts" 
              :key="rel.id"
              class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between"
            >
              <div>
                <div class="h-32 bg-slate-100 rounded-xl mb-3 flex items-center justify-center text-slate-400">
                  <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                  </svg>
                </div>
                <div class="text-[10px] font-mono font-semibold text-[#0284C7]">{{ rel.model_code }}</div>
                <h4 class="font-bold text-slate-900 text-xs mt-1 line-clamp-2 leading-snug">{{ rel.name }}</h4>
              </div>

              <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <div class="text-xs font-bold text-[#0F294A]">
                  {{ rel.is_price_visible ? formatPrice(rel.price) : 'Liên hệ' }}
                </div>
                <Link 
                  :href="'/san-pham/' + rel.slug" 
                  class="text-xs font-semibold text-[#0284C7] hover:underline"
                >
                  Xem chi tiết →
                </Link>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </PublicLayout>
</template>
