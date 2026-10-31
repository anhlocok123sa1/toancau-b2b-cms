@echo off
chcp 65001 >nul
echo ========================================================
echo   KHỞI CHẠY HỆ THỐNG TOÀN CẦU TECHNOLOGY B2B
echo   Địa chỉ Website: http://127.0.0.1:8000
echo   Trang Đăng Nhập: http://127.0.0.1:8000/login
echo   Nhấn Ctrl+C để dừng server khi không sử dụng.
echo ========================================================
php artisan serve
