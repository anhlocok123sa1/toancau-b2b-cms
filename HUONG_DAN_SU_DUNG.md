# TÀI LIỆU HƯỚNG DẪN SỬ DỤNG HỆ THỐNG WEBSITE B2B & CMS TOÀN CẦU TECHNOLOGY
> **Thông điệp thương hiệu**: *"Hạ tầng số – Kết nối thông minh – Giải pháp công nghệ"*  
> **Phiên bản hệ thống**: Giai đoạn 1 (B2B Portal & Enterprise CMS)  
> **Cập nhật lần cuối**: 29/10/2026  

---

## MỤC LỤC
1. [Tổng Quan Hệ Thống & Bộ Nhận Diện](#1-tổng-quan-hệ-thống--bộ-nhận-diện)
2. [Hướng Dẫn Dành Cho Khách Hàng & Đối Tác (Giao Diện Public)](#2-hướng-dành-cho-khách-hàng--đối-tác-giao-diện-public)
   - [2.1 Cấu trúc điều hướng 8 Menu chính](#21-cấu-trúc-điều-hướng-8-menu-chính)
   - [2.2 Khung 11 thành phần bắt buộc trang chi tiết sản phẩm](#22-khung-11-thành-phần-bắt-buộc-trang-chi-tiết-sản-phẩm)
   - [2.3 Quy trình gửi 3 Biểu mẫu & Tiếp nhận Lead SLA 15-30 phút](#23-quy-trình-gửi-3-biểu-mẫu--tiếp-nhận-lead-sla-15-30-phút)
   - [2.4 Tiện ích nổi Hotline & Chat Zalo OA](#24-tiện-ích-nổi-hotline--chat-zalo-oa)
3. [Hệ Thống Phân Quyền 5 Vai Trò (RBAC Matrix)](#3-hệ-thống-phân-quyền-5-vai-trò-rbac-matrix)
4. [Hướng Dẫn Đăng Nhập & Truy Cập Quản Trị](#4-hướng-dẫn-đăng-nhập--truy-cập-quản-trị)
5. [Hướng Dẫn Chi Tiết Theo Từng Vai Trò Quản Trị (Admin Portal)](#5-hướng-dẫn-chi-tiết-theo-từng-vai-trò-quản-trị-admin-portal)
   - [5.1 Role 1: Quản Trị Viên Tối Cao (Admin)](#51-role-1-quản-trị-viên-tối-cao-admin)
   - [5.2 Role 2: Quản Trị Viên Nội Dung (Content Admin)](#52-role-2-quản-trị-viên-nội-dung-content-admin)
   - [5.3 Role 3: Chuyên Viên Kinh Doanh (Sales)](#53-role-3-chuyên-viên-kinh-doanh-sales)
   - [5.4 Role 4: Quản Lý Đối Tác & Đại Lý (Partner Manager)](#54-role-4-quản-lý-đối-tác--đại-lý-partner-manager)
   - [5.5 Role 5: Chuyên Viên Báo Cáo (Viewer - Read Only)](#55-role-5-chuyên-viên-báo-cáo-viewer---read-only)
6. [Quy Chuẩn Pháp Lý & Kiểm Soát Thông Tin Nhạy Cảm](#6-quy-chuẩn-pháp-lý--kiểm-soát-thông-tin-nhạy-cảm)
7. [Kiến Trúc Sẵn Sàng Tích Hợp Giai Đoạn 2 (Phase 2 Hooks)](#7-kiến-trúc-sẵn-sàng-tích-hợp-giai-đoạn-2-phase-2-hooks)
8. [Bảng Tra Cứu Lệnh Vận Hành Kỹ Thuật (CLI Cheat Sheet)](#8-bảng-tra-cứu-lệnh-vận-hành-kỹ-thuật-cli-cheat-sheet)

---

## 1. TỔNG QUAN HỆ THỐNG & BỘ NHẬN DIỆN

Hệ thống **Toàn Cầu Technology** là nền tảng số hóa B2B hiện đại kết hợp Cổng quản trị CMS & Tiếp nhận Lead khách hàng doanh nghiệp.

### Bảng màu chuẩn B2B Technology
| Mã Màu Hex | Tên Định Danh | Ứng Dụng Trong Hệ Thống |
| :--- | :--- | :--- |
| `#0F294A` | `navy` | Màu chủ đạo: Header thanh điều hướng, Hero banner, tiêu đề khối, chân trang |
| `#0284C7` | `cyan` | Màu kết nối công nghệ: Icon chức năng, liên kết, nhãn nổi bật, thanh tiến trình |
| `#F8FAFC` | `surface` | Nền sáng sạch: Tối ưu khả năng đọc thông số kỹ thuật, bảng dữ liệu 2 cột |
| `#EA580C` | `cta` | Màu cam tương phản cao: Nút kêu gọi hành động `[Yêu cầu tư vấn]`, `[Báo giá]`, `[Khảo sát]` |

---

## 2. HƯỚNG DẪN DÀNH CHO KHÁCH HÀNG & ĐỐI TÁC (GIAO DIỆN PUBLIC)

### 2.1 Cấu trúc điều hướng 8 Menu chính

Khách hàng và đối tác có thể truy cập toàn bộ hệ sinh thái dịch vụ qua thanh menu trên cùng:

```
[Trang Chủ] ── [Cloud & Hạ Tầng] ── [Internet Doanh Nghiệp] ── [Smart IoT] ── [Bluecom 4G] ── [Dịch Vụ Kỹ Thuật] ── [Đối Tác & Đại Lý] ── [Về Toàn Cầu]
```

1. **Trang Chủ (`/`)**:
   - **Hero Banner**: Tiêu đề *"KẾT NỐI HẠ TẦNG – KIẾN TẠO GIẢI PHÁP SỐ"* với 3 nút chuyển đổi: `[Khám phá giải pháp]`, `[Liên hệ tư vấn]`, `[Hợp tác kinh doanh]`.
   - **6 Trụ cột cốt lõi**: Khám phá nhanh 6 khối giải pháp hạ tầng và thiết bị.
   - **Khối Năng lực công nghệ & Hạ tầng**: Hiển thị năng lực 100+ Cụm Server Tier 3, tài nguyên 4.000 IP sạch, cam kết SLA 99.9% và đội phản ứng khẩn 15-30 phút (Admin có thể bật/tắt linh hoạt).
   - **Showcase Sản phẩm tiêu biểu**: Hiển thị tối đa 8 sản phẩm do Quản trị viên gắn cờ nổi bật.
   - **Banner Đại lý**: Kêu gọi hợp tác phân phối với chính sách chiết khấu tới 35%.
   - **Lưới Dự án & Đối tác**: Các hợp đồng tiêu biểu tại Đồng Tháp, Navico, Vinaseed, Xăng dầu Biển Đông.

2. **Cloud & Infrastructure (`/cloud-infrastructure`)**:
   - Gồm 4 tab phân hệ:
     - **Cloud VPS NVMe**: Cấu hình từ 2 vCPU đến 16 vCPU, có nút mở **Modal Ma Trận So Sánh Trực Quan** giữa các gói, chuyển đổi chu kỳ thanh toán Tháng/Năm (chiết khấu 15%).
     - **Dedicated Server**: Máy chủ vật lý AMD EPYC 7763, RAM ECC, 2x NVMe Enterprise U.2/U.3, băng thông cam kết, hỗ trợ KVM chuyên dụng.
     - **Cho thuê dải IPv4 sạch**: Dịch vụ cấp dải /24 (256 IP) chính ngạch APNIC, hỗ trợ LOA công bố BGP ASN, ẩn 100% subnet nhạy cảm ra ngoài cổng Public.
     - **Private Cloud cho SME & SI**: Hạ tầng đám mây dùng riêng, mạng VLAN nội bộ và lưu trữ Ceph phân tán.

3. **Internet & Connectivity (`/internet-connectivity`)**:
   - **Internet Leased Line**: Kênh truyền cáp quang đối xứng 1:1, SLA 99.9%, độ trễ < 3ms, tặng kèm dải IPv4 tĩnh /29.
   - **Mạng riêng ảo SD-WAN**: Kết nối bảo mật đa điểm, cân bằng tải đường truyền thông minh.
   - **SIM IoT & Multi-WAN Failover**: Dự phòng đường truyền tự động chuyển mạch trong vòng 3 giây.
   - **Giải pháp vệ tinh Starlink**: Năng lực khảo sát hiện trường góc mở bầu trời, tích hợp bộ định tuyến công nghiệp cho tàu biển và công trình ngoài khơi.

4. **Smart IoT (`/smart-iot`)**:
   - Bộ lọc 5 danh mục chuyên sâu:
     1. *Solar Camera*: Hoạt động độc lập 100% bằng pin năng lượng mặt trời, ghi hình 5 ngày mưa liên tục.
     2. *Camera 4G / Wi-Fi*: Giám sát công trình, vỏ kháng sương muối ven biển chuẩn IP67.
     3. *IoT Gateway*: Chuyển đổi tín hiệu Modbus RTU sang MQTT / JSON đám mây, gắn thanh cài DIN-Rail.
     4. *Cảm biến công / nông nghiệp*: Đo đạc pH, EC, nhiệt độ, độ ẩm, khí thải, nước thải tự động.
     5. *Thiết bị giám sát & điều khiển từ xa*: Đóng ngắt relay 4 kênh qua ứng dụng điện thoại.

5. **Bluecom 4G (`/bluecom`)**:
   - Thiết bị thu phát truyền thanh thông minh 4G vỏ đúc hợp kim chống nước IP66.
   - Sơ đồ nguyên lý 4 bước: Từ văn bản cơ quan $\rightarrow$ Bluecom Cloud CMS & AI $\rightarrow$ Hạ tầng 4G LTE $\rightarrow$ Cụm thu phát và loa số Hi-Fi.
   - Tính năng AI Text-To-Speech đọc bản tin phát thanh tự động, phát thanh khẩn cấp trực tiếp bằng điện thoại.
   - Nút `[Đăng ký khảo sát hiện trường]` đo kiểm sóng miễn phí.

6. **Technical Services (`/technical-services`)**:
   - Phân hệ *Thi công & Lắp đặt hạ tầng*: Mạng LAN/Wi-Fi, hệ thống camera, phòng server.
   - Phân hệ *Bảo trì & Ứng cứu sự cố khẩn cấp*: Đo kiểm OTDR cáp quang, thay thế thiết bị trong 2 giờ.
   - Form gửi yêu cầu kỹ thuật trực tiếp có đính kèm ảnh chụp sự cố hoặc bản vẽ mặt bằng.

7. **Đối Tác & Đại Lý (`/doi-tac-dai-ly`)**:
   - Chính sách hợp tác cho 5 mảng kinh doanh (Cloud, Internet, IoT, Bluecom, Dịch vụ kỹ thuật).
   - Form nộp hồ sơ năng lực (Profile công ty / Giấy ĐKKD định dạng PDF/DOCX $\le$ 10MB).

8. **Về Toàn Cầu (`/ve-toan-cau`)**:
   - Giới thiệu pháp nhân, cơ sở vật chất, đội ngũ kỹ sư CCNA/CCNP.
   - Liên kết trực tiếp tới: Dự án tiêu biểu (`/du-an-tieu-bieu`), Tin tức công nghệ (`/tin-tuc`), Liên hệ trụ sở bản đồ (`/lien-he`) và Chính sách bảo vệ dữ liệu cá nhân (`/chinh-sach-bao-mat`).

---

### 2.2 Khung 11 thành phần bắt buộc trang chi tiết sản phẩm

Khi truy cập trang chi tiết bất kỳ sản phẩm nào theo cú pháp `/san-pham/{slug}`, giao diện luôn hiển thị đầy đủ 11 thành phần theo đúng thứ tự quy chuẩn:

```
┌───────────────────────────────────────────────────────────────────┐
│ [01] Breadcrumb phân cấp & Schema JSON-LD BreadcrumbList          │
├───────────────────────────────────────────────────────────────────┤
│ [02] Tiêu đề H1 Tên sản phẩm + Mã Model chính thức (model_code)   │
├───────────────────────────────────────────────────────────────────┤
│ [03] Gallery hình ảnh WebP đa góc (zoom chi tiết, lazy load)      │
├───────────────────────────────────────────────────────────────────┤
│ [04] Đoạn mô tả tổng quan (2-3 câu định vị giá trị B2B)          │
├───────────────────────────────────────────────────────────────────┤
│ [05] Đặc điểm & Tính năng chính (dạng thẻ icon nổi bật)           │
├───────────────────────────────────────────────────────────────────┤
│ [06] Bảng thông số kỹ thuật 2 cột (Dữ liệu JSON động)             │
├───────────────────────────────────────────────────────────────────┤
│ [07] Kịch bản & môi trường ứng dụng thực tế (nhà xưởng, ven biển) │
├───────────────────────────────────────────────────────────────────┤
│ [08] Nút tải về Datasheet / Tài liệu kỹ thuật chính hãng (PDF)    │
├───────────────────────────────────────────────────────────────────┤
│ [09] Chính sách bảo hành (12-36 tháng) & Cam kết SLA hỗ trợ 24/7   │
├───────────────────────────────────────────────────────────────────┤
│ [10] Sản phẩm / Dịch vụ liên quan (Cross-selling 3-4 thiết bị)     │
├───────────────────────────────────────────────────────────────────┤
│ [11] Cụm CTA [Yêu cầu báo giá] & Giá tùy chọn (Hiện giá / Liên hệ)│
└───────────────────────────────────────────────────────────────────┘
```

---

### 2.3 Quy trình gửi 3 Biểu mẫu & Tiếp nhận Lead SLA 15-30 phút

Hệ thống chuẩn hóa 3 biểu mẫu phục vụ các nhu cầu khác nhau của khách hàng:

1. **Biểu mẫu Tư Vấn / Báo Giá (`consultation_quote`)**:
   - Dành cho khách hàng cần báo giá cấu hình VPS, Dedicated Server, Kênh thuê riêng Leased Line hoặc dải IP.
   - Trường thông tin: Họ tên, Doanh nghiệp, SĐT, Email, Dịch vụ/Sản phẩm quan tâm, Nội dung yêu cầu, Checkbox đồng ý xử lý dữ liệu.
2. **Biểu mẫu Đăng Ký Đại Lý (`partner_registration`)**:
   - Dành cho doanh nghiệp muốn trở thành đối tác phân phối thiết bị hoặc tích hợp giải pháp.
   - Trường thông tin: Họ tên, Tên công ty, SĐT, Email, Tỉnh/Thành phố, Lĩnh vực hợp tác, File đính kèm Hồ sơ năng lực (Profile PDF/DOCX $\le$ 10MB).
3. **Biểu mẫu Dịch Vụ Kỹ Thuật (`technical_service`)**:
   - Dành cho khách hàng cần khảo sát hiện trường, thi công hoặc bảo trì sửa chữa khẩn cấp.
   - Trường thông tin: Họ tên, SĐT, Địa điểm/Công trình, Phân loại dịch vụ, Mô tả sự cố, File đính kèm ảnh chụp lỗi/bản vẽ hiện trường ($\le$ 10MB).

---

### 2.4 Tiện ích nổi Hotline & Chat Zalo OA

Ở góc phải dưới cùng của mọi trang public có cụm tiện ích cố định:
- **Nút Hotline (Màu cam, hiệu ứng rung)**: Nhấp để gọi trực tiếp tới `1900 6868` tiếp nhận sự cố kỹ thuật 24/7/365.
- **Nút Chat Zalo OA (Màu xanh, logo Zalo)**: Nhấp để mở ngay khung chat chính thức của Toàn Cầu Technology trên ứng dụng Zalo.

---

## 3. HỆ THỐNG PHÂN QUYỀN 5 VAI TRÒ (RBAC MATRIX)

Hệ thống được kiểm soát nghiêm ngặt bởi Middleware `EnforceToanCauRbac` và thư viện `spatie/laravel-permission`:

| Tính năng / Phân hệ | [1] Admin | [2] Content Admin | [3] Sales | [4] Partner Mgr | [5] Viewer |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Xem Dashboard Tổng Quan** | Có | Có | Có | Có | Có |
| **Xem & Xử Lý Lead Bán Hàng** | Có | **CHẶN (403)** | Có | Có | Có (Chỉ xem) |
| **Cập Nhật 5 Trạng Thái Lead** | Có | **CHẶN (403)** | Có | Có | **CHẶN (403)** |
| **Tải Tệp Đính Kèm Private của Lead** | Có | **CHẶN (403)** | Có | Có | Có |
| **Xuất Báo Cáo Lead (CSV/Excel)** | Có | **CHẶN (403)** | Có | Có | **CHẶN (403)** |
| **Quản Trị Sản Phẩm (11 Mục)** | Có | Có | Không | Không | Có (Chỉ xem) |
| **Bật/Tắt Giá & Cờ Nổi Bật Sản Phẩm**| Có | Có | Không | Không | **CHẶN (403)** |
| **Upload Ảnh WebP & Datasheet PDF** | Có | Có | Không | Không | **CHẶN (403)** |
| **Phê Duyệt 6 Checklist Pháp Lý** | Có | **CHẶN (403)** | **CHẶN (403)** | **CHẶN (403)** | **CHẶN (403)** |
| **Cấu Hình GA4, GSC, Hotline** | Có | **CHẶN (403)** | **CHẶN (403)** | **CHẶN (403)** | **CHẶN (403)** |
| **Xem Toàn Bộ Audit Logs** | Có | Không | Không | Không | Không |

---

## 4. HƯỚNG DẪN ĐĂNG NHẬP & TRUY CẬP QUẢN TRỊ

1. Truy cập đường dẫn: `/login` (hoặc nhấn nút *"Đăng nhập Quản Trị"* trên đầu trang).
2. Hệ thống hỗ trợ bảng **Đăng nhập nhanh 1-Click** cho 5 tài khoản thử nghiệm chuẩn:

```
┌────────────────────────────────────────────────────────────────────────┐
│ [1] Admin:       admin@toancau.vn    /  Mật khẩu: Password@123         │
│ [2] Content:     content@toancau.vn  /  Mật khẩu: Password@123         │
│ [3] Sales:       sales@toancau.vn    /  Mật khẩu: Password@123         │
│ [4] Partner Mgr: partner@toancau.vn  /  Mật khẩu: Password@123         │
│ [5] Viewer:      viewer@toancau.vn   /  Mật khẩu: Password@123         │
└────────────────────────────────────────────────────────────────────────┘
```

3. Sau khi xác thực thành công, hệ thống tự động chuyển hướng tới `/admin/dashboard`.

---

## 5. HƯỚNG DẪN CHI TIẾT THEO TỪNG VAI TRÒ QUẢN TRỊ (ADMIN PORTAL)

### 5.1 Role 1: Quản Trị Viên Tối Cao (Admin)
- **Tài khoản**: `admin@toancau.vn`
- **Quyền hạn**: Toàn quyền cấu hình hệ thống, quản trị người dùng, phê duyệt pháp lý.

#### Nhiệm vụ chính:
1. **Phê duyệt 6 Checklist pháp lý (`/admin/compliance`)**:
   - Kiểm tra thông tin pháp nhân và số điện thoại tiếp nhận lead.
   - Nhấn nút `[Phê duyệt công khai (is_verified = 1)]` để cho phép hiển thị thông tin ra ngoài Public.
   - Khi cần bảo mật hoặc bảo trì, nhấn `[Hủy phê duyệt (Ẩn Public)]` để ngay lập tức ẩn thông tin mà không cần sửa code.
2. **Cấu hình Tiếp thị số & Kênh liên hệ**:
   - Cập nhật mã đo lường Google Analytics 4 (`GA4 ID`).
   - Cập nhật mã xác thực Google Search Console (`GSC Token`).
   - Chỉnh sửa số Hotline hiển thị trên toàn bộ các trang.
   - Bật hoặc Tắt khối *"Năng Lực Công Nghệ & Hạ Tầng"* trên trang chủ qua 1 nút tick.
3. **Giám sát Audit Logs**:
   - Theo dõi toàn bộ lịch sử thay đổi của hệ thống: Ai đã sửa sản phẩm, ai chuyển trạng thái Lead, lúc mấy giờ.

---

### 5.2 Role 2: Quản Trị Viên Nội Dung (Content Admin)
- **Tài khoản**: `content@toancau.vn`
- **Quyền hạn**: Quản lý toàn bộ nội dung trực quan từng trang, 6 Trụ cột, Dự án tiêu biểu, Gói VPS, Sản phẩm 11 mục, Tin tức & bài viết.

> [!WARNING]
> Content Admin **tuyệt đối không được phép xem hoặc truy cập** dữ liệu Lead bán hàng. Mọi truy cập vào `/admin/leads` đều trả về lỗi **HTTP 403 Forbidden**.

#### HỆ THỐNG QUẢN TRỊ NỘI DUNG TRỰC QUAN ZERO-CODE (KHÔNG CẦN BIẾT CODE)
Truy cập: Thanh điều hướng chọn mục **"Quản Trị Nội Dung & CMS"** (`/admin/products`).
Hệ thống tích hợp thanh điều hướng 6 Phân hệ con:

---

#### 1. 📝 Quản Lý Từng Trang (Zero-Code CMS)
Tại tab này, người quản trị có thể thay đổi câu chữ, tiêu đề, mô tả, danh sách gạch đầu dòng, các bước quy trình của toàn bộ 7 trang trên website mà **hoàn toàn không cần biết về code**:
1. Nhấp chọn 1 trong 7 trang cần sửa:
   - 🏠 **Trang Chủ (`home`)**: Sửa Hero Banner (Tiêu đề, Tagline pill, Mô tả) và Khối Hệ Sinh Thái 6 Trụ Cột.
   - ☁️ **Cloud & Hạ Tầng (`cloud`)**: Sửa Khối Giải Pháp Cloud Business (Private Cloud, Reseller Cloud) và Dịch Vụ Cho Thuê Dải IP (Chính sách IP sạch, Lưu ý an ninh).
   - 🌐 **Internet Doanh Nghiệp (`internet`)**: Sửa Kênh Leased Line, Mạng SD-WAN Đa Điểm, SIM IoT Multi-WAN, và Giải Pháp Vệ Tinh Starlink (Tiêu đề, Mô tả, Các gạch đầu dòng tính năng, Lưu ý tuân thủ pháp lý).
   - 📡 **Smart IoT (`smart_iot`)**: Sửa Tiêu đề banner, mô tả hệ sinh thái Solar Camera và cảm biến công nghiệp không dây.
   - 📢 **Bluecom 4G (`bluecom`)**: Sửa Sơ đồ nguyên lý vận hành 4 bước (Bước 1: Ban lãnh đạo Đài TT -> Bước 2: Bluecom Cloud AI -> Bước 3: Sóng 4G LTE -> Bước 4: Cụm thu phát & Loa Hi-Fi).
   - 🛠️ **Dịch Vụ Kỹ Thuật (`tech_services`)**: Sửa Khối Dịch Vụ Thi Công & Lắp Đặt (4 dịch vụ thành phần) và Khối Bảo Trì Chẩn Đoán Khẩn Cấp (4 cam kết ứng cứu).
   - 🏢 **Về Toàn Cầu (`about`)**: Sửa Giới thiệu công ty, khẩu hiệu và 3 Trụ Cột Năng Lực Cốt Lõi (Hạ tầng độc quyền, Kỹ sư trực tiếp 24/7, Cam kết SLA khắt khe).
2. Nhấn nút **`[Sửa Nội Dung Trực Quan]`** tại thẻ tương ứng.
3. Nhập tiêu đề, tiêu đề phụ, nhãn huy hiệu, đoạn văn nội dung bằng tiếng Việt thông thường. Có thể sửa trực tiếp các câu chữ bên trong ngoặc kép `""` ở ô dữ liệu cấu trúc.
4. Nhấn **`[Lưu Thay Đổi (Cập Nhật Ngay)]`**. Nội dung trên trang public sẽ được cập nhật tức thời!
5. Nhấn **`[Xem ngoài Web ↗]`** để kiểm tra giao diện thực tế.

---

#### 2. 🏛️ 6 Trụ Cột & Quản Lý Danh Mục (Bao Gồm Danh Mục Con Smart IoT)
Quản lý các ngành hàng và phân mục thiết bị cốt lõi của Toàn Cầu:
- **Lọc danh mục theo lĩnh vực**: Nhấn các nút lọc nhanh (*Tất cả*, *📡 Smart IoT*, *☁️ Cloud*, *🌐 Internet*, *📢 Bluecom*, *🛠️ Dịch Vụ Kỹ Thuật*) để xem riêng danh mục của từng mảng.
- **Thêm danh mục con cho Smart IoT**: Nhấn nút `[📡 + Thêm Danh Mục Smart IoT]` -> Hệ thống tự động điền sẵn lĩnh vực `smart_iot` và danh mục cha `Smart IoT & Giám Sát`. Nhập tên danh mục (VD: *Camera AI Nhận Diện Khuôn Mặt*, *Cảm Biến Báo Cháy Không Dây*), chọn Icon và thứ tự hiển thị.
  - *Kết quả ngoài website*: Danh mục mới sẽ **ngay lập tức xuất hiện thành một tab lọc chuyên biệt** tại trang `/smart-iot` kèm số lượng sản phẩm!
- **Thêm danh mục / trụ cột khác**: Nhấn `[+ Thêm Danh Mục / Trụ Cột Khác]` -> Có thể tạo danh mục gốc mới hoặc chọn danh mục cha tương ứng.
- **Chỉnh sửa**: Nhấn nút `[Sửa]` tại dòng tương ứng để đổi tên, danh mục cha, mô tả, icon, thứ tự, bật/tắt kích hoạt hiển thị.
- **Xóa danh mục**:
  - Nhấn nút `[Xóa]`. Hệ thống tự động kiểm tra an toàn:
    - Nếu là 1 trong 8 trụ cột hệ thống gốc (như Smart IoT, Cloud, Internet...), hệ thống sẽ chặn xóa để bảo vệ cấu trúc trang và menu.
    - Nếu danh mục đang có sản phẩm trực thuộc, hệ thống sẽ cảnh báo số lượng sản phẩm và yêu cầu chuyển sản phẩm trước khi xóa.
    - Nếu danh mục hợp lệ, hệ thống sẽ xóa ngay lập tức và đồng bộ gỡ tab lọc khỏi trang `/smart-iot`.

---

#### 2.1 📡 Hướng Dẫn Sử Dụng Bộ Lọc Danh Mục Tại Trang `/smart-iot`
- Khi khách hàng hoặc chuyên viên truy cập `/smart-iot`:
  - Thanh tab lọc danh mục dính phía trên (`sticky bar`) tự động tải toàn bộ danh mục từ CMS (Solar Camera, Camera 4G/Wi-Fi, IoT Gateway, Cảm biến, Bộ điều khiển từ xa...).
  - Mỗi nút tab hiển thị tên danh mục và huy hiệu số lượng thiết bị tương ứng: `(X)`.
  - Nhấp vào bất kỳ tab nào sẽ **lọc danh sách thiết bị ngay lập tức (0ms delay)** mà không cần tải lại trang, đồng thời tự động cập nhật URL (VD: `/smart-iot?sub=solar-camera`) để có thể gửi link trực tiếp cho khách hàng.
  - Nếu danh mục chưa có sản phẩm, hệ thống hiển thị thông báo chuyên nghiệp kèm nút CTA `[Yêu Cầu Báo Giá Thiết Bị Này]` pre-fill sẵn tên danh mục.
  - Bấm `[Tất Cả Danh Mục]` hoặc `[✕ Bỏ bộ lọc]` để hiển thị lại toàn bộ sản phẩm.

---

#### 3. 🏢 Quản Trị Dự Án & Khách Hàng Tiêu Biểu
Hiển thị uy tín và năng lực triển khai thực tế trên Trang chủ và trang `/du-an-tieu-bieu`:
- **Thêm mới dự án**: Nhấn `[+ Thêm Dự Án Mới]` -> Nhập Tên dự án, Tên khách hàng (VD: Tập đoàn Cảng Biển SG, Chi cục Kiểm lâm...), Lĩnh vực cốt lõi, Mô tả kết quả triển khai và Tải lên ảnh thực tế công trình (Hệ thống tự động nén định dạng WebP).
- **Gắn cờ Nổi Bật Trang Chủ**: Nhấn nút `[Gắn Nổi Bật]` / `[Bỏ Nổi Bật]` để quyết định dự án có xuất hiện ở Lưới Dự Án Tiêu Biểu trên Trang Chủ hay không (1-click tức thì).
- **Sửa / Xóa**: Nhấn `[Sửa]` để cập nhật nội dung hoặc thay ảnh mới; nhấn `[Xóa]` để xóa dự án.

---

#### 4. ⚡ Quản Trị Gói Cấu Hình Cloud VPS & Bảng So Sánh B2B
Tùy biến các gói VPS và cung cấp dữ liệu cho Modal so sánh giữa các gói:
- **Thêm mới gói VPS**: Nhấn `[+ Thêm Gói Cloud VPS]` -> Nhập Tên gói (VD: Cloud VPS Basic, Pro, Advanced, Enterprise Max), số vCPU Cores, RAM GB, dung lượng ổ cứng NVMe U.2/U.3 (GB), Băng thông cam kết, Cổng mạng Port Uplink, Số IPv4 tĩnh, Đơn giá thuê theo tháng (VNĐ), Đơn giá thuê theo năm (VNĐ), % Giảm giá khi thuê năm (mặc định 15%).
- **Danh sách tính năng**: Nhập danh sách tính năng (Mỗi dòng 1 tính năng, hệ thống tự động tách dòng và hiển thị dấu tích xanh `✓`).
- **Gắn cờ Phổ biến nhất**: Tích chọn `[Gắn nhãn Gói Phổ Biến Nhất]` để hiển thị nhãn cam nổi bật kêu gọi khách hàng lựa chọn.
- **Sửa / Xóa**: Dễ dàng chỉnh sửa thông số cấu hình và giá cả khi có chương trình khuyến mãi.

---

#### 5. 📦 Quản Trị Sản Phẩm Chuẩn 11 Thành Phần (Trang Biên Tập Toàn Màn Hình)
Hệ thống đã nâng cấp toàn diện từ cửa sổ pop-up modal nhỏ hẹp sang **Trang Biên Tập Độc Lập Toàn Màn Hình** (`/admin/products/{id}/edit` và `/admin/products/create`), cho phép chỉnh sửa 100% dữ liệu một cách trực quan, thoải mái và không cần kiến thức về code:
- **Mở trang chỉnh sửa**: Tại bảng sản phẩm, nhấn nút **`[Sửa 11 Mục ↗]`**. Hệ thống sẽ mở ngay trang chỉnh sửa toàn màn hình.
- **Thêm sản phẩm mới**: Nhấn nút **`[+ Thêm Sản Phẩm 11 Mục]`** để mở trang tạo mới với các trường định dạng chuẩn B2B.
- **Tùy biến Huy hiệu sản phẩm (`badge`)**:
  - Tại ô *"Huy Hiệu Nhãn Sản Phẩm"*, bạn có thể tự do gõ nội dung tùy ý (VD: *Chuyên Dụng Ven Biển IP67*, *Hạ Tầng Tier 3 Độc Quyền*, *Bảo Hành 24 Tháng 1 Đổi 1*, *Công Nghệ Mới 2026*...).
  - Nội dung này sẽ **thay thế hoàn toàn cho câu mặc định *"Sản Phẩm B2B Tiêu Chuẩn"*** ngoài trang chi tiết sản phẩm.
- **Trình chỉnh sửa trực quan Đặc Điểm & Tính Năng Nổi Bật (Features - [05])**:
  - Không cần sửa mã JSON phức tạp!
  - Nhấn nút **`[+ Thêm Tính Năng Mới]`** để tạo dòng mới, nhập Tiêu đề tính năng và Mô tả chi tiết trực tiếp bằng tiếng Việt.
  - Nhấn `✕` để xóa bớt tính năng không cần thiết.
- **Trình chỉnh sửa trực quan Bảng Thông Số Kỹ Thuật (Specs - [06])**:
  - Bảng 2 cột tiện dụng: Cột trái nhập *Tên thông số* (Độ phân giải, Dung lượng pin, Chipset...), Cột phải nhập *Giá trị* (4.0 MP, 20.000mAh, AMD EPYC...).
  - Nhấn nút **`[+ Thêm Thông Số Kỹ Thuật]`** để bổ sung thuộc tính mới chỉ với 1 click.
- **Trình chỉnh sửa trực quan Kịch Bản Ứng Dụng (Application Scenarios - [07])**:
  - Mỗi kịch bản là một dòng văn bản thuần (VD: *Các trang trại nuôi tôm ven biển*, *Công trường xây dựng cao ốc*...).
  - Nhấn nút **`[+ Thêm Kịch Bản Ứng Dụng]`** hoặc `✕` để xóa.
- **Đính kèm tài liệu & Tải ảnh WebP**: Xem trước ảnh WebP và link file Datasheet PDF hiện tại; chọn file mới để tự động cập nhật.
- **Thanh thao tác cố định**: Nhấn nút `[💾 Lưu Thay Đổi]` hoặc nhấn `[Xem Web ↗]` để kiểm tra giao diện trang chi tiết ngoài website ngay lập tức.

---

#### 6. 📰 Quản Trị Tin Tức & Kiến Thức Kỹ Thuật
- **Thêm bài viết mới**: Nhấn `[+ Thêm Bài Viết Mới]` -> Nhập Tiêu đề, Tóm tắt (Summary), Nội dung chi tiết (Content), Tác giả và tải lên ảnh đại diện bài viết.
- **Xuất bản**: Tích chọn `[Xuất Bản Ngay]` để công khai ra trang `/tin-tuc` hoặc bỏ tích để lưu bản nháp.
- **Sửa / Xóa**: Chỉnh sửa bài viết hoặc xóa bài viết.

---

### 5.3 Role 3: Chuyên Viên Kinh Doanh (Sales)
- **Tài khoản**: `sales@toancau.vn`
- **Quyền hạn**: Quản lý toàn bộ yêu cầu tư vấn, báo giá, yêu cầu kỹ thuật.

#### Quy trình xử lý Lead 5 trạng thái vòng đời (`/admin/leads`):
1. **Tìm kiếm & Lọc yêu cầu**:
   - Lọc theo từng trạng thái: `[1] MỚI`, `[2] ĐÃ TIẾP NHẬN`, `[3] ĐANG TƯ VẤN`, `[4] ĐÃ BÁO GIÁ`, `[5] THÀNH CÔNG`, `[5] THẤT BẠI`.
   - Lọc theo loại biểu mẫu: *Tư vấn báo giá*, *Đăng ký đại lý*, *Dịch vụ kỹ thuật*.
   - Đánh dấu tick vào ô `Chỉ xem Lead quá hạn SLA 30p` để ưu tiên xử lý các khách hàng đang chờ phản hồi.
2. **Xem chi tiết & Tải tệp đính kèm an toàn**:
   - Nhấn nút `[Chi Tiết & Xử Lý]`.
   - Xem đầy đủ thông tin: Họ tên, Doanh nghiệp, Số điện thoại, Email, Dịch vụ quan tâm.
   - Nếu khách hàng có tải lên ảnh hiện trường hoặc tài liệu, nhấn nút `[Tải tệp đính kèm]` để tải về an toàn từ vùng lưu trữ private.
3. **Cập nhật trạng thái vòng đời**:
   - Chọn trạng thái mới trong menu dropdown.
   - Chọn chuyên viên phụ trách (`Assigned To`).
   - Nhập ghi chú tương tác (VD: *"Đã gọi điện lần 1, khách hẹn gửi bảng tài nguyên qua email"*).
   - **Lưu ý đặc biệt**: Nếu chọn trạng thái `[5] THẤT BẠI (5_closed_lost)`, ô `Lý Do Thất Bại` sẽ bắt buộc phải nhập (VD: *"Vượt ngân sách dự kiến của khách", "Mục đích sử dụng IP không hợp lệ"*).
   - Nhấn `[Cập Nhật Trạng Thái]`.
4. **Xuất báo cáo**:
   - Nhấn nút `[Xuất CSV (Excel)]` trên góc phải để tải về toàn bộ danh sách khách hàng phục vụ báo cáo doanh thu và thẩm định định kỳ.

---

### 5.4 Role 4: Quản Lý Đối Tác & Đại Lý (Partner Manager)
- **Tài khoản**: `partner@toancau.vn`
- **Quyền hạn**: Chuyên trách tiếp nhận và thẩm định các hồ sơ đăng ký đại lý, đối tác phân phối 5 mảng.

#### Hướng dẫn xử lý hồ sơ Đại lý:
1. Vào phân hệ `/admin/leads`, lọc loại biểu mẫu: `Đăng Ký Đối Tác / Đại Lý`.
2. Mở từng hồ sơ, kiểm tra tỉnh/thành phố hoạt động và mảng đăng ký (Cloud, Internet, IoT, Bluecom, Kỹ thuật).
3. Nhấp tải tệp đính kèm Profile năng lực công ty của đại lý để thẩm định tư cách pháp nhân và kinh nghiệm đấu thầu.
4. Chuyển trạng thái sang `[3] ĐANG TƯ VẤN` khi đang đàm phán hợp đồng nguyên tắc hoặc chính sách chiết khấu.
5. Chuyển sang `[5] THÀNH CÔNG` khi chính thức ký thỏa thuận phân phối.

---

### 5.5 Role 5: Chuyên Viên Báo Cáo (Viewer - Read Only)
- **Tài khoản**: `viewer@toancau.vn`
- **Quyền hạn**: Chỉ đọc (Read-Only) toàn bộ hệ thống.

> [!NOTE]
> Tài khoản Viewer có thể xem số liệu thống kê Dashboard, danh sách sản phẩm, danh sách Lead và các biên bản pháp lý. Tuy nhiên, mọi nút bấm thực hiện hành động ghi đều bị vô hiệu hóa hoặc trả về lỗi **HTTP 403 Forbidden**.

---

## 6. QUY CHUẨN PHÁP LÝ & KIỂM SOÁT THÔNG TIN NHẠY CẢM

1. **Thông tin pháp nhân minh bạch**:
   - Tên đầy đủ: CÔNG TY CỔ PHẦN CÔNG NGHỆ TOÀN CẦU
   - MST: `0318999888`
   - Trụ sở: Tầng 8, Tòa nhà Công Nghệ Số, 123 Nguyễn Thị Minh Khai, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh
2. **Quy tắc an toàn tài nguyên IP (4.000 IP)**:
   - Con số 4.000 IP chỉ xuất hiện sau khi Admin xác nhận duyệt.
   - **Tuyệt đối không lưu trữ hoặc công khai danh sách dải subnet / CIDR nhạy cảm** ra ngoài bất kỳ API Public nào.
3. **Quy tắc truyền thông giải pháp Starlink**:
   - Mặc định cờ `starlink_reseller_capability` = 0.
   - Nội dung chỉ giới thiệu năng lực tư vấn, khảo sát hiện trường; **tuyệt đối không tự nhận là "Đại lý ủy quyền chính thức"** khi chưa có văn bản phê duyệt.
4. **Quyền phân phối Bluecom**:
   - Quản lý cờ hiển thị chứng nhận phân phối chính hãng thiết bị truyền thanh số 4G và Router công nghiệp.
5. **Quy chuẩn hiển thị kỹ thuật**:
   - 100% hình ảnh sản phẩm được tự động tối ưu hóa sang định dạng **WebP** thế hệ mới.
   - Đầy đủ bảng thông số kỹ thuật 2 cột chuẩn hóa và file Datasheet PDF đính kèm.
6. **Bảo vệ dữ liệu cá nhân theo Nghị định 13/2023/NĐ-CP**:
   - Có trang điều khoản riêng tại `/chinh-sach-bao-mat`.
   - Mọi form gửi dữ liệu đều yêu cầu xác nhận checkbox đồng ý thu thập thông tin hợp pháp.
   - Tệp đính kèm được phân lập tuyệt đối trong thư mục private.

---

## 7. KIẾN TRÚC SẴN SÀNG TÍCH HỢP GIAI ĐOẠN 2 (PHASE 2 HOOKS)

Hệ thống Giai đoạn 1 tập trung vào tiếp nhận Lead B2B, không làm giỏ hàng bán lẻ. Cấu trúc cơ sở dữ liệu và Model đã chừa sẵn các điểm nối kỹ thuật (Hook) hoàn chỉnh:

```
┌─────────────────────────────────────────────────────────────┐
│                       SẢN PHẨM / DỊCH VỤ                    │
├──────────────────────────────┬──────────────────────────────┤
│ GIAI ĐOẠN 1 (Hiện tại)       │ GIAI ĐOẠN 2 (Điểm nối sẵn)   │
├──────────────────────────────┼──────────────────────────────┤
│ • 11 Thành phần B2B          │ • provisioning_driver        │
│ • Tiếp nhận Lead SLA 30p     │   (proxmox, openstack, ipam) │
│ • Toggle Báo Giá / Liên hệ   │ • provisioning_metadata      │
│ • Phê duyệt pháp lý          │   (template_id, pool, vlan)  │
│ • Tư vấn giải pháp kỹ thuật  │ • Tích hợp VietQR Auto       │
│                              │ • Cấp phát VPS tự động       │
└──────────────────────────────┴──────────────────────────────┘
```

---

## 8. BẢNG TRA CỨU LỆNH VẬN HÀNH KỸ THUẬT (CLI CHEAT SHEET)

| Thao Tác Kỹ Thuật | Câu Lệnh Thực Thi | Mục Đích |
| :--- | :--- | :--- |
| **Biên dịch Frontend** | `npm run build` | Đóng gói mã nguồn Vue 3, Inertia, Tailwind CSS vào thư mục `public/build` |
| **Chạy môi trường Dev** | `npm run dev` | Chạy máy chủ Vite để biên dịch nóng (Hot Module Replacement) khi phát triển |
| **Khởi tạo lại CSDL & Seed** | `php artisan migrate:fresh --seed` | Xóa và tạo mới toàn bộ bảng, nạp 5 vai trò, 6 checklist và sản phẩm mẫu |
| **Kiểm tra danh sách Route** | `php artisan route:list` | Liệt kê toàn bộ 40 route của hệ thống kèm middleware bảo vệ |
| **Chạy kiểm thử tự động** | `php artisan test` | Chạy bộ kiểm thử tự động (Feature/Unit Test) xác thực các quyền RBAC |
| **Định dạng chuẩn mã nguồn** | `vendor/bin/pint --format agent` | Tự động định dạng toàn bộ file PHP theo quy chuẩn PSR-12 của Laravel |
| **Xóa bộ nhớ đệm cache** | `php artisan optimize:clear` | Xóa sạch cache cấu hình, route, view khi cập nhật nội dung hệ thống |
