<script setup>
import { ref } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  plans: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['close', 'select-plan']);

const isYearly = ref(true);

const formatVnd = (amount) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
};

const selectPlan = (plan) => {
  emit('select-plan', plan);
};
</script>

<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="vps-modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" @click="emit('close')"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
      <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full max-w-5xl border border-slate-100">
        
        <!-- Header -->
        <div class="bg-[#0F294A] px-6 py-6 text-white flex items-center justify-between border-b border-slate-700">
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#0284C7] bg-[#0284C7]/15 px-3 py-1 rounded-full">
              Bảng So Sánh Cấu Hình Trực Quan
            </span>
            <h3 class="text-2xl font-extrabold mt-2 tracking-tight" id="vps-modal-title">
              Ma Trận Cấu Hình Cloud VPS NVMe Toàn Cầu
            </h3>
            <p class="text-slate-300 text-xs mt-1">
              Hạ tầng điện toán đám mây AMD EPYC, 100% ổ cứng Pure Enterprise NVMe tại Data Center Tier 3.
            </p>
          </div>
          <button @click="emit('close')" type="button" class="text-slate-300 hover:text-white rounded-lg p-2 transition">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Billing Period Toggle -->
        <div class="bg-slate-50 px-6 py-4 flex items-center justify-center gap-4 border-b border-slate-200">
          <span :class="!isYearly ? 'font-bold text-slate-900' : 'text-slate-500'" class="text-sm">Thanh toán theo Tháng</span>
          <button 
            type="button" 
            @click="isYearly = !isYearly"
            class="relative inline-flex h-7 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-[#0284C7]"
          >
            <span 
              :class="isYearly ? 'translate-x-7' : 'translate-x-0'" 
              class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
            />
          </button>
          <span :class="isYearly ? 'font-bold text-slate-900' : 'text-slate-500'" class="text-sm flex items-center gap-1.5">
            Thanh toán theo Năm 
            <span class="bg-[#EA580C] text-white text-[11px] font-extrabold px-2 py-0.5 rounded-full shadow-sm">
              Tiết Kiệm 15%
            </span>
          </span>
        </div>

        <!-- Plans Comparison Grid -->
        <div class="p-6 overflow-x-auto">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 min-w-[700px]">
            <div 
              v-for="plan in plans" 
              :key="plan.id"
              :class="plan.is_popular ? 'border-2 border-[#0284C7] ring-4 ring-[#0284C7]/10 bg-cyan-50/20' : 'border border-slate-200 bg-white'"
              class="rounded-2xl p-5 flex flex-col justify-between transition-all hover:shadow-lg relative"
            >
              <div v-if="plan.is_popular" class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#0284C7] text-white text-[11px] font-bold uppercase tracking-wider px-3 py-0.5 rounded-full shadow">
                Khuyên Dùng Cho Doanh Nghiệp
              </div>

              <div>
                <h4 class="font-extrabold text-slate-900 text-lg">{{ plan.name }}</h4>
                <div class="mt-3 pb-3 border-b border-slate-100">
                  <div class="text-2xl font-black text-[#0F294A]">
                    {{ formatVnd(isYearly ? (plan.price_yearly / 12) : plan.price_monthly) }}
                    <span class="text-xs font-normal text-slate-500">/ tháng</span>
                  </div>
                  <div v-if="isYearly" class="text-xs text-emerald-600 font-semibold mt-0.5">
                    {{ formatVnd(plan.price_yearly) }} / năm
                  </div>
                </div>

                <!-- Spec List -->
                <ul class="mt-4 space-y-2.5 text-xs text-slate-700">
                  <li class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Vi xử lý vCPU:</span>
                    <span class="font-bold text-slate-900">{{ plan.vcpu }} Cores AMD EPYC</span>
                  </li>
                  <li class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Bộ nhớ RAM:</span>
                    <span class="font-bold text-slate-900">{{ plan.ram_gb }} GB ECC DDR4</span>
                  </li>
                  <li class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Ổ cứng Pure NVMe:</span>
                    <span class="font-bold text-slate-900">{{ plan.storage_nvme_gb }} GB RAID-10</span>
                  </li>
                  <li class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Địa chỉ IPv4 tĩnh:</span>
                    <span class="font-bold text-slate-900">{{ plan.ipv4_count }} IP riêng biệt</span>
                  </li>
                  <li class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Băng thông mạng:</span>
                    <span class="font-bold text-slate-900">Không giới hạn</span>
                  </li>
                  <li class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Cổng mạng vật lý:</span>
                    <span class="font-bold text-slate-900">{{ plan.port_speed }}</span>
                  </li>
                </ul>

                <!-- Additional Features -->
                <div class="mt-4 pt-2">
                  <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Đặc quyền hạ tầng:</div>
                  <ul class="space-y-1.5 text-xs text-slate-600">
                    <li v-for="(feat, idx) in plan.features" :key="idx" class="flex items-start gap-1.5">
                      <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>{{ feat }}</span>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- Action button -->
              <div class="mt-6 pt-3 border-t border-slate-100">
                <button 
                  type="button" 
                  @click="selectPlan(plan)"
                  :class="plan.is_popular ? 'bg-[#EA580C] hover:bg-[#C2410C] text-white shadow-md shadow-[#EA580C]/25' : 'bg-[#0F294A] hover:bg-[#183F70] text-white'"
                  class="w-full py-2.5 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5"
                >
                  <span>Chọn Gói & Yêu Cầu Báo Giá</span>
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer Notice -->
        <div class="bg-slate-50 px-6 py-4 text-center text-xs text-slate-500 border-t border-slate-200">
          * Giá trên chưa bao gồm thuế GTGT (VAT 10%). Mọi cấu hình đều được bàn giao trong vòng 15 phút kèm hợp đồng SLA pháp lý cam kết mức độ dịch vụ 99.9%.
        </div>

      </div>
    </div>
  </div>
</template>
