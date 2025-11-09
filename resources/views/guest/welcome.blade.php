@extends('layouts.portal')

@section('title', 'Hoş Geldiniz - Misafir Paneli')

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
    @include('guest.partials.sidebar', ['active' => 'welcome'])
@endsection

@section('content')
<!-- Hoş Geldiniz Bölümü -->
<div class="w-full space-y-6">
    <!-- Hoş Geldiniz Kartı -->
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
                        Hoş Geldiniz, {{ auth()->user()->name }}! 👋
                    </h1>
                    @if($hotel && $hotel->welcome_message)
                        <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                            {{ $hotel->welcome_message }}
                        </p>
                    @else
                        <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                            {{ $hotel->name ?? 'Otelimize' }} hoş geldiniz! Konforlu bir konaklama deneyimi için buradayız.
                        </p>
                    @endif
                </div>
            </div>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Otel Bilgileri -->
    @if($hotel)
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-4">
            <x-ui.card-title class="flex items-center gap-2">
                <i data-lucide="building-2" class="w-5 h-5"></i>
                Otel Hakkında
            </x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>Adres</span>
                    </div>
                    <p class="font-medium">{{ $hotel->address }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        <span>Telefon</span>
                    </div>
                    <p class="font-medium">{{ $hotel->phone }}</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        <span>E-posta</span>
                    </div>
                    <p class="font-medium">{{ $hotel->email }}</p>
                </div>
            </div>
            @if($hotel->description)
            <div class="pt-4 border-t">
                <p class="text-sm text-muted-foreground leading-relaxed">
                    {{ $hotel->description }}
                </p>
            </div>
            @endif
        </x-ui.card-content>
    </x-ui.card>
    @endif

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
                <a href="{{ route('guest.services') }}" class="block">
                    <button type="button" class="w-full flex flex-col items-center justify-center gap-3 p-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[100px]">
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
                </a>
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
                <a href="{{ route('guest.services') }}" class="block">
                    <button type="button" class="w-full flex flex-col items-center justify-center gap-3 p-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[100px]">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="wifi" class="w-6 h-6 text-primary"></i>
                        </div>
                        <span class="text-sm font-medium text-center leading-tight">Ücretsiz Wi-Fi</span>
                    </button>
                </a>
                <a href="{{ route('guest.services') }}" class="block">
                    <button type="button" class="w-full flex flex-col items-center justify-center gap-3 p-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[100px]">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="concierge-bell" class="w-6 h-6 text-primary"></i>
                        </div>
                        <span class="text-sm font-medium text-center leading-tight">7/24 Concierge</span>
                    </button>
                </a>
                <a href="{{ route('guest.services') }}" class="block">
                    <button type="button" class="w-full flex flex-col items-center justify-center gap-3 p-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[100px]">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="utensils-crossed" class="w-6 h-6 text-primary"></i>
                        </div>
                        <span class="text-sm font-medium text-center leading-tight">Restoran & Oda Servisi</span>
                    </button>
                </a>
                <a href="{{ route('guest.services') }}" class="block">
                    <button type="button" class="w-full flex flex-col items-center justify-center gap-3 p-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors min-h-[100px]">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="sparkles" class="w-6 h-6 text-primary"></i>
                        </div>
                        <span class="text-sm font-medium text-center leading-tight">Spa & Wellness</span>
                    </button>
                </a>
            </div>
        </x-ui.card-content>
    </x-ui.card>
    @endif

    <!-- Etkinlikler ve Duyurular -->
    @if($events && $events->count() > 0)
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-4 flex items-center justify-between">
            <x-ui.card-title class="flex items-center gap-2">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                Etkinlikler & Duyurular
            </x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="space-y-4">
                @foreach($events as $event)
                <a href="{{ route('guest.events.show', $event->id) }}" class="block p-4 rounded-lg border border-border/60 hover:bg-accent/50 transition-colors cursor-pointer">
                    <div class="flex items-start gap-4">
                        @if($event->image_path)
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ asset($event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="w-20 h-20 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="calendar" class="w-8 h-8 text-primary"></i>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="font-semibold text-lg">{{ $event->title }}</h3>
                                @if($event->start_date)
                                <div class="flex items-center gap-1 text-xs text-muted-foreground flex-shrink-0">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d.m.Y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                            @if($event->description)
                            <p class="text-sm text-muted-foreground mb-2 line-clamp-2">
                                {{ $event->description }}
                            </p>
                            @endif
                            @if($event->location)
                            <div class="flex items-center gap-1 text-xs text-muted-foreground">
                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </x-ui.card-content>
    </x-ui.card>
    @else
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-4">
            <x-ui.card-title class="flex items-center gap-2">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                Etkinlikler & Duyurular
            </x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="text-center py-8 text-muted-foreground">
                <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                <p>Şu anda planlanmış etkinlik bulunmamaktadır.</p>
            </div>
        </x-ui.card-content>
    </x-ui.card>
    @endif

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
