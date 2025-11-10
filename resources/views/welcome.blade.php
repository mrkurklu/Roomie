<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Roomie - Otel İletişim Portalı</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased second-color text-foreground">
    <!-- Topbar -->
    <header class="sticky top-0 z-50 w-full first-color border-b border-white/10 shadow-lg">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 lg:px-6 xl:px-8">
            <div class="flex h-14 sm:h-16 items-center justify-between">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <i data-lucide="concierge-bell" class="w-5 h-5 sm:w-6 sm:h-6 text-white flex-shrink-0"></i>
                    <span class="font-semibold text-base sm:text-lg lg:text-xl text-white whitespace-nowrap">Roomie</span>
                    <span class="hidden sm:inline-block ml-2 px-2 py-0.5 text-xs font-medium rounded bg-third-color text-white">Beta</span>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <a href="{{ route('login') }}" class="px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium bg-third-color text-white hover:bg-third-color/90 transition-all duration-200 shadow-md hover:shadow-lg">
                        Giriş Yap
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative py-12 sm:py-16 md:py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center space-y-6 sm:space-y-8">
                <div class="flex items-center justify-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full bg-first-color/10 flex items-center justify-center">
                        <i data-lucide="concierge-bell" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-first-color"></i>
                    </div>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight text-third-color">
                        Roomie
                    </h1>
                    <p class="text-xl sm:text-2xl md:text-3xl text-gray-700 font-medium">
                        Modern Otel İletişim Portalı
                    </p>
                    <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-3xl mx-auto pt-2 sm:pt-4 px-4">
                        Konforlu konaklama deneyiminiz için tasarlanmış, modern ve kullanıcı dostu otel yönetim sistemi. 
                        Misafirlerinizle iletişim kurun, talepleri yönetin ve hizmet kalitesini artırın.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center pt-4 sm:pt-6 px-4">
                    <a href="{{ route('login') }}" class="px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-sm sm:text-base font-semibold bg-first-color text-white hover:bg-first-color/90 transition-all duration-200 shadow-lg hover:shadow-xl inline-flex items-center justify-center gap-2">
                        Giriş Yap
                        <i data-lucide="arrow-right" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center space-y-3 sm:space-y-4 mb-10 sm:mb-12 md:mb-16">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-third-color">Roomie Hakkında</h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-3xl mx-auto px-4">
                    Otel yönetimini kolaylaştıran, misafir deneyimini iyileştiren kapsamlı bir platform
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-10 md:gap-12 items-start">
                <div class="space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="users" class="w-6 h-6 text-primary"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-third-color mb-2">Çoklu Rol Sistemi</h3>
                                <p class="text-muted-foreground">
                                    Yönetim, Personel ve Misafir panelleri ile herkes için özelleştirilmiş deneyim. 
                                    Her rol için uygun yetkiler ve özellikler.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="message-square" class="w-6 h-6 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-third-color mb-2">Çok Dilli Chat Sistemi</h3>
                            <p class="text-muted-foreground">
                                Otomatik çeviri ile misafir-personel iletişimi. 30+ dil desteği ile 
                                dünya çapında misafirlerinizle sorunsuz iletişim kurun.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="clipboard-list" class="w-6 h-6 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-third-color mb-2">Görev Yönetimi</h3>
                            <p class="text-muted-foreground">
                                Görev oluşturma, atama ve takip sistemi. Personel verimliliğini artırın 
                                ve iş akışını optimize edin.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="calendar" class="w-6 h-6 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-third-color mb-2">Etkinlik Yönetimi</h3>
                            <p class="text-muted-foreground">
                                Otel etkinliklerini oluşturun, yönetin ve misafirlerinize duyurun. 
                                Etkinlik detaylarını kolayca paylaşın.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="trending-up" class="w-6 h-6 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-third-color mb-2">Dashboard & Analitik</h3>
                            <p class="text-muted-foreground">
                                Gerçek zamanlı istatistikler ve grafikler. Otel performansını izleyin 
                                ve veriye dayalı kararlar alın.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="bell" class="w-6 h-6 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-third-color mb-2">Bildirim Sistemi</h3>
                            <p class="text-muted-foreground">
                                Gerçek zamanlı bildirimler ile önemli güncellemelerden haberdar olun. 
                                Anında bildirimler ve hatırlatmalar.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="moon" class="w-6 h-6 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-third-color mb-2">Dark Mode</h3>
                            <p class="text-muted-foreground">
                                Karanlık/Açık tema desteği. Göz yormayan, modern bir arayüz deneyimi.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="smartphone" class="w-6 h-6 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-third-color mb-2">Responsive Tasarım</h3>
                            <p class="text-muted-foreground">
                                Mobil uyumlu arayüz. Her cihazda mükemmel görünüm ve kullanım deneyimi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-second-color">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center space-y-3 sm:space-y-4 mb-10 sm:mb-12 md:mb-16">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-third-color">Özellikler</h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-3xl mx-auto px-4">
                    Roomie ile otel yönetimini kolaylaştıran özellikler
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <x-ui.card class="border-none shadow-lg text-center hover:shadow-xl transition-shadow">
                    <x-ui.card-content class="p-8">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="wifi" class="w-8 h-8 text-primary"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-third-color mb-3">Ücretsiz Wi-Fi</h3>
                        <p class="text-muted-foreground">
                            Tüm alanlarda yüksek hızlı internet erişimi
                        </p>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="border-none shadow-lg text-center hover:shadow-xl transition-shadow">
                    <x-ui.card-content class="p-8">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="concierge-bell" class="w-8 h-8 text-primary"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-third-color mb-3">7/24 Concierge</h3>
                        <p class="text-muted-foreground">
                            Her zaman yanınızda olan destek ekibimiz
                        </p>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="border-none shadow-lg text-center hover:shadow-xl transition-shadow">
                    <x-ui.card-content class="p-8">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="utensils-crossed" class="w-8 h-8 text-primary"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-third-color mb-3">Restoran & Oda Servisi</h3>
                        <p class="text-muted-foreground">
                            Lezzetli yemekler ve oda servisi
                        </p>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 md:py-20 first-color">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center space-y-6 sm:space-y-8">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white">
                    Hemen Başlayın
                </h2>
                <p class="text-base sm:text-lg md:text-xl text-white/90 max-w-2xl mx-auto px-4">
                    Roomie ile otel yönetimini modernize edin ve misafir deneyimini iyileştirin
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center pt-4 px-4">
                    <a href="{{ route('login') }}" class="px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-sm sm:text-base font-semibold bg-third-color text-white hover:bg-third-color/90 transition-all duration-200 shadow-lg hover:shadow-xl inline-flex items-center justify-center gap-2">
                        Giriş Yap
                        <i data-lucide="arrow-right" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/10 py-8 sm:py-10 md:py-12 first-color">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center space-y-3 sm:space-y-4">
                <div class="flex items-center justify-center gap-2 sm:gap-3 text-white">
                    <i data-lucide="concierge-bell" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    <span class="font-semibold text-base sm:text-lg">Roomie</span>
                </div>
                <p class="text-xs sm:text-sm text-white/80">
                    © {{ date('Y') }} Roomie. Tüm hakları saklıdır.
                </p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
