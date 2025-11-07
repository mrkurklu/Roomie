# GitHub'a yükleme scripti
Write-Host "Git deposu başlatılıyor..." -ForegroundColor Green
git init

Write-Host "Dosyalar ekleniyor..." -ForegroundColor Green
git add .

Write-Host "Commit yapılıyor..." -ForegroundColor Green
git commit -m "Initial commit: Roomie Hotel Management System"

Write-Host "Remote ekleniyor..." -ForegroundColor Green
$remoteExists = git remote get-url origin 2>$null
if ($LASTEXITCODE -ne 0) {
    git remote add origin https://github.com/mrkurklu/Roomie.git
} else {
    Write-Host "Remote zaten mevcut, güncelleniyor..." -ForegroundColor Yellow
    git remote set-url origin https://github.com/mrkurklu/Roomie.git
}

Write-Host "Branch main olarak ayarlanıyor..." -ForegroundColor Green
git branch -M main

Write-Host "GitHub'a push ediliyor..." -ForegroundColor Green
git push -u origin main

Write-Host "Tamamlandı!" -ForegroundColor Green

