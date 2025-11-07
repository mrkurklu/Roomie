# GitHub'a Yükleme Adımları

Projeyi GitHub'a yüklemek için aşağıdaki komutları terminalde çalıştırın:

## 1. Git deposunu başlatın (eğer henüz başlatılmadıysa)

```bash
git init
```

## 2. Tüm dosyaları ekleyin

```bash
git add .
```

## 3. İlk commit'i yapın

```bash
git commit -m "Initial commit: Roomie Hotel Management System"
```

## 4. GitHub remote'unu ekleyin

```bash
git remote add origin https://github.com/mrkurklu/Roomie.git
```

## 5. Ana branch'i main olarak ayarlayın (eğer gerekirse)

```bash
git branch -M main
```

## 6. GitHub'a push edin

```bash
git push -u origin main
```

## Notlar

- Eğer GitHub'da zaten dosyalar varsa, önce pull yapmanız gerekebilir:
```bash
git pull origin main --allow-unrelated-histories
```

- `.env` dosyası `.gitignore`'da olduğu için yüklenmeyecek (güvenlik için)
- `vendor/` ve `node_modules/` klasörleri de yüklenmeyecek

