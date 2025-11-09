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
        <x-ui.card class="border-none shadow-sm bg-gradient-to-br from-primary/10 via-background to-secondary/10">
            <x-ui.card-content class="p-8">
                <div class="text-center space-y-4">
                    <div class="flex items-center justify-center">
                        <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center">
                            <i data-lucide="sparkles" class="w-10 h-10 text-primary"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            Otel Hizmetlerimiz
                        </h1>
                        <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                            Konforlu konaklama deneyiminiz için sunduğumuz tüm hizmetler
                        </p>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Otel İmkanları -->
        @if($hotel && $hotel->amenities && count($hotel->amenities) > 0)
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-4">
                <x-ui.card-title class="flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                    Otel İmkanları
                </x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($hotel->amenities as $amenity)
                    <button type="button" class="flex flex-col items-center justify-center gap-3 p-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[100px]">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
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
                            <i data-lucide="{{ $icon }}" class="w-6 h-6 text-primary"></i>
                        </div>
                        <span class="text-sm font-medium text-center leading-tight">{{ $amenity }}</span>
                    </button>
                    @endforeach
                </div>
            </x-ui.card-content>
        </x-ui.card>
        @else
        <!-- Varsayılan İmkanlar -->
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-4">
                <x-ui.card-title class="flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                    Otel İmkanları
                </x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    <button type="button" class="flex flex-col items-center justify-center gap-3 p-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[100px]">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="wifi" class="w-6 h-6 text-primary"></i>
                        </div>
                        <span class="text-sm font-medium text-center leading-tight">Ücretsiz Wi-Fi</span>
                    </button>
                    <button type="button" class="flex flex-col items-center justify-center gap-3 p-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[100px]">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="concierge-bell" class="w-6 h-6 text-primary"></i>
                        </div>
                        <span class="text-sm font-medium text-center leading-tight">7/24 Concierge</span>
                    </button>
                    <button type="button" class="flex flex-col items-center justify-center gap-3 p-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[100px]">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="utensils-crossed" class="w-6 h-6 text-primary"></i>
                        </div>
                        <span class="text-sm font-medium text-center leading-tight">Restoran & Oda Servisi</span>
                    </button>
                    <button type="button" class="flex flex-col items-center justify-center gap-3 p-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[100px]">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="sparkles" class="w-6 h-6 text-primary"></i>
                        </div>
                        <span class="text-sm font-medium text-center leading-tight">Spa & Wellness</span>
                    </button>
                </div>
            </x-ui.card-content>
        </x-ui.card>
        @endif

        <!-- Hizmet Kategorileri -->
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-4">
                <x-ui.card-title class="flex items-center gap-2">
                    <i data-lucide="grid" class="w-5 h-5"></i>
                    Hizmet Kategorileri
                </x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Oda Servisi -->
                    <a href="{{ route('guest.requests') }}" class="block">
                        <div class="p-6 rounded-lg border border-border/60 hover:bg-accent/50 transition-colors cursor-pointer">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="utensils-crossed" class="w-7 h-7 text-primary"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg mb-2">Oda Servisi</h3>
                                    <p class="text-sm text-muted-foreground mb-3">
                                        Odanıza yemek ve içecek siparişi verebilir, 7/24 oda servisi hizmetimizden yararlanabilirsiniz.
                                    </p>
                                    <x-ui.button variant="outline" size="sm" class="w-full">
                                        Talep Oluştur
                                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                                    </x-ui.button>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Temizlik Hizmeti -->
                    <a href="{{ route('guest.requests') }}" class="block">
                        <div class="p-6 rounded-lg border border-border/60 hover:bg-accent/50 transition-colors cursor-pointer">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="sparkles" class="w-7 h-7 text-primary"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg mb-2">Temizlik Hizmeti</h3>
                                    <p class="text-sm text-muted-foreground mb-3">
                                        Odanızın temizlenmesi, yatak takımlarının değiştirilmesi ve ekstra temizlik talepleriniz için.
                                    </p>
                                    <x-ui.button variant="outline" size="sm" class="w-full">
                                        Talep Oluştur
                                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                                    </x-ui.button>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Bakım & Onarım -->
                    <a href="{{ route('guest.requests') }}" class="block">
                        <div class="p-6 rounded-lg border border-border/60 hover:bg-accent/50 transition-colors cursor-pointer">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="wrench" class="w-7 h-7 text-primary"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg mb-2">Bakım & Onarım</h3>
                                    <p class="text-sm text-muted-foreground mb-3">
                                        Odanızdaki teknik sorunlar, arızalar ve bakım talepleri için hızlı çözüm.
                                    </p>
                                    <x-ui.button variant="outline" size="sm" class="w-full">
                                        Talep Oluştur
                                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                                    </x-ui.button>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Concierge Hizmetleri -->
                    <a href="{{ route('guest.requests') }}" class="block">
                        <div class="p-6 rounded-lg border border-border/60 hover:bg-accent/50 transition-colors cursor-pointer">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="concierge-bell" class="w-7 h-7 text-primary"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg mb-2">Concierge Hizmetleri</h3>
                                    <p class="text-sm text-muted-foreground mb-3">
                                        Restoran rezervasyonu, ulaşım, turistik bilgiler ve özel talepleriniz için.
                                    </p>
                                    <x-ui.button variant="outline" size="sm" class="w-full">
                                        Talep Oluştur
                                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                                    </x-ui.button>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Hızlı Erişim -->
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-4">
                <x-ui.card-title class="flex items-center gap-2">
                    <i data-lucide="zap" class="w-5 h-5"></i>
                    Hızlı Erişim
                </x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('guest.chat') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-4 p-6 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[140px]">
                            <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="message-square" class="w-7 h-7 text-primary"></i>
                            </div>
                            <div class="text-center">
                                <div class="text-base font-semibold">Canlı Sohbet</div>
                                <div class="text-sm text-muted-foreground mt-1">Sorularınız için</div>
                            </div>
                        </button>
                    </a>
                    <a href="{{ route('guest.requests') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-4 p-6 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[140px]">
                            <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="concierge-bell" class="w-7 h-7 text-primary"></i>
                            </div>
                            <div class="text-center">
                                <div class="text-base font-semibold">Taleplerim</div>
                                <div class="text-sm text-muted-foreground mt-1">Hizmet talepleri</div>
                            </div>
                        </button>
                    </a>
                    <a href="{{ route('guest.services') }}" class="block">
                        <button type="button" class="w-full flex flex-col items-center justify-center gap-4 p-6 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[140px]">
                            <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="sparkles" class="w-7 h-7 text-primary"></i>
                            </div>
                            <div class="text-center">
                                <div class="text-base font-semibold">Hizmetler</div>
                                <div class="text-sm text-muted-foreground mt-1">Otel hizmetleri</div>
                            </div>
                        </button>
                    </a>
                </div>
        </x-ui.card-content>
    </x-ui.card>
</div>
@endsection
