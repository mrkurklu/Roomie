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
        <!-- Başlık -->
        <x-ui.card class="border-2 border-white/20 shadow-sm bg-gradient-to-br from-primary/10 via-background to-secondary/10">
            <x-ui.card-content class="p-8">
                <div class="text-center space-y-4">
                    <div class="flex items-center justify-center">
                        <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center">
                            <i data-lucide="sparkles" class="w-10 h-10 text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2 text-third-color">
                            Otel Hizmetlerimiz
                        </h1>
                        <p class="text-lg text-white/90 max-w-2xl mx-auto">
                            Konforlu konaklama deneyiminiz için sunduğumuz tüm hizmetler
                        </p>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Otel İmkanları -->
        @if($hotel && $hotel->amenities && count($hotel->amenities) > 0)
        <x-ui.card class="border-2 border-white/20 shadow-sm">
            <x-ui.card-header class="pb-4">
                <x-ui.card-title class="flex items-center gap-2 text-white">
                    <i data-lucide="sparkles" class="w-5 h-5 text-white"></i>
                    Otel İmkanları
                </x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($hotel->amenities as $amenity)
                    <a href="{{ route('guest.requests') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-3 p-4 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 h-32">
                            <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                                @php
                                    $icons = [
                                        'Wi-Fi' => 'wifi',
                                        'WiFi' => 'wifi',
                                        'Spa' => 'sparkles',
                                        'Fitness' => 'dumbbell',
                                        'Gym' => 'dumbbell',
                                        'Havuz' => 'waves',
                                        'Pool' => 'waves',
                                        'Restoran' => 'utensils-crossed',
                                        'Restaurant' => 'utensils-crossed',
                                        'Parking' => 'car',
                                        'Otopark' => 'car',
                                        'Bar' => 'wine',
                                        'Room Service' => 'concierge-bell',
                                        'Oda Servisi' => 'concierge-bell',
                                        'Laundry' => 'shirt',
                                        'Çamaşır' => 'shirt',
                                    ];
                                    $icon = 'sparkles';
                                    foreach($icons as $key => $value) {
                                        if(stripos($amenity, $key) !== false) {
                                            $icon = $value;
                                            break;
                                        }
                                    }
                                @endphp
                                <i data-lucide="{{ $icon }}" class="w-8 h-8 text-first-color dark:text-blue-400 drop-shadow-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-center leading-tight text-first-color dark:text-blue-400">{{ $amenity }}</span>
                        </button>
                    </a>
                    @endforeach
                </div>
            </x-ui.card-content>
        </x-ui.card>
        @else
        <!-- Varsayılan İmkanlar -->
        <x-ui.card class="border-2 border-white/20 shadow-sm">
            <x-ui.card-header class="pb-4">
                <x-ui.card-title class="flex items-center gap-2 text-white">
                    <i data-lucide="sparkles" class="w-5 h-5 text-white"></i>
                    Otel İmkanları
                </x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    <a href="{{ route('guest.requests') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-3 p-4 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 h-32">
                            <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="wifi" class="w-8 h-8 text-first-color dark:text-blue-400 drop-shadow-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-center leading-tight text-first-color dark:text-blue-400">Ücretsiz Wi-Fi</span>
                        </button>
                    </a>
                    <a href="{{ route('guest.requests') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-3 p-4 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 h-32">
                            <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="concierge-bell" class="w-8 h-8 text-first-color dark:text-blue-400 drop-shadow-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-center leading-tight text-first-color dark:text-blue-400">7/24 Concierge</span>
                        </button>
                    </a>
                    <a href="{{ route('guest.requests') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-3 p-4 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 h-32">
                            <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="utensils-crossed" class="w-8 h-8 text-first-color dark:text-blue-400 drop-shadow-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-center leading-tight text-first-color dark:text-blue-400">Restoran & Oda Servisi</span>
                        </button>
                    </a>
                    <a href="{{ route('guest.requests') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-3 p-4 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 h-32">
                            <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="sparkles" class="w-8 h-8 text-first-color dark:text-blue-400 drop-shadow-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-center leading-tight text-first-color dark:text-blue-400">Spa & Wellness</span>
                        </button>
                    </a>
                </div>
            </x-ui.card-content>
        </x-ui.card>
        @endif

        <!-- Hizmet Kategorileri -->
        <x-ui.card class="border-2 border-white/20 shadow-sm">
            <x-ui.card-header class="pb-4">
                <x-ui.card-title class="flex items-center gap-2 text-white">
                    <i data-lucide="grid" class="w-5 h-5 text-white"></i>
                    Hizmet Kategorileri
                </x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Oda Servisi -->
                    <a href="{{ route('guest.requests') }}" class="block">
                        <div class="p-6 rounded-lg border border-white/20 bg-white/10 hover:bg-white/20 transition-colors cursor-pointer">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="utensils-crossed" class="w-7 h-7 text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg mb-2 text-white">Oda Servisi</h3>
                                    <p class="text-sm text-white/80 mb-3">
                                        Odanıza yemek ve içecek siparişi verebilir, 7/24 oda servisi hizmetimizden yararlanabilirsiniz.
                                    </p>
                                    <button type="button" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 text-first-color dark:text-blue-400 font-medium">
                                        Talep Oluştur
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Temizlik Hizmeti -->
                    <a href="{{ route('guest.requests') }}" class="block">
                        <div class="p-6 rounded-lg border border-white/20 bg-white/10 hover:bg-white/20 transition-colors cursor-pointer">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="sparkles" class="w-7 h-7 text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg mb-2 text-white">Temizlik Hizmeti</h3>
                                    <p class="text-sm text-white/80 mb-3">
                                        Odanızın temizlenmesi, yatak takımlarının değiştirilmesi ve ekstra temizlik talepleriniz için.
                                    </p>
                                    <button type="button" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 text-first-color dark:text-blue-400 font-medium">
                                        Talep Oluştur
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Bakım & Onarım -->
                    <a href="{{ route('guest.requests') }}" class="block">
                        <div class="p-6 rounded-lg border border-white/20 bg-white/10 hover:bg-white/20 transition-colors cursor-pointer">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="wrench" class="w-7 h-7 text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg mb-2 text-white">Bakım & Onarım</h3>
                                    <p class="text-sm text-white/80 mb-3">
                                        Odanızdaki teknik sorunlar, arızalar ve bakım talepleri için hızlı çözüm.
                                    </p>
                                    <button type="button" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 text-first-color dark:text-blue-400 font-medium">
                                        Talep Oluştur
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Concierge Hizmetleri -->
                    <a href="{{ route('guest.requests') }}" class="block">
                        <div class="p-6 rounded-lg border border-white/20 bg-white/10 hover:bg-white/20 transition-colors cursor-pointer">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="concierge-bell" class="w-7 h-7 text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg mb-2 text-white">Concierge Hizmetleri</h3>
                                    <p class="text-sm text-white/80 mb-3">
                                        Restoran rezervasyonu, ulaşım, turistik bilgiler ve özel talepleriniz için.
                                    </p>
                                    <button type="button" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 text-first-color dark:text-blue-400 font-medium">
                                        Talep Oluştur
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Hızlı Erişim -->
        <x-ui.card class="border-2 border-white/20 shadow-sm">
            <x-ui.card-header class="pb-4">
                <x-ui.card-title class="flex items-center gap-2 text-white">
                    <i data-lucide="zap" class="w-5 h-5 text-white"></i>
                    Hızlı Erişim
                </x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('guest.chat') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-4 p-6 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 h-32">
                            <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="message-square" class="w-7 h-7 text-first-color dark:text-blue-400 drop-shadow-lg"></i>
                            </div>
                            <div class="text-center">
                                <div class="text-base font-semibold text-first-color dark:text-blue-400">Canlı Sohbet</div>
                                <div class="text-sm text-first-color/70 dark:text-blue-400/70 mt-1">Sorularınız için</div>
                            </div>
                        </button>
                    </a>
                    <a href="{{ route('guest.requests') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-4 p-6 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 h-32">
                            <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="concierge-bell" class="w-7 h-7 text-first-color dark:text-blue-400 drop-shadow-lg"></i>
                            </div>
                            <div class="text-center">
                                <div class="text-base font-semibold text-first-color dark:text-blue-400">Taleplerim</div>
                                <div class="text-sm text-first-color/70 dark:text-blue-400/70 mt-1">Hizmet talepleri</div>
                            </div>
                        </button>
                    </a>
                    <a href="{{ route('guest.services') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-4 p-6 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 h-32">
                            <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="sparkles" class="w-7 h-7 text-first-color dark:text-blue-400 drop-shadow-lg"></i>
                            </div>
                            <div class="text-center">
                                <div class="text-base font-semibold text-first-color dark:text-blue-400">Hizmetler</div>
                                <div class="text-sm text-first-color/70 dark:text-blue-400/70 mt-1">Otel hizmetleri</div>
                            </div>
                        </button>
                    </a>
                </div>
        </x-ui.card-content>
    </x-ui.card>
</div>
@endsection
