@echo off
cd /d "%~dp0"

where php >nul 2>nul
if errorlevel 1 (
    echo PHP tidak ditemukan di PATH.
    echo Install PHP atau jalankan dari terminal yang sudah punya perintah php.
    pause
    exit /b 1
)

echo Menjalankan preview lokal di http://127.0.0.1:8080/preview-all-elements.php
echo Tekan Ctrl+C untuk menghentikan server.
php -S 127.0.0.1:8080 -t .