@echo off
chcp 65001 >nul
echo ========================================================
echo   TOÀN CẦU B2B CMS - AUTO SETUP SCRIPT (WINDOWS)
echo ========================================================
echo.

if not exist .env (
    echo [1/5] Dang tao file .env tu .env.example...
    copy .env.example .env
) else (
    echo [1/5] File .env da ton tai. Bo qua.
)

echo.
echo [2/5] Dang cai dat cac goi Composer PHP...
call composer install

echo.
echo [3/5] Dang tao Application Key va Storage Link...
call php artisan key:generate
call php artisan storage:link

if not exist database\database.sqlite (
    echo.
    echo [4/5] Dang tao database SQLite va nap du lieu mau...
    type nul > database\database.sqlite
    call php artisan migrate --seed
) else (
    echo.
    echo [4/5] Database SQLite da co san.
)

echo.
echo [5/5] Dang cai dat Node packages va bien dich giao dien...
call npm install
call npm run build

echo.
echo ========================================================
echo   CÀI ĐẶT HOÀN TẤT THÀNH CÔNG!
echo   Khởi chạy website bằng lệnh:
echo     php artisan serve
echo   Truy cập: http://127.0.0.1:8000
echo   Admin: admin@toancau.vn / Password@123
echo ========================================================
pause
