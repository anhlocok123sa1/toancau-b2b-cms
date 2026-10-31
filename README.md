# TOÀN CẦU TECHNOLOGY - B2B ENTERPRISE PORTAL & CMS

Hệ thống Cổng Thông Tin Doanh Nghiệp B2B & Quản Trị Nội Dung Toàn Cầu Technology (Dedicated Server, Cloud VPS, IP Clean, Starlink, Smart IoT, Bluecom 4G & Dịch Vụ Kỹ Thuật).

---

## 🛠️ Yêu Cầu Môi Trường (Prerequisites)

Trước khi chạy, máy tính cần cài đặt sẵn:
- **PHP** >= 8.2 (Bật các extension: `pdo_sqlite`, `curl`, `mbstring`, `openssl`, `xml`, `zip`)
- **Composer** (Quản lý thư viện PHP)
- **Node.js** >= 18.x & **npm** (Quản lý thư viện frontend & build Vite)
- *(Không cần cài MySQL vì hệ thống sử dụng SQLite gọn nhẹ, chạy được ngay).*

---

## 🚀 Hướng Dẫn Cài Đặt & Chạy Dự Án (Setup Guide)

### Bước 1: Mở thư mục dự án
Mở Terminal (hoặc PowerShell trên Windows) và chuyển đến thư mục dự án:
```bash
cd toancau-b2b-cms
```

### Bước 2: Tạo file cấu hình môi trường `.env`
- Trên **Windows (PowerShell / CMD)**:
  ```bash
  copy .env.example .env
  ```
- Trên **macOS / Linux**:
  ```bash
  cp .env.example .env
  ```

### Bước 3: Cài đặt thư viện Backend (PHP / Composer)
```bash
composer install
```

### Bước 4: Tạo Application Key & Storage Link
```bash
php artisan key:generate
php artisan storage:link
```

### Bước 5: Thiết lập Cơ sở dữ liệu (SQLite)
- **Trường hợp 1**: Nếu đã có sẵn file `database/database.sqlite` gửi kèm:
  * Không cần tạo mới, toàn bộ dữ liệu mẫu đã có sẵn.
- **Trường hợp 2**: Nếu tạo mới hoặc muốn reset dữ liệu từ đầu:
  * Windows PowerShell: `New-Item database/database.sqlite -ItemType File -Force`
  * macOS / Linux: `touch database/database.sqlite`
  * Chạy lệnh tạo bảng và nạp dữ liệu mẫu B2B Toàn Cầu:
    ```bash
    php artisan migrate --seed
    ```

### Bước 6: Cài đặt thư viện Frontend & Biên dịch giao diện
```bash
npm install
npm run build
```
*(Hoặc dùng `npm run dev` nếu muốn vừa sửa code frontend vừa xem thay đổi tức thì).*

### Bước 7: Khởi chạy Website
```bash
php artisan serve
```
Mở trình duyệt truy cập:
👉 **Website Công Khai**: [http://127.0.0.1:8000](http://127.0.0.1:8000)  
👉 **Trang Đăng Nhập Quản Trị**: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)

---

## 🔐 Danh Sách Tài Khoản Đăng Nhập Hệ Thống

Tất cả tài khoản đều sử dụng chung mật khẩu mặc định: `Password@123`

| Vai trò (Role) | Email đăng nhập | Mật khẩu | Quyền hạn chính |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@toancau.vn` | `Password@123` | Toàn quyền quản trị: Lead, Sản phẩm, Checklist Pháp lý, Cài đặt |
| **Content Admin** | `content@toancau.vn` | `Password@123` | Quản trị Sản phẩm, Danh mục, Banner, Tin tức, Dự án (không xem được Lead) |
| **Sales** | `sales@toancau.vn` | `Password@123` | Quản trị luồng xử lý Yêu cầu báo giá, Quy trình hợp đồng & Khách hàng |
| **Partner Manager** | `partner@toancau.vn` | `Password@123` | Quản lý đối tác và đại lý phân phối |
| **Viewer** | `viewer@toancau.vn` | `Password@123` | Chỉ xem dữ liệu báo cáo (chế độ Read-only, không thể chỉnh sửa) |

---

## 📦 Lưu Ý Khi Đóng Gói (Nén File) Để Gửi Cho Người Khác

Khi nén dự án thành file `.zip` để gửi:
1. **XÓA HOẶC KHÔNG CHỌN 2 thư mục**:
   - `vendor/` (Thư viện PHP - giải nén rất nặng)
   - `node_modules/` (Thư viện Node.js - rất nặng)
   *(Người nhận chỉ cần chạy `composer install` và `npm install` là máy tính sẽ tự tải về đúng phiên bản tương thích với hệ điều hành của họ).*
2. **NÊN gửi kèm**:
   - Thư mục `database/` (đặc biệt là file `database.sqlite` nếu muốn giữ nguyên toàn bộ dữ liệu đã nhập).
   - Thư mục `public/images/` (có chứa `logo.png`).
