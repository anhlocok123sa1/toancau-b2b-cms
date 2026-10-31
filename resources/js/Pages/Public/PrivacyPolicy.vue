<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';
</script>

<template>
  <PublicLayout>
    <Head>
      <title>Chính Sách Bảo Mật Dữ Liệu Cá Nhân (Nghị định 13/2023/NĐ-CP) - Toàn Cầu Tech</title>
      <meta name="description" content="Chính sách bảo vệ và xử lý dữ liệu cá nhân theo Nghị định số 13/2023/NĐ-CP của Chính phủ tại Toàn Cầu Technology." />
    </Head>

    <div class="bg-[#0F294A] text-white py-14 border-b border-slate-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full">
          Tuân Thủ Pháp Lý Bắt Buộc
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
          Chính Sách Bảo Vệ Dữ Liệu Cá Nhân (Nghị Định 13/2023/NĐ-CP)
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
          Cam kết bảo mật tuyệt đối, minh bạch trong thu thập và xử lý thông tin cá nhân của khách hàng, đối tác và đại lý tại Toàn Cầu Technology.
        </p>
      </div>
    </div>

    <div class="py-12 bg-slate-50 min-h-screen">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-sm space-y-8 text-xs sm:text-sm text-slate-700 leading-relaxed">
          
          <div class="p-4 rounded-2xl bg-cyan-50 border border-cyan-100 text-slate-800">
            <strong>Căn cứ pháp lý:</strong> Chính sách này được xây dựng và thực thi nghiêm ngặt theo quy định tại <strong>Nghị định số 13/2023/NĐ-CP</strong> ngày 17/04/2023 của Chính phủ về Bảo vệ dữ liệu cá nhân (BVDLCN).
          </div>

          <!-- Section 1 -->
          <div>
            <h2 class="text-lg font-bold text-[#0F294A] mb-2">1. Mục Đích Thu Thập & Xử Lý Dữ Liệu</h2>
            <p>
              Toàn Cầu Technology thu thập dữ liệu cá nhân (Họ tên, Số điện thoại, Email, Đơn vị công tác, Địa chỉ liên hệ) thông qua 3 biểu mẫu trực tuyến (Tư vấn báo giá, Đăng ký đối tác/đại lý, Yêu cầu dịch vụ kỹ thuật) chỉ nhằm các mục đích hợp pháp sau:
            </p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
              <li>Xác thực và liên hệ tư vấn kỹ thuật, báo giá cấu hình dịch vụ theo đúng yêu cầu (Cam kết SLA 15-30 phút).</li>
              <li>Thẩm định tư cách pháp nhân và hồ sơ năng lực đại lý phân phối thiết bị Bluecom 4G & Smart IoT.</li>
              <li>Gửi tài liệu kỹ thuật (Datasheet, Bảng vẽ hiện trường) và hợp đồng dịch vụ.</li>
            </ul>
          </div>

          <!-- Section 2 -->
          <div>
            <h2 class="text-lg font-bold text-[#0F294A] mb-2">2. Nguyên Tắc Bảo Vệ & Lưu Trữ An Toàn</h2>
            <p>
              Mọi tệp đính kèm (Profile doanh nghiệp, hình ảnh hiện trường) được lưu trữ tại phân vùng bảo mật chuyên dụng (<code class="bg-slate-100 px-1 py-0.5 rounded text-[#0F294A] font-mono text-xs">storage/app/private</code>), có phân quyền truy cập nghiêm ngặt và ngăn chặn hoàn toàn việc thu thập tự động từ internet.
            </p>
            <p class="mt-2">
              Toàn Cầu Technology cam kết: <strong>TUYỆT ĐỐI KHÔNG mua bán, chuyển giao hoặc tiết lộ dữ liệu cá nhân của Quý khách cho bên thứ ba vì bất kỳ mục đích thương mại nào.</strong>
            </p>
          </div>

          <!-- Section 3 -->
          <div>
            <h2 class="text-lg font-bold text-[#0F294A] mb-2">3. Quyền Của Chủ Thể Dữ Liệu</h2>
            <p>Theo Nghị định 13/2023/NĐ-CP, Quý khách với tư cách là chủ thể dữ liệu có đầy đủ các quyền:</p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
              <li>Quyền được biết về mục đích, phạm vi xử lý dữ liệu.</li>
              <li>Quyền yêu cầu chỉnh sửa, cập nhật hoặc xóa bỏ dữ liệu liên hệ khỏi hệ thống CRM.</li>
              <li>Quyền rút lại sự đồng ý hoặc khiếu nại về việc xử lý dữ liệu qua Hotline: <strong class="text-[#0284C7]">1900 6868</strong> hoặc Email: <strong class="text-[#0284C7]">privacy@toancau.vn</strong>.</li>
            </ul>
          </div>

          <!-- Section 4 -->
          <div>
            <h2 class="text-lg font-bold text-[#0F294A] mb-2">4. Thông Tin Liên Hệ Bộ Phận Bảo Vệ Dữ Liệu</h2>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-1 text-xs">
              <div><strong>Đơn vị kiểm soát dữ liệu:</strong> CÔNG TY CỔ PHẦN CÔNG NGHỆ TOÀN CẦU</div>
              <div><strong>Địa chỉ:</strong> Tầng 8, Tòa nhà Công Nghệ Số, 123 Nguyễn Thị Minh Khai, P. Bến Thành, Quận 1, TP. Hồ Chí Minh</div>
              <div><strong>Hotline DPO:</strong> 1900 6868 (Ext: 102)</div>
              <div><strong>Email chuyên trách:</strong> privacy@toancau.vn</div>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-100 text-center">
            <Link href="/" class="text-xs font-bold text-[#0284C7] hover:underline">
              ← Quay lại Trang Chủ Toàn Cầu Technology
            </Link>
          </div>

        </div>

      </div>
    </div>
  </PublicLayout>
</template>
