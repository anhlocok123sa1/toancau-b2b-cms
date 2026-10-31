<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  initialType: {
    type: String,
    default: 'consultation_quote', // consultation_quote, partner_registration, technical_service
  },
  prefilledProduct: {
    type: Object,
    default: null,
  },
  prefilledService: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['close', 'submitted']);

const currentFormType = ref(props.initialType);
const isSuccess = ref(false);
const leadResultCode = ref('');
const isCopied = ref(false);

const copyLeadCode = () => {
  if (leadResultCode.value && navigator.clipboard) {
    navigator.clipboard.writeText(leadResultCode.value);
    isCopied.value = true;
    setTimeout(() => { isCopied.value = false; }, 2000);
  }
};

const applyPrefill = () => {
  if (props.initialType) {
    currentFormType.value = props.initialType;
    form.form_type = props.initialType;
  }
  if (props.prefilledService) {
    form.interested_service = props.prefilledService;
  } else if (props.prefilledProduct) {
    form.product_id = props.prefilledProduct.id;
    form.interested_service = `${props.prefilledProduct.name}` + (props.prefilledProduct.model_code ? ` (${props.prefilledProduct.model_code})` : '');
  }
};

watch(() => props.show, (isOpen) => {
  if (isOpen) {
    applyPrefill();
  }
});

watch(() => props.initialType, (newVal) => {
  if (newVal) {
    currentFormType.value = newVal;
    form.form_type = newVal;
  }
});

const form = useForm({
  form_type: props.initialType,
  full_name: '',
  company_name: '',
  phone: '',
  email: '',
  city_province: '',
  interested_service: props.prefilledService || (props.prefilledProduct ? props.prefilledProduct.name : ''),
  product_id: props.prefilledProduct ? props.prefilledProduct.id : null,
  message: '',
  attachment: null,
  data_consent: true,
});

watch(() => props.prefilledService, (srv) => {
  if (srv) {
    form.interested_service = srv;
  }
});

watch(() => props.prefilledProduct, (prod) => {
  if (prod) {
    form.product_id = prod.id;
    if (!props.prefilledService) {
      form.interested_service = `${prod.name}` + (prod.model_code ? ` (${prod.model_code})` : '');
    }
  }
});

watch(() => currentFormType.value, (type) => {
  form.form_type = type;
});

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.attachment = file;
  }
};

const submit = () => {
  form.post('/lead-submission', {
    preserveScroll: true,
    onSuccess: (page) => {
      isSuccess.value = true;
      leadResultCode.value = page.props.flash?.lead_code || page.props.flash?.success || ('TC-LEAD-' + Math.floor(1000 + Math.random() * 9000));
      emit('submitted');
    },
  });
};

const resetAndClose = () => {
  isSuccess.value = false;
  form.reset();
  form.clearErrors();
  emit('close');
};

const formTitles = {
  consultation_quote: 'Yêu Cầu Báo Giá & Tư Vấn Giải Pháp',
  partner_registration: 'Đăng Ký Đối Tác & Đại Lý Phân Phối',
  technical_service: 'Yêu Cầu Dịch Vụ Kỹ Thuật & Khảo Sát Hiện Trường',
};
</script>

<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" @click="resetAndClose"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
      <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-100">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#0F294A] to-[#183F70] px-6 py-5 text-white flex items-center justify-between">
          <div>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#EA580C] text-white">
                Cam Kết SLA 15-30 Phút
              </span>
              <span class="text-xs text-cyan-200">Nghị định 13/2023/NĐ-CP</span>
            </div>
            <h3 class="text-xl font-bold mt-1 tracking-tight" id="modal-title">
              {{ formTitles[currentFormType] }}
            </h3>
          </div>
          <button @click="resetAndClose" type="button" class="text-slate-300 hover:text-white rounded-lg p-1.5 transition">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Success Screen -->
        <div v-if="isSuccess" class="p-8 text-center space-y-4">
          <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 shadow-inner">
            <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
          </div>

          <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold mb-2">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
              <span>SLA Phản Hồi: Tối Đa 15 - 30 Phút</span>
            </div>
            <h4 class="text-2xl font-black text-slate-900 tracking-tight">Tiếp Nhận Yêu Cầu Thành Công!</h4>
            <p class="text-slate-600 max-w-md mx-auto text-xs sm:text-sm leading-relaxed mt-2">
              Hệ thống Toàn Cầu Technology đã ghi nhận hồ sơ và điều phối ngay tới Kỹ sư chuyên trách theo tiêu chuẩn bảo mật Nghị định 13/2023/NĐ-CP.
            </p>
          </div>

          <!-- Code Box -->
          <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-5 max-w-md mx-auto text-left shadow-sm space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-xs text-slate-500 uppercase tracking-wider font-bold">Mã tra cứu tiến độ SLA:</span>
              <button 
                type="button" 
                @click="copyLeadCode"
                class="text-xs text-[#0284C7] hover:text-[#0369A1] font-semibold flex items-center gap-1 transition"
              >
                <svg v-if="!isCopied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span v-if="isCopied" class="text-emerald-600 font-bold">✓ Đã sao chép</span>
                <span v-else>Sao chép mã</span>
              </button>
            </div>
            <div class="text-xl font-mono font-extrabold text-[#0F294A] bg-white px-3.5 py-2 rounded-xl border border-slate-200 flex items-center justify-between">
              <span>{{ leadResultCode }}</span>
              <span class="text-[11px] font-sans font-medium bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md">Đã Ghi Nhận</span>
            </div>
            <div class="text-xs text-slate-500 flex items-center gap-2 pt-1 border-t border-slate-200/60">
              <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Email & SMS xác nhận đã được gửi tự động tới hộp thư của Quý khách.</span>
            </div>
          </div>

          <div class="pt-2 flex items-center justify-center gap-3">
            <button 
              type="button" 
              @click="resetAndClose" 
              class="px-8 py-3 bg-[#0F294A] hover:bg-[#183F70] text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-lg shadow-navy/20"
            >
              Đóng Cửa Sổ
            </button>
            <a 
              href="tel:19006868"
              class="px-6 py-3 border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs sm:text-sm rounded-xl transition flex items-center gap-2"
            >
              <svg class="w-4 h-4 text-[#EA580C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              <span>Hotline (1900 6868)</span>
            </a>
          </div>
        </div>

        <!-- Form Body -->
        <form v-else @submit.prevent="submit" class="p-6 space-y-4 text-left">
          <!-- Form Tabs -->
          <div class="flex rounded-lg bg-slate-100 p-1 mb-4 text-xs font-semibold">
            <button 
              type="button" 
              @click="currentFormType = 'consultation_quote'" 
              :class="currentFormType === 'consultation_quote' ? 'bg-white text-[#0F294A] shadow' : 'text-slate-600 hover:text-slate-900'"
              class="flex-1 py-2 rounded-md transition text-center"
            >
              Tư Vấn / Báo Giá
            </button>
            <button 
              type="button" 
              @click="currentFormType = 'partner_registration'" 
              :class="currentFormType === 'partner_registration' ? 'bg-white text-[#0F294A] shadow' : 'text-slate-600 hover:text-slate-900'"
              class="flex-1 py-2 rounded-md transition text-center"
            >
              Đăng Ký Đại Lý
            </button>
            <button 
              type="button" 
              @click="currentFormType = 'technical_service'" 
              :class="currentFormType === 'technical_service' ? 'bg-white text-[#0F294A] shadow' : 'text-slate-600 hover:text-slate-900'"
              class="flex-1 py-2 rounded-md transition text-center"
            >
              Dịch Vụ Kỹ Thuật
            </button>
          </div>

          <!-- Common inputs -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
                Họ và tên người liên hệ <span class="text-rose-500">*</span>
              </label>
              <input 
                v-model="form.full_name" 
                type="text" 
                required 
                placeholder="Nguyễn Văn A" 
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none"
              />
              <div v-if="form.errors.full_name" class="text-xs text-rose-500 mt-1">{{ form.errors.full_name }}</div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
                Số điện thoại liên hệ <span class="text-rose-500">*</span>
              </label>
              <input 
                v-model="form.phone" 
                type="tel" 
                required 
                placeholder="0912 345 678" 
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none"
              />
              <div v-if="form.errors.phone" class="text-xs text-rose-500 mt-1">{{ form.errors.phone }}</div>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
                Tên Doanh nghiệp / Đơn vị <span v-if="currentFormType === 'partner_registration'" class="text-rose-500">*</span>
              </label>
              <input 
                v-model="form.company_name" 
                type="text" 
                :required="currentFormType === 'partner_registration'"
                placeholder="Công ty CP / TNHH..." 
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
                Email doanh nghiệp <span v-if="currentFormType !== 'technical_service'" class="text-rose-500">*</span>
              </label>
              <input 
                v-model="form.email" 
                type="email" 
                :required="currentFormType !== 'technical_service'"
                placeholder="contact@company.vn" 
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none"
              />
              <div v-if="form.errors.email" class="text-xs text-rose-500 mt-1">{{ form.errors.email }}</div>
            </div>
          </div>

          <!-- Dynamic fields per form type -->
          <div v-if="currentFormType === 'partner_registration'" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
                Tỉnh / Thành phố hoạt động <span class="text-rose-500">*</span>
              </label>
              <input 
                v-model="form.city_province" 
                type="text" 
                required 
                placeholder="Hà Nội, TP.HCM, Cần Thơ..." 
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
                Lĩnh vực đăng ký đại lý <span class="text-rose-500">*</span>
              </label>
              <select 
                v-model="form.interested_service" 
                required 
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none bg-white"
              >
                <option value="">-- Chọn lĩnh vực hợp tác --</option>
                <option value="Đại lý Phân phối Bluecom 4G & Truyền thanh số">Đại lý Phân phối Bluecom 4G & Truyền thanh số</option>
                <option value="Đại lý Thiết bị Smart IoT & Solar Camera">Đại lý Thiết bị Smart IoT & Solar Camera</option>
                <option value="Đối tác Triển khai Cloud & Dedicated Server">Đối tác Triển khai Cloud & Dedicated Server</option>
                <option value="Đại lý Kênh Internet Doanh Nghiệp & Leased Line">Đại lý Kênh Internet Doanh Nghiệp & Leased Line</option>
                <option value="Đối tác Thi công & Dịch vụ Kỹ thuật Mặt đất">Đối tác Thi công & Dịch vụ Kỹ thuật Mặt đất</option>
              </select>
            </div>
          </div>

          <div v-else-if="currentFormType === 'technical_service'" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
                Địa điểm / Công trình khảo sát <span class="text-rose-500">*</span>
              </label>
              <input 
                v-model="form.city_province" 
                type="text" 
                required 
                placeholder="KCN Sóng Thần, Tòa nhà Bitexco..." 
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
                Phân loại dịch vụ yêu cầu <span class="text-rose-500">*</span>
              </label>
              <select 
                v-model="form.interested_service" 
                required 
                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none bg-white"
              >
                <option value="">-- Chọn phân loại dịch vụ --</option>
                <option value="Thi công & Lắp đặt hệ thống mới">Thi công & Lắp đặt hệ thống mới</option>
                <option value="Bảo trì định kỳ hạ tầng mạng & Server">Bảo trì định kỳ hạ tầng mạng & Server</option>
                <option value="Chẩn đoán & Sửa chữa sự cố khẩn cấp">Chẩn đoán & Sửa chữa sự cố khẩn cấp</option>
                <option value="Khảo sát hiện trường & Lập phương án kỹ thuật">Khảo sát hiện trường & Lập phương án kỹ thuật</option>
              </select>
            </div>
          </div>

          <div v-else>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
              Sản phẩm / Dịch vụ quan tâm
            </label>
            <input 
              v-model="form.interested_service" 
              type="text" 
              placeholder="VD: Dedicated Server AMD EPYC, Thuê dải IP /24, Leased Line 100Mbps..." 
              class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
              {{ currentFormType === 'technical_service' ? 'Mô tả chi tiết sự cố / yêu cầu kỹ thuật' : 'Yêu cầu cụ thể hoặc ghi chú' }}
            </label>
            <textarea 
              v-model="form.message" 
              rows="3" 
              :placeholder="currentFormType === 'technical_service' ? 'Mô tả hiện trạng, thời điểm phát sinh sự cố...' : 'Số lượng, quy mô triển khai, thời gian mong muốn nhận báo giá...'"
              class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none"
            ></textarea>
          </div>

          <!-- Attachment Upload for Partner & Technical -->
          <div v-if="currentFormType !== 'consultation_quote'">
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
              {{ currentFormType === 'partner_registration' ? 'Đính kèm Hồ sơ năng lực (Profile công ty / Giấy ĐKKD - Tối đa 10MB)' : 'Đính kèm Ảnh chụp lỗi / Bản vẽ mặt bằng công trình (Tối đa 10MB)' }}
            </label>
            <input 
              type="file" 
              @change="handleFileChange"
              :accept="currentFormType === 'partner_registration' ? '.pdf,.doc,.docx,.zip' : 'image/*,.pdf'"
              class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-[#0F294A] hover:file:bg-slate-200 cursor-pointer"
            />
          </div>

          <!-- Decree 13/2023/ND-CP Personal Data Protection Consent -->
          <div class="pt-2">
            <label class="flex items-start gap-2.5 cursor-pointer">
              <input 
                v-model="form.data_consent" 
                type="checkbox" 
                required 
                class="mt-1 h-4 w-4 rounded border-slate-300 text-[#0284C7] focus:ring-[#0284C7]"
              />
              <span class="text-xs text-slate-600 leading-relaxed">
                Tôi đồng ý cho phép Toàn Cầu Technology thu thập và xử lý dữ liệu cá nhân theo <strong class="text-slate-800">Nghị định 13/2023/NĐ-CP</strong> để phục vụ tư vấn báo giá và bảo mật thông tin liên hệ.
              </span>
            </label>
            <div v-if="form.errors.data_consent" class="text-xs text-rose-500 mt-1">{{ form.errors.data_consent }}</div>
          </div>

          <!-- Action Buttons -->
          <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
            <button 
              type="button" 
              @click="resetAndClose" 
              class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
            >
              Hủy bỏ
            </button>
            <button 
              type="submit" 
              :disabled="form.processing"
              class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-sm font-bold shadow-lg shadow-[#EA580C]/25 transition disabled:opacity-50"
            >
              <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ form.processing ? 'Đang gửi...' : 'Gửi Yêu Cầu (SLA 30 Phút)' }}</span>
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</template>
