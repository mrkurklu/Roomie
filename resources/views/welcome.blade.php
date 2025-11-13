<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': dark }" x-init="if (dark) $el.classList.add('dark')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Roomie - Otel İletişim Portalı</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark transition-colors duration-300">
    <!-- Topbar -->
    <header class="sticky top-0 z-50 w-full bg-surface-light dark:bg-surface-dark border-b border-primary-light/10 dark:border-primary-dark/10 shadow-lg backdrop-blur-sm">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 lg:px-6 xl:px-8">
            <div class="flex h-14 sm:h-16 items-center justify-between">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <i data-lucide="concierge-bell" class="w-5 h-5 sm:w-6 sm:h-6 text-primary-light dark:text-primary-dark flex-shrink-0"></i>
                    <span class="font-semibold text-base sm:text-lg lg:text-xl text-text-light dark:text-text-dark whitespace-nowrap">Roomie</span>
                    <span class="hidden sm:inline-block ml-2 px-2 py-0.5 text-xs font-medium rounded bg-primary-light dark:bg-primary-dark text-white">Beta</span>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <button @click="dark = !dark; localStorage.setItem('darkMode', dark)" class="p-2 rounded-lg hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors">
                        <i data-lucide="moon" x-show="!dark" class="w-5 h-5 text-text-light dark:text-text-dark"></i>
                        <i data-lucide="sun" x-show="dark" class="w-5 h-5 text-text-light dark:text-text-dark"></i>
                    </button>
                    <a href="{{ route('login') }}" class="px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-all duration-200 shadow-md hover:shadow-lg">
                        Giriş Yap
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative py-12 sm:py-16 md:py-20 lg:py-24 bg-gradient-to-br from-primary-light/5 dark:from-primary-dark/5 via-background-light dark:via-background-dark to-secondary-light/5 dark:to-secondary-dark/5">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center space-y-6 sm:space-y-8">
                <div class="flex items-center justify-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center">
                        <i data-lucide="concierge-bell" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-primary-light dark:text-primary-dark"></i>
                    </div>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight text-text-light dark:text-text-dark">
                        Roomie
                    </h1>
                    <p class="text-xl sm:text-2xl md:text-3xl text-text-light/70 dark:text-text-dark/70 font-medium">
                        Modern Otel İletişim Portalı
                    </p>
                    <p class="text-base sm:text-lg md:text-xl text-text-light/60 dark:text-text-dark/60 max-w-3xl mx-auto pt-2 sm:pt-4 px-4">
                        Konforlu konaklama deneyiminiz için tasarlanmış, modern ve kullanıcı dostu otel yönetim sistemi. 
                        Misafirlerinizle iletişim kurun, talepleri yönetin ve hizmet kalitesini artırın.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center pt-4 sm:pt-6 px-4">
                    <a href="{{ route('login') }}" class="px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-sm sm:text-base font-semibold bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl inline-flex items-center justify-center gap-2">
                        Giriş Yap
                        <i data-lucide="arrow-right" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-surface-light dark:bg-surface-dark">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center space-y-3 sm:space-y-4 mb-10 sm:mb-12 md:mb-16">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-text-light dark:text-text-dark">Roomie Hakkında</h2>
                <p class="text-base sm:text-lg md:text-xl text-text-light/60 dark:text-text-dark/60 max-w-3xl mx-auto px-4">
                    Otel yönetimini kolaylaştıran, misafir deneyimini iyileştiren kapsamlı bir platform
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-10 md:gap-12 items-start">
                <div class="space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="users" class="w-6 h-6 text-primary-light dark:text-primary-dark"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-2">Çoklu Rol Sistemi</h3>
                                <p class="text-text-light/60 dark:text-text-dark/60">
                                    Yönetim, Personel ve Misafir panelleri ile herkes için özelleştirilmiş deneyim. 
                                    Her rol için uygun yetkiler ve özellikler.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="message-square" class="w-6 h-6 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-2">Çok Dilli Chat Sistemi</h3>
                            <p class="text-text-light/60 dark:text-text-dark/60">
                                Otomatik çeviri ile misafir-personel iletişimi. 30+ dil desteği ile 
                                dünya çapında misafirlerinizle sorunsuz iletişim kurun.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="clipboard-list" class="w-6 h-6 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-2">Görev Yönetimi</h3>
                            <p class="text-text-light/60 dark:text-text-dark/60">
                                Görev oluşturma, atama ve takip sistemi. Personel verimliliğini artırın 
                                ve iş akışını optimize edin.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="calendar" class="w-6 h-6 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-2">Etkinlik Yönetimi</h3>
                            <p class="text-text-light/60 dark:text-text-dark/60">
                                Otel etkinliklerini oluşturun, yönetin ve misafirlerinize duyurun. 
                                Etkinlik detaylarını kolayca paylaşın.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="trending-up" class="w-6 h-6 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-2">Dashboard & Analitik</h3>
                            <p class="text-text-light/60 dark:text-text-dark/60">
                                Gerçek zamanlı istatistikler ve grafikler. Otel performansını izleyin 
                                ve veriye dayalı kararlar alın.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="bell" class="w-6 h-6 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-2">Bildirim Sistemi</h3>
                            <p class="text-text-light/60 dark:text-text-dark/60">
                                Gerçek zamanlı bildirimler ile önemli güncellemelerden haberdar olun. 
                                Anında bildirimler ve hatırlatmalar.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="moon" class="w-6 h-6 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-2">Dark Mode</h3>
                            <p class="text-text-light/60 dark:text-text-dark/60">
                                Karanlık/Açık tema desteği. Göz yormayan, modern bir arayüz deneyimi.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="smartphone" class="w-6 h-6 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-2">Responsive Tasarım</h3>
                            <p class="text-text-light/60 dark:text-text-dark/60">
                                Mobil uyumlu arayüz. Her cihazda mükemmel görünüm ve kullanım deneyimi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-background-light dark:bg-background-dark">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center space-y-3 sm:space-y-4 mb-10 sm:mb-12 md:mb-16">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-text-light dark:text-text-dark">Özellikler</h2>
                <p class="text-base sm:text-lg md:text-xl text-text-light/60 dark:text-text-dark/60 max-w-3xl mx-auto px-4">
                    Roomie ile otel yönetimini kolaylaştıran özellikler
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-lg text-center hover:shadow-xl transition-shadow bg-surface-light dark:bg-surface-dark">
                    <x-ui.card-content class="p-8">
                        <div class="w-16 h-16 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="wifi" class="w-8 h-8 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-3">Ücretsiz Wi-Fi</h3>
                        <p class="text-text-light/60 dark:text-text-dark/60">
                            Tüm alanlarda yüksek hızlı internet erişimi
                        </p>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-lg text-center hover:shadow-xl transition-shadow bg-surface-light dark:bg-surface-dark">
                    <x-ui.card-content class="p-8">
                        <div class="w-16 h-16 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="concierge-bell" class="w-8 h-8 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-3">7/24 Concierge</h3>
                        <p class="text-text-light/60 dark:text-text-dark/60">
                            Her zaman yanınızda olan destek ekibimiz
                        </p>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-lg text-center hover:shadow-xl transition-shadow bg-surface-light dark:bg-surface-dark">
                    <x-ui.card-content class="p-8">
                        <div class="w-16 h-16 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="utensils-crossed" class="w-8 h-8 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-text-light dark:text-text-dark mb-3">Restoran & Oda Servisi</h3>
                        <p class="text-text-light/60 dark:text-text-dark/60">
                            Lezzetli yemekler ve oda servisi
                        </p>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-primary-light dark:bg-primary-dark">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center space-y-6 sm:space-y-8">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white">
                    Hemen Başlayın
                </h2>
                <p class="text-base sm:text-lg md:text-xl text-white/90 max-w-2xl mx-auto px-4">
                    Roomie ile otel yönetimini modernize edin ve misafir deneyimini iyileştirin
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center pt-4 px-4">
                    <a href="{{ route('login') }}" class="px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-sm sm:text-base font-semibold bg-white text-primary-light dark:text-primary-dark hover:opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl inline-flex items-center justify-center gap-2">
                        Giriş Yap
                        <i data-lucide="arrow-right" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-primary-light/10 dark:border-primary-dark/10 py-8 sm:py-10 md:py-12 bg-surface-light dark:bg-surface-dark">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center space-y-3 sm:space-y-4">
                <div class="flex items-center justify-center gap-2 sm:gap-3 text-text-light dark:text-text-dark">
                    <i data-lucide="concierge-bell" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    <span class="font-semibold text-base sm:text-lg">Roomie</span>
                </div>
                <p class="text-xs sm:text-sm text-text-light/60 dark:text-text-dark/60">
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
            
            // Dark mode initialization
            const darkMode = localStorage.getItem('darkMode') === 'true';
            if (darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
</body>
</html>
