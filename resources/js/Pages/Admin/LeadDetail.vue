<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
  lead: {
    type: Object,
    required: true,
  },
  salesUsers: {
    type: Array,
    default: () => [],
  },
  statusLabels: {
    type: Object,
    default: () => ({}),
  },
  formTypes: {
    type: Object,
    default: () => ({}),
  },
});

const page = usePage();
const auth = computed(() => page.props.auth?.user || {});

// Pipeline steps definition
const pipelineSteps = [
  { key: '1_new', label: 'Mới Tiếp Nhận', stepNum: 1 },
  { key: '2_acknowledged', label: 'Đã Tiếp Nhận SLA', stepNum: 2 },
  { key: '3_in_progress', label: 'Tư Vấn & Khảo Sát', stepNum: 3 },
  { key: '4_quoted', label: 'Đã Gửi Báo Giá', stepNum: 4 },
  { key: '5_negotiating', label: 'Đàm Phán Hợp Đồng', stepNum: 5 },
  { key: '6_closed_won', label: 'Chốt Hợp Đồng', stepNum: 6 },
];

const currentStepIndex = computed(() => {
  const currentKey = props.lead.status;
  if (currentKey === '5_closed_won' || currentKey === '6_closed_won') return 5;
  if (currentKey === '5_closed_lost' || currentKey === '7_closed_lost') return -1;
  const idx = pipelineSteps.findIndex(s => s.key === currentKey);
  return idx !== -1 ? idx : 0;
});

// Form 1: Customer Profile Form
const customerForm = useForm({
  full_name: props.lead.full_name || '',
  company_name: props.lead.company_name || '',
  phone: props.lead.phone || '',
  email: props.lead.email || '',
  city_province: props.lead.city_province || '',
  interested_service: props.lead.interested_service || '',
  message: props.lead.message || '',
  assigned_to: props.lead.assigned_to || '',
  status: props.lead.status || '1_new',
  closed_reason: props.lead.closed_reason || '',
});

const submitCustomerUpdate = () => {
  customerForm.put(`/admin/leads/${props.lead.id}`, {
    preserveScroll: true,
  });
};

// Form 2: Contract & Deal Form
const contractForm = useForm({
  contract_number: props.lead.contract_number || '',
  contract_value: props.lead.contract_value || '',
  contract_term: props.lead.contract_term || '12 tháng',
  contract_payment_terms: props.lead.contract_payment_terms || 'Thanh toán 50% khi ký HĐ, 50% khi bàn giao nghiệm thu',
  contract_signed_at: props.lead.contract_signed_at ? props.lead.contract_signed_at.substring(0, 10) : '',
  contract_notes: props.lead.contract_notes || '',
  status: props.lead.status,
  contract_file: null,
});

const handleContractFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    contractForm.contract_file = file;
  }
};

const submitContractUpdate = () => {
  contractForm.post(`/admin/leads/${props.lead.id}/contract`, {
    preserveScroll: true,
  });
};

// Form 3: Activity Log Note Form
const noteForm = useForm({
  note: '',
  action_type: 'Cuộc gọi tư vấn',
});

const submitAddNote = () => {
  if (!noteForm.note.trim()) return;
  const fullNote = `[${noteForm.action_type}] ${noteForm.note}`;
  noteForm.transform(() => ({ note: fullNote })).post(`/admin/leads/${props.lead.id}/notes`, {
    preserveScroll: true,
    onSuccess: () => {
      noteForm.reset('note');
    },
  });
};

// Quick Status Switch
const statusSwitchForm = useForm({
  status: props.lead.status,
  closed_reason: '',
  note: '',
});

const changeStatus = (newStatus) => {
  let reason = '';
  if (newStatus === '7_closed_lost' || newStatus === '5_closed_lost') {
    reason = prompt('Vui lòng nhập lý do không thành công (bắt buộc):');
    if (!reason) return;
  }
  statusSwitchForm.status = newStatus;
  statusSwitchForm.closed_reason = reason;
  statusSwitchForm.post(`/admin/leads/${props.lead.id}/status`, {
    preserveScroll: true,
  });
};

const formatVnd = (val) => {
  if (!val) return '0 ₫';
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
};

const formatDateTime = (dt) => {
  if (!dt) return 'N/A';
  const d = new Date(dt);
  return d.toLocaleString('vi-VN', {
    hour: '2-digit',
    minute: '2-digit',
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
};

const isSlaBreached = computed(() => {
  if (['6_closed_won', '5_closed_won', '7_closed_lost', '5_closed_lost'].includes(props.lead.status)) {
    return false;
  }
  if (!props.lead.sla_due_at) return false;
  return new Date(props.lead.sla_due_at) < new Date();
});
</script>

<template>
  <div class="min-h-screen bg-slate-100 font-sans text-slate-800 pb-20">
    <Head>
      <title>Chi Tiết & Quản Lý Hợp Đồng Lead [{{ lead.code }}] - Toàn Cầu Tech</title>
    </Head>

    <!-- Top Admin Header -->
    <header class="bg-[#0F294A] text-white border-b border-slate-700 sticky top-0 z-40 shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center gap-4">
            <Link 
              href="/admin/leads" 
              class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              <span>Quay Lại Danh Sách Lead</span>
            </Link>
            <div class="h-5 w-px bg-slate-700 hidden sm:block"></div>
            <div class="hidden sm:flex items-center gap-2">
              <span class="text-xs text-slate-400 font-mono">MÃ YÊU CẦU:</span>
              <span class="text-sm font-black font-mono text-cyan-400">{{ lead.code }}</span>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <span class="text-xs text-slate-300">
              Nhân sự: <strong class="text-white">{{ auth.name }}</strong>
            </span>
            <Link 
              href="/admin/dashboard" 
              class="text-xs text-cyan-300 hover:underline"
            >
              Bàn Quản Trị
            </Link>
          </div>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-6">

      <!-- Flash Message Banner -->
      <div v-if="$page.props.flash?.success" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <span>{{ $page.props.flash.success }}</span>
        </div>
      </div>

      <!-- Lead Summary Header Card -->
      <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-slate-100">
          <div>
            <div class="flex flex-wrap items-center gap-2.5 mb-2">
              <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-cyan-100 text-[#0284C7]">
                {{ formTypes[lead.form_type] || lead.form_type }}
              </span>
              <span 
                :class="isSlaBreached ? 'bg-rose-100 text-rose-700 border border-rose-300 animate-pulse' : 'bg-emerald-100 text-emerald-800'"
                class="px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5"
              >
                <span :class="isSlaBreached ? 'bg-rose-600' : 'bg-emerald-500'" class="w-2 h-2 rounded-full"></span>
                <span>{{ isSlaBreached ? 'QUÁ HẠN SLA 30 PHÚT' : 'ĐÚNG HẠN SLA (15 - 30P)' }}</span>
              </span>
              <span class="text-xs text-slate-400 font-mono">
                Tiếp nhận: {{ formatDateTime(lead.created_at) }}
              </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-[#0F294A] tracking-tight">
              {{ lead.company_name ? lead.company_name + ' – ' + lead.full_name : lead.full_name }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 flex items-center gap-2">
              <span class="font-semibold text-slate-900">Quan tâm:</span>
              <span class="text-[#0284C7] font-bold">{{ lead.interested_service || 'Tư vấn giải pháp công nghệ tổng thể' }}</span>
              <span v-if="lead.product" class="text-slate-400">({{ lead.product.name }} - {{ lead.product.model_code }})</span>
            </p>
          </div>

          <!-- Quick Actions & Contact Toolbar -->
          <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a 
              :href="'tel:' + lead.phone" 
              class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              <span>Gọi: {{ lead.phone }}</span>
            </a>

            <a 
              :href="'https://zalo.me/' + lead.phone.replace(/[^0-9]/g, '')" 
              target="_blank" 
              rel="noopener noreferrer"
              class="px-4 py-2.5 rounded-xl bg-[#0284C7] hover:bg-[#0369A1] text-white font-bold text-xs shadow-md transition flex items-center gap-1.5"
            >
              <span>Chat Zalo</span>
            </a>

            <a 
              :href="'mailto:' + lead.email" 
              class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <span>Gửi Mail</span>
            </a>
          </div>
        </div>

        <!-- 6-Stage Visual Sales Pipeline Stepper -->
        <div class="pt-6">
          <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4 flex items-center justify-between">
            <span>Tiến Trình Chốt Hợp Đồng B2B (6 Giai Đoạn)</span>
            <span class="text-[#0284C7] font-semibold">Trạng thái hiện tại: {{ statusLabels[lead.status] || lead.status }}</span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
            <button
              v-for="(step, idx) in pipelineSteps"
              :key="step.key"
              type="button"
              @click="changeStatus(step.key)"
              :class="[
                idx <= currentStepIndex && currentStepIndex !== -1
                  ? 'bg-[#0F294A] text-white border-[#0F294A] shadow'
                  : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100',
                lead.status === step.key ? 'ring-2 ring-offset-2 ring-[#0284C7]' : ''
              ]"
              class="p-3 rounded-2xl border text-left transition relative flex flex-col justify-between"
            >
              <div class="flex items-center justify-between text-[11px] mb-1 font-mono font-bold">
                <span>0{{ step.stepNum }}</span>
                <span v-if="idx < currentStepIndex">✓</span>
                <span v-else-if="idx === currentStepIndex" class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
              </div>
              <div class="text-xs font-bold leading-tight">
                {{ step.label }}
              </div>
            </button>
          </div>

          <!-- Fail / Cancel Button -->
          <div class="mt-3 flex items-center justify-end gap-3 text-xs">
            <span v-if="lead.status === '7_closed_lost' || lead.status === '5_closed_lost'" class="text-rose-600 font-bold">
              Yêu cầu đã đánh dấu Không Thành Công (Lý do: {{ lead.closed_reason || 'Không rõ' }})
            </span>
            <button
              v-else
              type="button"
              @click="changeStatus('7_closed_lost')"
              class="text-slate-400 hover:text-rose-600 font-medium transition"
            >
              Đánh dấu Không Thành Công / Hủy Deal
            </button>
          </div>
        </div>
      </div>

      <!-- Main 2-Column Content Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left Column: Core Workflows (8/12) -->
        <div class="lg:col-span-8 space-y-6">

          <!-- CARD 1: CONTRACT & DEAL CLOSING (Crucial B2B Feature) -->
          <div class="bg-white rounded-3xl border-2 border-cyan-500/30 p-6 sm:p-8 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">
                  <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div>
                  <h2 class="text-lg font-black text-[#0F294A]">Thông Tin Hợp Đồng & Chốt Thương Thảo</h2>
                  <p class="text-xs text-slate-500">Quản lý số hợp đồng, giá trị nghiệm thu, chu kỳ thanh toán và tệp scan chính thức</p>
                </div>
              </div>

              <span 
                v-if="lead.contract_signed_at || lead.status === '6_closed_won' || lead.status === '5_closed_won'" 
                class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1"
              >
                <span>✓ ĐÃ KÝ HỢP ĐỒNG</span>
              </span>
              <span v-else class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                Đang Thương Thảo
              </span>
            </div>

            <form @submit.prevent="submitContractUpdate" class="space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Số / Mã Hợp Đồng (Contract No.)
                  </label>
                  <input
                    v-model="contractForm.contract_number"
                    type="text"
                    placeholder="VD: HD-2026-TC-089"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold text-slate-900 focus:border-[#0284C7] focus:ring-1 focus:ring-[#0284C7] outline-none"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Giá Trị Hợp Đồng (VNĐ)
                  </label>
                  <div class="relative">
                    <input
                      v-model="contractForm.contract_value"
                      type="number"
                      step="1000"
                      placeholder="VD: 150000000"
                      class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold text-emerald-700 focus:border-[#0284C7] focus:ring-1 focus:ring-[#0284C7] outline-none"
                    />
                    <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-bold">
                      {{ formatVnd(contractForm.contract_value) }}
                    </span>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Thời Hạn Hợp Đồng
                  </label>
                  <input
                    v-model="contractForm.contract_term"
                    type="text"
                    placeholder="VD: 12 tháng, 24 tháng..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:border-[#0284C7] outline-none"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Ngày Ký Kết Chính Thức
                  </label>
                  <input
                    v-model="contractForm.contract_signed_at"
                    type="date"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:border-[#0284C7] outline-none bg-white"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Chuyển Trạng Thái Sau Lưu
                  </label>
                  <select
                    v-model="contractForm.status"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:border-[#0284C7] outline-none bg-white font-semibold"
                  >
                    <option value="4_quoted">Đã Gửi Báo Giá</option>
                    <option value="5_negotiating">Đang Đàm Phán Hợp Đồng</option>
                    <option value="6_closed_won">Chốt Hợp Đồng Thành Công</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                  Điều Khoản & Lộ Trình Thanh Toán
                </label>
                <input
                  v-model="contractForm.contract_payment_terms"
                  type="text"
                  placeholder="VD: Đợt 1 tạm ứng 50% khi ký HĐ, Đợt 2 thanh toán 50% sau nghiệm thu bàn giao..."
                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:border-[#0284C7] outline-none"
                />
              </div>

              <!-- Contract Attachment Upload / Download -->
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                  Tệp Hợp Đồng Ký Kết (PDF / DOCX <= 20MB)
                </label>
                
                <div v-if="lead.contract_attachment_path" class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between mb-2">
                  <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-800">{{ lead.contract_attachment_name || 'Tệp hợp đồng đã lưu' }}</span>
                  </div>
                  <a
                    :href="'/admin/leads/' + lead.id + '/contract-download'"
                    class="px-3 py-1.5 rounded-lg bg-[#0284C7] text-white font-bold text-xs hover:bg-[#0369A1] transition shadow-sm"
                  >
                    Tải Về Hợp Đồng
                  </a>
                </div>

                <input
                  type="file"
                  @change="handleContractFileChange"
                  accept=".pdf,.doc,.docx,.zip"
                  class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0F294A] file:text-white hover:file:bg-[#183F70]"
                />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                  Ghi Chú Phạm Vi Triển Khai & Cam Kết SLA Riêng
                </label>
                <textarea
                  v-model="contractForm.contract_notes"
                  rows="2"
                  placeholder="Ghi chú thêm về thiết bị dự phòng, địa điểm bàn giao, đầu mối IT phụ trách..."
                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:border-[#0284C7] outline-none"
                ></textarea>
              </div>

              <div class="pt-2 flex items-center justify-end">
                <button
                  type="submit"
                  :disabled="contractForm.processing"
                  class="px-6 py-2.5 rounded-xl bg-[#0284C7] hover:bg-[#0369A1] text-white font-bold text-xs sm:text-sm shadow-md transition disabled:opacity-50"
                >
                  {{ contractForm.processing ? 'Đang Lưu...' : 'Lưu Thông Tin Hợp Đồng' }}
                </button>
              </div>
            </form>
          </div>

          <!-- CARD 2: CUSTOMER PROFILE & REQUIREMENT DETAILS -->
          <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                  <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
                <div>
                  <h2 class="text-lg font-black text-[#0F294A]">Hồ Sơ Yêu Cầu & Thông Tin Khách Hàng</h2>
                  <p class="text-xs text-slate-500">Xem và hiệu chỉnh chi tiết thông tin liên hệ, nhu cầu và ghi chú</p>
                </div>
              </div>
            </div>

            <form @submit.prevent="submitCustomerUpdate" class="space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Họ Tên Khách Hàng <span class="text-rose-500">*</span>
                  </label>
                  <input
                    v-model="customerForm.full_name"
                    type="text"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:border-[#0284C7] outline-none"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Công Ty / Tổ Chức Doanh Nghiệp
                  </label>
                  <input
                    v-model="customerForm.company_name"
                    type="text"
                    placeholder="Công ty CP / TNHH..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:border-[#0284C7] outline-none"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Số Điện Thoại <span class="text-rose-500">*</span>
                  </label>
                  <input
                    v-model="customerForm.phone"
                    type="text"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-mono text-slate-900 focus:border-[#0284C7] outline-none"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Email Liên Hệ <span class="text-rose-500">*</span>
                  </label>
                  <input
                    v-model="customerForm.email"
                    type="email"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:border-[#0284C7] outline-none"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Tỉnh / Thành Phố / Công Trình
                  </label>
                  <input
                    v-model="customerForm.city_province"
                    type="text"
                    placeholder="Hà Nội, TP.HCM..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:border-[#0284C7] outline-none"
                  />
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                  Sản Phẩm / Dịch Vụ Khách Hàng Quan Tâm
                </label>
                <input
                  v-model="customerForm.interested_service"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-[#0284C7] focus:border-[#0284C7] outline-none"
                />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                  Nội Dung Yêu Cầu / Sự Cố Ban Đầu
                </label>
                <textarea
                  v-model="customerForm.message"
                  rows="3"
                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:border-[#0284C7] outline-none"
                ></textarea>
              </div>

              <!-- Customer Initial Upload File -->
              <div v-if="lead.attachment_path" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                <div>
                  <div class="text-xs font-bold text-slate-700">Tệp Hồ Sơ Đính Kèm Ban Đầu (Khách Gửi)</div>
                  <div class="text-[11px] text-slate-500 font-mono">{{ lead.attachment_name }}</div>
                </div>
                <a
                  :href="'/admin/leads/' + lead.id + '/download'"
                  class="px-3 py-1.5 rounded-lg bg-[#0F294A] text-white font-bold text-xs hover:bg-[#183F70] transition shadow-sm"
                >
                  Tải Tệp Đính Kèm
                </a>
              </div>

              <div class="pt-2 flex items-center justify-end">
                <button
                  type="submit"
                  :disabled="customerForm.processing"
                  class="px-6 py-2.5 rounded-xl bg-[#0F294A] hover:bg-[#183F70] text-white font-bold text-xs sm:text-sm shadow-md transition disabled:opacity-50"
                >
                  {{ customerForm.processing ? 'Đang Lưu...' : 'Cập Nhật Hồ Sơ Khách Hàng' }}
                </button>
              </div>
            </form>
          </div>

          <!-- CARD 3: WORK HISTORY & ACTIVITY LOG TIMELINE -->
          <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                  <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h2 class="text-lg font-black text-[#0F294A]">Nhật Ký Quá Trình Làm Việc & Hoạt Động</h2>
                  <p class="text-xs text-slate-500">Lưu vết toàn bộ trao đổi kỹ thuật, lịch hẹn khảo sát và chuyển giao</p>
                </div>
              </div>
            </div>

            <!-- Add Note Form -->
            <form @submit.prevent="submitAddNote" class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
              <div class="flex flex-col sm:flex-row items-center gap-3">
                <select
                  v-model="noteForm.action_type"
                  class="w-full sm:w-48 px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 bg-white outline-none"
                >
                  <option value="Cuộc gọi tư vấn">📞 Cuộc gọi tư vấn</option>
                  <option value="Họp trực tuyến">💻 Họp trực tuyến</option>
                  <option value="Khảo sát hiện trường">📍 Khảo sát hiện trường</option>
                  <option value="Gửi email báo giá">✉️ Gửi email báo giá</option>
                  <option value="Gặp gỡ chốt hợp đồng">🤝 Gặp gỡ chốt HĐ</option>
                  <option value="Ghi chú nội bộ">📝 Ghi chú nội bộ</option>
                </select>

                <input
                  v-model="noteForm.note"
                  type="text"
                  placeholder="Ghi lại tóm tắt nội dung cuộc gọi hoặc thỏa thuận kỹ thuật..."
                  required
                  class="flex-1 w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs text-slate-900 focus:border-[#0284C7] outline-none"
                />

                <button
                  type="submit"
                  :disabled="noteForm.processing"
                  class="w-full sm:w-auto px-5 py-2 rounded-xl bg-[#0F294A] hover:bg-[#183F70] text-white font-bold text-xs shadow transition shrink-0"
                >
                  Thêm Nhật Ký
                </button>
              </div>
            </form>

            <!-- Timeline List -->
            <div class="space-y-4 pt-2">
              <div v-if="!lead.notes || lead.notes.length === 0" class="text-center py-8 text-xs text-slate-400">
                Chưa có nhật ký hoạt động nào được ghi nhận.
              </div>
              <div 
                v-for="(n, idx) in [...(lead.notes || [])].reverse()" 
                :key="idx" 
                class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-100 shadow-sm"
              >
                <div class="w-8 h-8 rounded-full bg-cyan-100 text-[#0284C7] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                  {{ idx + 1 }}
                </div>
                <div class="flex-1 space-y-1">
                  <div class="flex flex-wrap items-center justify-between text-xs">
                    <span class="font-bold text-slate-900">{{ n.actor || 'Hệ Thống' }}</span>
                    <span class="text-slate-400 font-mono text-[11px]">{{ formatDateTime(n.time) }}</span>
                  </div>
                  <p class="text-xs text-slate-700 leading-relaxed">
                    {{ n.text }}
                  </p>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Right Column: Meta & Actions (4/12) -->
        <div class="lg:col-span-4 space-y-6">

          <!-- Personnel Assignment -->
          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-black text-[#0F294A] uppercase tracking-wider pb-2 border-b border-slate-100">
              Phân Công Nhân Sự
            </h3>
            <div>
              <label class="block text-xs font-bold text-slate-600 mb-1">Nhân viên phụ trách Deal này:</label>
              <select
                v-model="customerForm.assigned_to"
                @change="submitCustomerUpdate"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-900 bg-white outline-none focus:border-[#0284C7]"
              >
                <option value="">-- Chưa phân công --</option>
                <option v-for="u in salesUsers" :key="u.id" :value="u.id">
                  {{ u.name }} ({{ u.email }})
                </option>
              </select>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500 leading-relaxed">
              Nhân sự được phân công sẽ nhận thông báo giám sát cam kết SLA phản hồi trong 15-30 phút và quản lý xuyên suốt quá trình đàm phán hợp đồng.
            </div>
          </div>

          <!-- Compliance & SLA Card -->
          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-black text-[#0F294A] uppercase tracking-wider pb-2 border-b border-slate-100">
              Kiểm Soát SLA & Pháp Lý
            </h3>

            <div class="space-y-3 text-xs">
              <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="text-slate-500">Mã Lead:</span>
                <span class="font-mono font-bold text-[#0F294A]">{{ lead.code }}</span>
              </div>
              <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="text-slate-500">Thời hạn SLA 30p:</span>
                <span :class="isSlaBreached ? 'text-rose-600 font-bold' : 'text-emerald-600 font-bold'" class="font-mono">
                  {{ formatDateTime(lead.sla_due_at) }}
                </span>
              </div>
              <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="text-slate-500">Đồng ý dữ liệu NĐ 13:</span>
                <span class="text-emerald-600 font-bold">✓ Đã xác nhận</span>
              </div>
              <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="text-slate-500">Địa chỉ IP khách:</span>
                <span class="font-mono text-slate-700">{{ lead.ip_address || '127.0.0.1' }}</span>
              </div>
              <div>
                <span class="text-slate-500 block mb-1">Trình duyệt / Thiết bị:</span>
                <div class="text-[11px] text-slate-600 font-mono bg-slate-50 p-2 rounded-lg break-all">
                  {{ lead.user_agent || 'Mozilla/5.0' }}
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>
</template>
