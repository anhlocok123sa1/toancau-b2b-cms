@echo off
chcp 65001 >nul
cd /d "%~dp0"
title TOAN CAU TECHNOLOGY - CLOUDFLARE TUNNEL

set "PHP_BIN=php"
if exist "%~dp0php\php.exe" (
    set "PHP_BIN=%~dp0php\php.exe"
)

echo ===================================================================
echo     TOÀN CẦU TECHNOLOGY B2B - CHẠY TRÊN CLOUDFLARE TUNNEL
echo ===================================================================
echo.
echo  [1/2] Đang khởi động máy chủ Web nội bộ...
start "Laravel Web Server (GIU CUA SO NAY MO)" cmd /k "chcp 65001 >nul && cd /d ""%~dp0"" && title Laravel Web Server && ""%PHP_BIN%"" artisan serve --host=127.0.0.1 --port=8000"

:: Đợi 2 giây cho server khởi động
timeout /t 2 /nobreak >nul

echo  [2/2] Đang kết nối mạng toàn cầu Cloudflare...
echo.
echo  -------------------------------------------------------------------
echo  HÃY TÌM DÒNG LINK CÓ DẠNG:
echo    https://xxxx.trycloudflare.com
echo.
echo  Copy link đó gửi cho bất kỳ ai (trên điện thoại hoặc máy tính) 
echo  để truy cập website ngay lập tức qua Cloudflare!
echo.
echo  LƯU Ý: Giữ cả 2 cửa sổ màu đen mở trong suốt quá trình xem web!
echo  -------------------------------------------------------------------
echo.

"%~dp0cloudflared.exe" tunnel --url http://127.0.0.1:8000
pause
