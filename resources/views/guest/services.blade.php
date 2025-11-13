@extends('layouts.portal')

@section('title', 'Hizmetlerimiz - Misafir Paneli')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush

@section('sidebar')
    @include('guest.partials.sidebar', ['active' => 'services'])
@endsection

@section('content')
<div class="w-full space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl sm:text-4xl font-bold text-text-light dark:text-text-dark">Otel Hizmetlerimiz</h1>
        <p class="text-sm sm:text-base text-text-light/70 dark:text-text-dark/70 mt-1">
            Konaklamanızı daha keyifli hale getirecek tüm olanakları keşfedin.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Sidebar - Categories -->
        <div class="lg:col-span-1">
            <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark sticky top-4">
                <x-ui.card-header class="pb-3">
                    <x-ui.card-title class="text-text-light dark:text-text-dark">Kategoriler</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-1">
                    <button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white text-sm font-medium transition-colors">
                        <i data-lucide="grid" class="w-4 h-4"></i>
                        <span>Tümü</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-sm transition-colors">
                        <i data-lucide="leaf" class="w-4 h-4"></i>
                        <span>Spa & Wellness</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-sm transition-colors">
                        <i data-lucide="utensils-crossed" class="w-4 h-4"></i>
                        <span>Restoran & Barlar</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-sm transition-colors">
                        <i data-lucide="concierge-bell" class="w-4 h-4"></i>
                        <span>Oda Servisi</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-sm transition-colors">
                        <i data-lucide="bike" class="w-4 h-4"></i>
                        <span>Aktivite & Turlar</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-sm transition-colors">
                        <i data-lucide="building" class="w-4 h-4"></i>
                        <span>Toplantı & Etkinlik</span>
                    </button>
                </x-ui.card-content>
            </x-ui.card>
        </div>

        <!-- Main Content - Service Cards -->
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Service Card 1 -->
                <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark hover:shadow-lg transition-shadow cursor-pointer">
                    <x-ui.card-content class="p-6">
                        <div class="w-16 h-16 rounded-full bg-primary-light/10 dark:bg-primary-dark/20 flex items-center justify-center mb-4 mx-auto">
                            <i data-lucide="leaf" class="w-8 h-8 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <h3 class="text-lg font-bold text-text-light dark:text-text-dark mb-2 text-center">Bali Masajı</h3>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70 mb-4 text-center">
                            Geleneksel Bali teknikleriyle rahatlayın ve stresten arının.
                        </p>
                        <button class="w-full px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium">
                            Detayları İncele
                        </button>
                    </x-ui.card-content>
                </x-ui.card>

                <!-- Service Card 2 -->
                <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark hover:shadow-lg transition-shadow cursor-pointer">
                    <x-ui.card-content class="p-6">
                        <div class="w-16 h-16 rounded-full bg-primary-light/10 dark:bg-primary-dark/20 flex items-center justify-center mb-4 mx-auto">
                            <i data-lucide="utensils-crossed" class="w-8 h-8 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <h3 class="text-lg font-bold text-text-light dark:text-text-dark mb-2 text-center">Açık Büfe Kahvaltı</h3>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70 mb-4 text-center">
                            Güne zengin ve taze lezzetlerle dolu bir başlangıç yapın.
                        </p>
                        <button class="w-full px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium">
                            Menüyü Gör
                        </button>
                    </x-ui.card-content>
                </x-ui.card>

                <!-- Service Card 3 -->
                <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark hover:shadow-lg transition-shadow cursor-pointer">
                    <x-ui.card-content class="p-6">
                        <div class="w-16 h-16 rounded-full bg-primary-light/10 dark:bg-primary-dark/20 flex items-center justify-center mb-4 mx-auto">
                            <i data-lucide="bike" class="w-8 h-8 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <h3 class="text-lg font-bold text-text-light dark:text-text-dark mb-2 text-center">Şehir Bisiklet Turu</h3>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70 mb-4 text-center">
                            Rehber eşliğinde şehrin tarihi dokusunu keşfedin.
                        </p>
                        <button class="w-full px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium">
                            Rezervasyon Yap
                        </button>
                    </x-ui.card-content>
                </x-ui.card>

                <!-- Service Card 4 -->
                <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark hover:shadow-lg transition-shadow cursor-pointer">
                    <x-ui.card-content class="p-6">
                        <div class="w-16 h-16 rounded-full bg-primary-light/10 dark:bg-primary-dark/20 flex items-center justify-center mb-4 mx-auto">
                            <i data-lucide="building" class="w-8 h-8 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <h3 class="text-lg font-bold text-text-light dark:text-text-dark mb-2 text-center">Toplantı Salonu Kiralama</h3>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70 mb-4 text-center">
                            Modern altyapıya sahip salonlarımızda verimli etkinlikler düzenleyin.
                        </p>
                        <button class="w-full px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium">
                            Bilgi Al
                        </button>
                    </x-ui.card-content>
                </x-ui.card>

                <!-- Service Card 5 -->
                <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark hover:shadow-lg transition-shadow cursor-pointer">
                    <x-ui.card-content class="p-6">
                        <div class="w-16 h-16 rounded-full bg-primary-light/10 dark:bg-primary-dark/20 flex items-center justify-center mb-4 mx-auto">
                            <i data-lucide="concierge-bell" class="w-8 h-8 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <h3 class="text-lg font-bold text-text-light dark:text-text-dark mb-2 text-center">7/24 Oda Servisi</h3>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70 mb-4 text-center">
                            Odanızın konforunda lezzetli menümüzün tadını çıkarın.
                        </p>
                        <button class="w-full px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium">
                            Sipariş Ver
                        </button>
                    </x-ui.card-content>
                </x-ui.card>

                <!-- Service Card 6 -->
                <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark hover:shadow-lg transition-shadow cursor-pointer">
                    <x-ui.card-content class="p-6">
                        <div class="w-16 h-16 rounded-full bg-primary-light/10 dark:bg-primary-dark/20 flex items-center justify-center mb-4 mx-auto">
                            <i data-lucide="sparkles" class="w-8 h-8 text-primary-light dark:text-primary-dark"></i>
                        </div>
                        <h3 class="text-lg font-bold text-text-light dark:text-text-dark mb-2 text-center">Sauna & Buhar Odası</h3>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70 mb-4 text-center">
                            Günün yorgunluğunu atmak için sauna ve buhar odamızı ziyaret edin.
                        </p>
                        <button class="w-full px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium">
                            Detayları İncele
                        </button>
                    </x-ui.card-content>
                </x-ui.card>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center gap-2 mt-6">
                <button class="px-3 py-2 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <button class="px-3 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white">1</button>
                <button class="px-3 py-2 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors">2</button>
                <button class="px-3 py-2 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors">3</button>
                <span class="px-2 text-text-light/50 dark:text-text-dark/50">...</span>
                <button class="px-3 py-2 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors">8</button>
                <button class="px-3 py-2 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors">9</button>
                <button class="px-3 py-2 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors">10</button>
                <button class="px-3 py-2 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
