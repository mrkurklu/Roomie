# Roomie - Hotel Management System

Modern bir otel yönetim sistemi. Laravel 9, Blade Templates, Tailwind CSS ve Alpine.js ile geliştirilmiştir.

## Özellikler

- 🏨 **Çoklu Rol Sistemi**: Yönetim, Personel ve Misafir panelleri
- 💬 **Çok Dilli Chat Sistemi**: Otomatik çeviri ile misafir-personel iletişimi
- 🌍 **Çok Dilli Arayüz**: 30+ dil desteği
- 📊 **Dashboard**: Gerçek zamanlı istatistikler ve grafikler
- ✅ **Görev Yönetimi**: Görev oluşturma, atama ve takip
- 📨 **Mesajlaşma**: İç mesajlaşma sistemi
- 🔔 **Bildirimler**: Gerçek zamanlı bildirim sistemi
- 🌙 **Dark Mode**: Karanlık/Açık tema desteği
- 📱 **Responsive Tasarım**: Mobil uyumlu arayüz

## Teknolojiler

- **Backend**: Laravel 9
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Icons**: Lucide Icons
- **Charts**: Chart.js
- **Translation**: statickidz/php-google-translate-free

## Kurulum

### Gereksinimler

- PHP >= 8.0.2
- Composer
- Node.js & NPM
- PostgreSQL (veya MySQL)

### Adımlar

1. **Projeyi klonlayın**
```bash
git clone https://github.com/mrkurklu/Roomie.git
cd Roomie
```

2. **Bağımlılıkları yükleyin**
```bash
composer install
npm install
```

3. **Ortam değişkenlerini ayarlayın**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Veritabanını yapılandırın**
`.env` dosyasında veritabanı bilgilerinizi güncelleyin:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=roomie
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Veritabanını oluşturun**
```bash
php artisan migrate
php artisan db:seed
```

6. **Frontend asset'lerini derleyin**
```bash
npm run build
```

7. **Uygulamayı başlatın**
```bash
php artisan serve
```

Uygulama `http://localhost:8000` adresinde çalışacaktır.

## Kullanım

### Roller

- **Yönetim (superadmin/müdür)**: Tüm sistem yönetimi
- **Personel**: Görevler, mesajlar ve vardiya yönetimi
- **Misafir**: Chat, talep ve geri bildirim

### Dil Ayarları

Kullanıcılar profil ayarlarından dil tercihlerini seçebilir. Seçilen dil tüm arayüz ve chat mesajlarına uygulanır.

## Katkıda Bulunma

1. Fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Commit edin (`git commit -m 'Add some amazing feature'`)
4. Push edin (`git push origin feature/amazing-feature`)
5. Pull Request açın

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır.

## İletişim

Sorularınız için issue açabilirsiniz.
