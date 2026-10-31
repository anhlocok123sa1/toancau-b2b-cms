<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const auth = computed(() => page.props.auth?.user || {});
const flash = computed(() => page.props.flash || {});

const props = defineProps({
  product: {
    type: Object,
    default: null,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  otherProducts: {
    type: Array,
    default: () => [],
  },
  prefillSector: {
    type: String,
    default: 'smart_iot',
  },
});

const isEditMode = computed(() => !!props.product);

// Initialize visual reactive form fields
const form = useForm({
  name: props.product?.name || '',
  model_code: props.product?.model_code || '',
  badge: props.product?.badge || 'Sản Phẩm B2B Tiêu Chuẩn',
  sector_code: props.product?.sector_code || props.prefillSector || 'smart_iot',
  category_id: props.product?.category_id || (props.categories?.[0]?.id || ''),
  overview: props.product?.overview || '',
  warranty_policy: props.product?.warranty_policy || 'Bảo hành chính hãng 24 tháng theo tiêu chuẩn nhà sản xuất; 1 đổi 1 trong 30 ngày.',
  support_commitment: props.product?.support_commitment || 'Khảo sát hiện trường miễn phí, kỹ sư hỗ trợ 24/7/365, SLA 15-30 phút.',
  price: props.product?.price || '',
  price_unit: props.product?.price_unit || 'bộ',
  is_price_visible: props.product ? !!props.product.is_price_visible : true,
  is_featured: props.product ? !!props.product.is_featured : false,
  is_active: props.product ? !!props.product.is_active : true,
  thumbnail_file: null,
  datasheet_file: null,
  // Dynamic visual arrays for 0-code editing
  features: Array.isArray(props.product?.features) && props.product.features.length > 0 
    ? JSON.parse(JSON.stringify(props.product.features))
    : [
        { icon: 'ShieldCheckIcon', title: 'Chính Hãng 100%', desc: 'Đầy đủ chứng nhận CO/CQ từ hãng' },
        { icon: 'BoltIcon', title: 'Độ Tin Cậy Cao', desc: 'Thiết kế chuẩn công nghiệp vận hành liên tục 24/7' },
      ],
  specs: Array.isArray(props.product?.specs) && props.product.specs.length > 0
    ? JSON.parse(JSON.stringify(props.product.specs))
    : [
        { label: 'Mã thiết bị (Model)', value: props.product?.model_code || 'TC-STANDARD' },
        { label: 'Tiêu chuẩn bảo vệ', value: 'IP67 Công Nghiệp' },
      ],
  application_scenarios: Array.isArray(props.product?.application_scenarios) && props.product.application_scenarios.length > 0
    ? JSON.parse(JSON.stringify(props.product.application_scenarios))
    : [
        'Nhà máy sản xuất, khu công nghiệp và kho vận logistics',
        'Công trường xây dựng, hạ tầng giao thông và ven biển',
      ],
  related_product_ids: Array.isArray(props.product?.related_product_ids)
    ? [...props.product.related_product_ids]
    : [],
});

// Dynamic Row Helpers
const addFeature = () => {
  form.features.push({
    icon: 'SparklesIcon',
    title: '',
    desc: '',
  });
};

const removeFeature = (index) => {
  form.features.splice(index, 1);
};

const addSpec = () => {
  form.specs.push({
    label: '',
    value: '',
  });
};

const removeSpec = (index) => {
  form.specs.splice(index, 1);
};

const addScenario = () => {
  form.application_scenarios.push('');
};

const removeScenario = (index) => {
  form.application_scenarios.splice(index, 1);
};

const submitForm = () => {
  if (isEditMode.value) {
    form.post(`/admin/products/${props.product.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        // Flash message shown automatically
      },
    });
  } else {
    form.post('/admin/products', {
      preserveScroll: true,
      onSuccess: () => {
        // Redirect handled by controller
      },
    });
  }
};
</script>

<template>
  <div class="min-h-screen bg-slate-100 font-sans text-slate-800 flex flex-col">
    <Head>
      <title>{{ isEditMode ? 'Sửa Sản Phẩm: ' + form.name : 'Thêm Mới Sản Phẩm B2B 11 Mục' }} - Toàn Cầu Tech</title>
    </Head>

    <!-- Top Navigation Header -->
    <header class="bg-[#0F294A] text-white border-b border-slate-700 sticky top-0 z-40 shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center gap-4">
            <Link href="/admin/products" class="flex items-center gap-2 text-slate-300 hover:text-white transition">
              <span class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700">←</span>
              <span class="text-xs font-bold uppercase tracking-wider hidden sm:inline">Quay Lại Danh Sách</span>
            </Link>
            <div class="h-5 w-px bg-slate-700"></div>
            <div>
              <h1 class="text-sm sm:text-base font-extrabold text-white truncate max-w-md">
                {{ isEditMode ? 'Sửa Sản Phẩm: ' + form.name : 'Thêm Mới Sản Phẩm B2B Chuẩn 11 Mục' }}
              </h1>
              <div class="text-[11px] text-cyan-400 font-mono">
                Model: {{ form.model_code || 'Chưa đặt' }}
              </div>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <a 
              v-if="isEditMode && product.slug" 
              :href="'/san-pham/' + product.slug" 
              target="_blank"
              class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 text-xs font-bold transition flex items-center gap-1.5 border border-slate-700"
            >
              <span>Xem Web ↗</span>
            </a>

            <button 
              type="button" 
              :disabled="form.processing || auth.is_viewer"
              @click="submitForm"
              class="px-5 py-2 rounded-xl bg-[#0284C7] hover:bg-cyan-600 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg disabled:opacity-50"
            >
              <svg v-if="form.processing" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
              </svg>
              <span>{{ isEditMode ? '💾 Lưu Thay Đổi' : '🚀 Thêm Sản Phẩm Mới' }}</span>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Flash notifications -->
    <div v-if="flash.success" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
      <div class="p-4 rounded-2xl bg-emerald-500 text-white text-xs font-bold shadow-md flex items-center justify-between">
        <span>✓ {{ flash.success }}</span>
        <button @click="flash.success = null" class="text-white hover:text-slate-200">✕</button>
      </div>
    </div>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
      <form @submit.prevent="submitForm" class="space-y-8">

        <!-- ======================================================== -->
        <!-- SECTION 1: NHẬN DIỆN, MODEL & HUY HIỆU BADGE (01 & 02)     -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
          <div class="border-b border-slate-100 pb-4">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#0284C7] font-mono bg-cyan-50 px-2.5 py-1 rounded-full">
              Thành Phần [01] & [02]
            </span>
            <h2 class="text-lg font-extrabold text-[#0F294A] mt-2">1. Định Danh Sản Phẩm & Nhãn B2B</h2>
            <p class="text-xs text-slate-500 mt-1">Cấu hình tên gọi chính thức, mã model phần cứng, lĩnh vực và nhãn huy hiệu xuất hiện trên đầu trang chi tiết.</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block font-bold text-slate-700 uppercase text-xs mb-1">
                Tên Sản Phẩm / Dịch Vụ <span class="text-rose-500">*</span>
              </label>
              <input 
                v-model="form.name" 
                type="text" 
                required 
                placeholder="VD: Camera Giám Sát Năng Lượng Mặt Trời Solar 4G Ven Biển IP67..." 
                class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs outline-none focus:border-[#0284C7] font-semibold text-slate-900" 
              />
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase text-xs mb-1">
                Mã Model Chính Thức [02] <span class="text-rose-500">*</span>
              </label>
              <input 
                v-model="form.model_code" 
                type="text" 
                required 
                placeholder="VD: TC-IOT-SOLAR-4G" 
                class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs outline-none focus:border-[#0284C7] font-mono font-bold text-[#0F294A]" 
              />
            </div>

            <!-- HUY HIỆU BADGE TÙY BIẾN (GIẢI QUYẾT YÊU CẦU SỬA "Sản Phẩm B2B Tiêu Chuẩn") -->
            <div>
              <label class="block font-bold text-emerald-800 uppercase text-xs mb-1 flex items-center justify-between">
                <span>Huy Hiệu Nhãn Sản Phẩm (Bên Cạnh Mã Model)</span>
                <span class="text-[10px] text-emerald-600 font-normal">Tự do chỉnh sửa</span>
              </label>
              <input 
                v-model="form.badge" 
                type="text" 
                placeholder="VD: Sản Phẩm B2B Tiêu Chuẩn, Chuyên Dụng Ven Biển IP67, Bảo Hành 24 Tháng..." 
                class="w-full rounded-xl border border-emerald-300 py-2.5 px-3.5 text-xs outline-none focus:border-emerald-600 bg-emerald-50/30 text-emerald-900 font-bold" 
              />
              <p class="text-[11px] text-slate-500 mt-1">
                💡 Đoạn chữ này sẽ xuất hiện trong nhãn xanh cạnh mã Model ngoài trang chi tiết (thay thế cho chữ mặc định "Sản Phẩm B2B Tiêu Chuẩn").
              </p>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase text-xs mb-1">
                Lĩnh Vực Cốt Lõi <span class="text-rose-500">*</span>
              </label>
              <select 
                v-model="form.sector_code" 
                required 
                class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs bg-white outline-none focus:border-[#0284C7] font-semibold"
              >
                <option value="smart_iot">smart_iot (Smart IoT & Giám Sát Không Dây)</option>
                <option value="cloud_infra">cloud_infra (Cloud & Hạ Tầng Máy Chủ)</option>
                <option value="cloud_business">cloud_business (Cloud Doanh Nghiệp SME & SI)</option>
                <option value="internet">internet (Internet Leased Line & Kết Nối)</option>
                <option value="bluecom">bluecom (Bluecom 4G & Truyền Thanh Số)</option>
                <option value="tech_services">tech_services (Dịch Vụ Thi Công & Kỹ Thuật)</option>
              </select>
            </div>

            <div class="md:col-span-2">
              <label class="block font-bold text-slate-700 uppercase text-xs mb-1">
                Danh Mục Trực Thuộc <span class="text-rose-500">*</span>
              </label>
              <select 
                v-model="form.category_id" 
                required 
                class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs bg-white outline-none focus:border-[#0284C7] font-semibold"
              >
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                  {{ cat.parent_id ? '  ↳ [Danh mục con] ' + cat.name : '★ [Trụ cột gốc] ' + cat.name }} (Lĩnh vực: {{ cat.sector_code }})
                </option>
              </select>
            </div>
          </div>
        </div>

        <!-- ======================================================== -->
        <!-- SECTION 2: MÔ TẢ TỔNG QUAN (COMPONENT [04])               -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-4">
          <div class="border-b border-slate-100 pb-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#0284C7] font-mono bg-cyan-50 px-2.5 py-1 rounded-full">
              Thành Phần [04]
            </span>
            <h2 class="text-lg font-extrabold text-[#0F294A] mt-2">2. Đoạn Mô Tả Tổng Quan (Overview B2B)</h2>
            <p class="text-xs text-slate-500 mt-1">2-3 câu định vị giá trị B2B giúp khách hàng doanh nghiệp hiểu ngay công năng và lợi thế giải pháp.</p>
          </div>

          <div>
            <textarea 
              v-model="form.overview" 
              required 
              rows="3" 
              placeholder="Giải pháp giám sát an ninh độc lập hoàn toàn không cần kéo dây điện và dây mạng. Tích hợp tấm pin năng lượng mặt trời công suất cao..." 
              class="w-full rounded-xl border border-slate-300 p-4 text-xs outline-none focus:border-[#0284C7] leading-relaxed text-slate-800"
            ></textarea>
          </div>
        </div>

        <!-- ======================================================== -->
        <!-- SECTION 3: ĐẶC ĐIỂM & TÍNH NĂNG NỔI BẬT (COMPONENT [05])  -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-[#0284C7] font-mono bg-cyan-50 px-2.5 py-1 rounded-full">
                Thành Phần [05]
              </span>
              <h2 class="text-lg font-extrabold text-[#0F294A] mt-2">3. Đặc Điểm & Tính Năng Nổi Bật (Features)</h2>
              <p class="text-xs text-slate-500 mt-1">Trình bày dạng thẻ gạch đầu dòng kèm icon nổi bật ngoài trang chi tiết.</p>
            </div>

            <button 
              type="button" 
              @click="addFeature"
              class="px-4 py-2 rounded-xl bg-cyan-50 hover:bg-cyan-100 text-[#0284C7] text-xs font-bold transition flex items-center gap-1.5 self-start"
            >
              <span>+ Thêm Tính Năng Mới</span>
            </button>
          </div>

          <div class="space-y-3">
            <div 
              v-for="(feat, fIdx) in form.features" 
              :key="fIdx"
              class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center gap-3 transition"
            >
              <div class="w-7 h-7 rounded-lg bg-[#0F294A] text-white flex items-center justify-center text-xs font-bold shrink-0">
                0{{ fIdx + 1 }}
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1 w-full">
                <div>
                  <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Tiêu Đề Tính Năng</label>
                  <input 
                    v-model="feat.title" 
                    type="text" 
                    required 
                    placeholder="VD: Độc Lập 100% Nguồn & Mạng" 
                    class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7] bg-white font-bold" 
                  />
                </div>

                <div class="sm:col-span-2">
                  <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Mô Tả Chi Tiết Tính Năng</label>
                  <input 
                    v-model="feat.desc" 
                    type="text" 
                    required 
                    placeholder="VD: Tự sạc năng lượng mặt trời và truyền dữ liệu video sắc nét qua sóng di động 4G LTE" 
                    class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7] bg-white text-slate-700" 
                  />
                </div>
              </div>

              <button 
                type="button" 
                @click="removeFeature(fIdx)" 
                class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg text-xs font-bold transition self-end sm:self-center"
                title="Xóa tính năng này"
              >
                ✕
              </button>
            </div>
          </div>
        </div>

        <!-- ======================================================== -->
        <!-- SECTION 4: THÔNG SỐ KỸ THUẬT CHI TIẾT (COMPONENT [06])    -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-[#0284C7] font-mono bg-cyan-50 px-2.5 py-1 rounded-full">
                Thành Phần [06]
              </span>
              <h2 class="text-lg font-extrabold text-[#0F294A] mt-2">4. Bảng Thông Số Kỹ Thuật Chi Tiết (Specs 2 Cột)</h2>
              <p class="text-xs text-slate-500 mt-1">Bảng thông số 2 cột (Tên thông số - Giá trị) hiển thị trang nhã trên nền trắng sạch.</p>
            </div>

            <button 
              type="button" 
              @click="addSpec"
              class="px-4 py-2 rounded-xl bg-cyan-50 hover:bg-cyan-100 text-[#0284C7] text-xs font-bold transition flex items-center gap-1.5 self-start"
            >
              <span>+ Thêm Thông Số Kỹ Thuật</span>
            </button>
          </div>

          <div class="space-y-2">
            <div 
              v-for="(spec, sIdx) in form.specs" 
              :key="sIdx"
              class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200"
            >
              <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <input 
                  v-model="spec.label" 
                  type="text" 
                  required 
                  placeholder="Tên thông số (VD: Độ phân giải, Pin lưu trữ, Chuẩn chống nước...)" 
                  class="rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7] bg-white font-medium" 
                />
                <input 
                  v-model="spec.value" 
                  type="text" 
                  required 
                  placeholder="Giá trị (VD: 4.0 Megapixel 2K, 20.000mAh LiFePO4, IP67 Ven biển...)" 
                  class="rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7] bg-white font-bold text-slate-900" 
                />
              </div>

              <button 
                type="button" 
                @click="removeSpec(sIdx)" 
                class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg text-xs font-bold transition"
                title="Xóa thông số này"
              >
                ✕
              </button>
            </div>
          </div>
        </div>

        <!-- ======================================================== -->
        <!-- SECTION 5: KỊCH BẢN & MÔI TRƯỜNG ỨNG DỤNG (COMPONENT [07]) -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-[#0284C7] font-mono bg-cyan-50 px-2.5 py-1 rounded-full">
                Thành Phần [07]
              </span>
              <h2 class="text-lg font-extrabold text-[#0F294A] mt-2">5. Kịch Bản & Môi Trường Ứng Dụng Thực Tế</h2>
              <p class="text-xs text-slate-500 mt-1">Các ví dụ địa hình và môi trường triển khai thực tế (trang trại, ao tôm, KCN, vùng ven biển...).</p>
            </div>

            <button 
              type="button" 
              @click="addScenario"
              class="px-4 py-2 rounded-xl bg-cyan-50 hover:bg-cyan-100 text-[#0284C7] text-xs font-bold transition flex items-center gap-1.5 self-start"
            >
              <span>+ Thêm Kịch Bản Ứng Dụng</span>
            </button>
          </div>

          <div class="space-y-2">
            <div 
              v-for="(scen, scIdx) in form.application_scenarios" 
              :key="scIdx"
              class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 border border-slate-200"
            >
              <span class="text-cyan-600 font-bold text-xs pl-2">✓</span>
              <input 
                v-model="form.application_scenarios[scIdx]" 
                type="text" 
                required 
                placeholder="VD: Các trang trại nuôi trồng thủy hải sản, ao tôm, lồng bè nuôi cá ven biển và hải đảo" 
                class="flex-1 rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7] bg-white text-slate-800" 
              />
              <button 
                type="button" 
                @click="removeScenario(scIdx)" 
                class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg text-xs font-bold transition"
                title="Xóa kịch bản này"
              >
                ✕
              </button>
            </div>
          </div>
        </div>

        <!-- ======================================================== -->
        <!-- SECTION 6: HÌNH ẢNH WEBP & DATASHEET PDF (03 & 08)        -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
          <div class="border-b border-slate-100 pb-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#0284C7] font-mono bg-cyan-50 px-2.5 py-1 rounded-full">
              Thành Phần [03] & [08]
            </span>
            <h2 class="text-lg font-extrabold text-[#0F294A] mt-2">6. Hình Ảnh WebP & File Datasheet Kỹ Thuật (PDF)</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Thumbnail WebP -->
            <div>
              <label class="block font-bold text-slate-700 uppercase text-xs mb-1">
                Ảnh Đại Diện Sản Phẩm (Tự động nén WebP)
              </label>
              <div v-if="product?.thumbnail" class="mb-3 flex items-center gap-3 p-3 bg-slate-50 rounded-2xl border border-slate-200">
                <img :src="product.thumbnail" :alt="product.name" class="w-16 h-16 object-cover rounded-xl border" />
                <div class="text-xs">
                  <div class="font-bold text-slate-800">Ảnh hiện tại:</div>
                  <div class="text-slate-400 font-mono text-[10px] truncate max-w-xs">{{ product.thumbnail }}</div>
                </div>
              </div>
              <input 
                type="file" 
                accept="image/*" 
                @change="e => form.thumbnail_file = e.target.files[0]" 
                class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-cyan-50 file:text-[#0284C7] hover:file:bg-cyan-100" 
              />
            </div>

            <!-- Datasheet PDF -->
            <div>
              <label class="block font-bold text-slate-700 uppercase text-xs mb-1">
                Tài Liệu Kỹ Thuật Datasheet Chính Hãng [08]
              </label>
              <div v-if="product?.datasheet_pdf_path" class="mb-3 p-3 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between text-xs">
                <div>
                  <div class="font-bold text-slate-800">Datasheet PDF hiện tại:</div>
                  <div class="text-slate-400 font-mono text-[10px]">{{ product.datasheet_pdf_path }}</div>
                </div>
                <a :href="product.datasheet_pdf_path" target="_blank" class="text-[#0284C7] font-bold hover:underline">Tải thử ↗</a>
              </div>
              <input 
                type="file" 
                accept=".pdf" 
                @change="e => form.datasheet_file = e.target.files[0]" 
                class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" 
              />
            </div>
          </div>
        </div>

        <!-- ======================================================== -->
        <!-- SECTION 7: CHÍNH SÁCH BẢO HÀNH & SLA KỸ THUẬT (09)       -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
          <div class="border-b border-slate-100 pb-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#0284C7] font-mono bg-cyan-50 px-2.5 py-1 rounded-full">
              Thành Phần [09]
            </span>
            <h2 class="text-lg font-extrabold text-[#0F294A] mt-2">7. Cam Kết Bảo Hành & Hỗ Trợ Kỹ Thuật</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block font-bold text-slate-700 uppercase text-xs mb-1">Chính Sách Bảo Hành (Tháng, 1 đổi 1)</label>
              <textarea 
                v-model="form.warranty_policy" 
                rows="2" 
                class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs outline-none focus:border-[#0284C7]"
              ></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase text-xs mb-1">Cam Kết Hỗ Trợ Kỹ Thuật (SLA, 24/7)</label>
              <textarea 
                v-model="form.support_commitment" 
                rows="2" 
                class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs outline-none focus:border-[#0284C7]"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- ======================================================== -->
        <!-- SECTION 8: GIÁ B2B, TOGGLE NỔI BẬT & TRẠNG THÁI (11)      -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
          <div class="border-b border-slate-100 pb-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#0284C7] font-mono bg-cyan-50 px-2.5 py-1 rounded-full">
              Thành Phần [11]
            </span>
            <h2 class="text-lg font-extrabold text-[#0F294A] mt-2">8. Chính Sách Giá Niêm Yết & Nút Bấm Chuyển Đổi</h2>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div>
              <label class="block font-bold text-slate-700 uppercase text-xs mb-1">Giá Niêm Yết (VNĐ)</label>
              <input 
                v-model="form.price" 
                type="number" 
                min="0" 
                placeholder="VD: 3890000 (để trống nếu thỏa thuận)" 
                class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs outline-none focus:border-[#0284C7] font-bold" 
              />
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase text-xs mb-1">Đơn Vị Tính</label>
              <input 
                v-model="form.price_unit" 
                type="text" 
                placeholder="bộ, thiết bị, tháng, năm..." 
                class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-xs outline-none focus:border-[#0284C7]" 
              />
            </div>

            <div class="flex flex-col justify-center space-y-3 pt-2">
              <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-slate-800">
                <input v-model="form.is_price_visible" type="checkbox" class="rounded text-[#0284C7] w-4 h-4" />
                <span>Hiển thị giá niêm yết (Bỏ tick sẽ hiện nút "Liên hệ")</span>
              </label>

              <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-cyan-800">
                <input v-model="form.is_featured" type="checkbox" class="rounded text-[#0284C7] w-4 h-4" />
                <span>★ Gắn cờ Nổi Bật trên Showcase Trang Chủ</span>
              </label>

              <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-emerald-800">
                <input v-model="form.is_active" type="checkbox" class="rounded text-emerald-600 w-4 h-4" />
                <span>Kích hoạt hiển thị công khai ngoài website</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Bottom Action Bar -->
        <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
          <Link href="/admin/products" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-200 transition">
            ← Hủy & Quay Lại
          </Link>

          <button 
            type="submit" 
            :disabled="form.processing || auth.is_viewer"
            class="px-8 py-3 rounded-2xl bg-[#0284C7] hover:bg-cyan-600 text-white text-sm font-black transition flex items-center gap-2 shadow-xl disabled:opacity-50"
          >
            <span v-if="form.processing">Đang lưu...</span>
            <span v-else>{{ isEditMode ? '💾 Lưu Thay Đổi Sản Phẩm' : '🚀 Thêm Sản Phẩm Mới' }}</span>
          </button>
        </div>

      </form>
    </main>
  </div>
</template>
