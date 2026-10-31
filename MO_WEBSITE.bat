@echo off
chcp 65001 >nul
title TOAN CAU TECHNOLOGY - B2B ENTERPRISE PORTAL
echo ========================================================
echo   HE THONG TOAN CAU TECHNOLOGY B2B CMS DANG KHOI DONG...
echo.
echo   Dia chi Website: http://127.0.0.1:8000
echo   Dang nhap Admin: http://127.0.0.1:8000/login
echo   Tai khoan:       admin@toancau.vn
echo   Mat khau:        Password@123
echo.
echo   Trinh duyet se tu dong mo len sau vai giay...
echo   (Luu y: Giu cua so nay mo trong suot qua trinh xem web)
echo ========================================================
echo.

start "" "http://127.0.0.1:8000"
"%~dp0php\php.exe" artisan serve --host=127.0.0.1 --port=8000
pause
