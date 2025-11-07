@echo off
echo Git deposu baslatiliyor...
git init

echo Dosyalar ekleniyor...
git add .

echo Commit yapiliyor...
git commit -m "Initial commit: Roomie Hotel Management System"

echo Remote ekleniyor...
git remote add origin https://github.com/mrkurklu/Roomie.git 2>nul
if errorlevel 1 (
    echo Remote zaten mevcut, guncelleniyor...
    git remote set-url origin https://github.com/mrkurklu/Roomie.git
)

echo Branch main olarak ayarlaniyor...
git branch -M main

echo GitHub'a push ediliyor...
git push -u origin main

echo Tamamlandi!
pause

