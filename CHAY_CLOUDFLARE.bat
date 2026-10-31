@echo off
chcp 65001 >nul
title TOAN CAU TECHNOLOGY - CLOUDFLARE TUNNEL
echo ===================================================================
echo     TOÀN CẦU TECHNOLOGY B2B - CHẠY TRÊN CLOUDFLARE TUNNEL
echo ===================================================================
echo.
echo  [1/2] Đang khởi động máy chủ Laravel nội bộ...
start "Laravel Server (Khong tat cua so nay)" cmd /c "chcp 65001 >nul && title Laravel Server && php\php.exe artisan serve --host=127.0.0.1 --port=8000"

echo  [2/2] Đang kết nối mạng toàn cầu Cloudflare...
echo.
echo  -------------------------------------------------------------------
echo  HÃY TÌM DÒNG LINK CÓ DẠNG:
echo    https://xxxx.trycloudflare.com
echo.
echo  Copy link đó gửi cho bất kỳ ai (trên điện thoại hoặc máy tính) 
echo  để truy cập website ngay lập tức qua Cloudflare!
echo  -------------------------------------------------------------------
echo.

"%~dp0cloudflared.exe" tunnel --url http://127.0.0.1:8000
pause
