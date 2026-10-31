<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const layoutRef = ref(null);

const triggerPartnerForm = (sectorName = 'Đại lý phân phối tổng hợp') => {
  if (layoutRef.value) {
    layoutRef.value.openLeadModal('partner_registration', null, sectorName);
  }
};
</script>

<template>
  <PublicLayout ref="layoutRef">
    <Head>
      <title>Chính Sách Đối Tác & Đại Lý - Toàn Cầu Tech</title>
      <meta name="description" content="Chính sách hợp tác phân phối và phát triển hệ sinh thái công nghệ cùng Toàn Cầu Technology. Chiết khấu lên đến 35%, bảo hộ dự án, hỗ trợ kỹ thuật toàn diện." />
    </Head>

    <!-- Header Section - High-Tech Modern IT Banner -->
    <div class="relative bg-[#061224] text-white py-14 border-b border-slate-800 overflow-hidden">
      <!-- High-Tech Cyber Grid & Glow -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_60%,transparent_100%)] pointer-events-none"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-amber-300 bg-amber-400/10 px-3 py-1 rounded-full">
            Chính Sách Hợp Tác Doanh Nghiệp B2B
          </span>
          <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
            Đối Tác & Đại Lý Phân Phối Toàn Cầu Technology
          </h1>
          <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
            Hợp tác cùng phát triển trong 5 lĩnh vực hạ tầng công nghệ mũi nhọn với chính sách chiết khấu cạnh tranh, bảo hộ dự án và hỗ trợ kỹ thuật mặt đất tận nơi.
          </p>
        </div>

        <button 
          type="button" 
          @click="triggerPartnerForm()"
          class="px-6 py-3.5 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold shadow-xl shadow-[#EA580C]/25 transition shrink-0"
        >
          Đăng Ký Làm Đại Lý Ngay
        </button>
      </div>
    </div>

    <div class="py-12 bg-slate-50 space-y-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- 5 Cooperation Fields Grid -->
        <div>
          <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold text-[#0284C7] uppercase tracking-wider bg-cyan-50 px-3 py-1 rounded-full">
              5 Mảng Hợp Tác Trọng Điểm
            </span>
            <h2 class="text-2xl font-extrabold text-[#0F294A] mt-2">
              Lựa Chọn Lĩnh Vực Hợp Tác Phù Hợp Năng Lực
            </h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Sector 1 -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm">
              <div class="space-y-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">1</div>
                <h3 class="text-lg font-bold text-slate-900">Cloud & Dedicated Server</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                  Phân phối lại hạ tầng máy chủ vật lý, Cloud VPS NVMe và Private Cloud cho khách hàng của bạn với mức chiết khấu định kỳ trọn đời hợp đồng.
                </p>
              </div>
              <button 
                type="button" 
                @click="triggerPartnerForm('Đối tác phân phối Cloud & Dedicated')"
                class="mt-6 w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition"
              >
                Đăng ký mảng Cloud
              </button>
            </div>

            <!-- Sector 2 -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm">
              <div class="space-y-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">2</div>
                <h3 class="text-lg font-bold text-slate-900">Internet & Kết Nối Đa Điểm</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                  Đại lý bán kênh thuê riêng Leased Line, dịch vụ SD-WAN và SIM IoT Multi-WAN cho khối văn phòng, KCN và dự án viễn thông.
                </p>
              </div>
              <button 
                type="button" 
                @click="triggerPartnerForm('Đại lý Kênh Internet Doanh Nghiệp')"
                class="mt-6 w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition"
              >
                Đăng ký mảng Internet
              </button>
            </div>

            <!-- Sector 3 -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm">
              <div class="space-y-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">3</div>
                <h3 class="text-lg font-bold text-slate-900">Smart IoT & Solar Camera</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                  Phân phối thiết bị camera năng lượng mặt trời 4G ngoài trời, IoT Gateway Modbus/MQTT cho các công trình nông nghiệp, ven biển, san lấp.
                </p>
              </div>
              <button 
                type="button" 
                @click="triggerPartnerForm('Đại lý Thiết bị Smart IoT')"
                class="mt-6 w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition"
              >
                Đăng ký mảng Smart IoT
              </button>
            </div>

            <!-- Sector 4 -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm">
              <div class="space-y-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">4</div>
                <h3 class="text-lg font-bold text-slate-900">Bluecom 4G & Truyền Thanh Số</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                  Đại lý độc quyền hoặc cấp tỉnh triển khai thiết bị truyền thanh 4G và phần mềm quản trị AI Text-to-Speech cho các UBND xã/phường/huyện.
                </p>
              </div>
              <button 
                type="button" 
                @click="triggerPartnerForm('Đại lý Thiết bị Bluecom 4G')"
                class="mt-6 w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition"
              >
                Đăng ký mảng Bluecom
              </button>
            </div>

            <!-- Sector 5 -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm">
              <div class="space-y-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-[#0284C7] flex items-center justify-center font-bold">5</div>
                <h3 class="text-lg font-bold text-slate-900">Dịch Vụ Kỹ Thuật Mặt Đất</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                  Hợp tác với các đội ngũ kỹ thuật, thầu phụ viễn thông tại các địa phương để thi công và ứng cứu sự cố hạ tầng tại chỗ.
                </p>
              </div>
              <button 
                type="button" 
                @click="triggerPartnerForm('Đối tác Thi công Kỹ thuật Địa phương')"
                class="mt-6 w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition"
              >
                Đăng ký mảng Kỹ thuật
              </button>
            </div>

            <!-- Register Callout -->
            <div class="bg-gradient-to-br from-[#0F294A] to-[#183F70] rounded-3xl p-6 text-white flex flex-col justify-between shadow-md">
              <div>
                <span class="text-[11px] font-bold text-amber-300 uppercase tracking-wider">Hỗ Trợ Tối Đa</span>
                <h3 class="text-xl font-bold mt-2">Bắt Đầu Hợp Tác Ngay Hôm Nay</h3>
                <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                  Chúng tôi cử chuyên viên thẩm định hồ sơ năng lực và liên hệ trao đổi chi tiết cơ chế trong vòng 24 giờ làm việc.
                </p>
              </div>
              <button 
                type="button" 
                @click="triggerPartnerForm('Đại lý Tổng thể')"
                class="mt-6 w-full py-3 bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold rounded-xl transition shadow"
              >
                Gửi Hồ Sơ Đăng Ký Đại Lý
              </button>
            </div>

          </div>
        </div>

      </div>
    </div>
  </PublicLayout>
</template>
