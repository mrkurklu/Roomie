<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Otel İletişim Portalı - {{ config('app.name', 'Roomie') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <!-- Topbar -->
    <div class="sticky top-0 z-30 backdrop-blur supports-[backdrop-filter]:bg-background/70 border-b">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="concierge-bell" class="w-6 h-6"></i>
                <span class="font-semibold text-lg">Otel İletişim Portalı</span>
                <span class="hidden sm:inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-secondary text-secondary-foreground">Beta</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-sm font-medium hover:bg-accent transition-colors">
                    Giriş Yap
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 transition-colors">
                        Kayıt Ol
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary/10 via-background to-secondary/10 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-6">
                <h1 class="text-4xl md:text-6xl font-bold tracking-tight">
                    Otelimize Hoş Geldiniz
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto">
                    Konforlu konaklama deneyimi için modern iletişim portalımızı keşfedin
                </p>
                <div class="flex gap-4 justify-center pt-4">
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 transition-colors">
                        Giriş Yap
                    </a>
                    <a href="{{ route('rooms.index') }}" class="px-6 py-3 rounded-md text-sm font-medium border border-input bg-background hover:bg-accent transition-colors">
                        Odaları Görüntüle
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Rooms Section -->
    <section class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-12">
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight">Favori Odalarımız</h2>
                <p class="text-lg text-muted-foreground">En popüler odalarımızı keşfedin</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($rooms as $room)
                    <x-ui.card class="border-none shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="aspect-video bg-muted relative overflow-hidden">
                            @if($room->image_path)
                                <img src="{{ asset($room->image_path) }}" alt="{{ $room->roomType->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i data-lucide="hotel" class="w-16 h-16 text-muted-foreground"></i>
                                </div>
                            @endif
                        </div>
                        <x-ui.card-content class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold mb-1">{{ $room->roomType->name }}</h3>
                                    <p class="text-sm text-muted-foreground">Oda {{ $room->room_number }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold">₺{{ number_format($room->roomType->price_per_night, 0) }}</div>
                                    <div class="text-xs text-muted-foreground">/ Gece</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 text-sm text-muted-foreground mb-4">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="users" class="w-4 h-4"></i>
                                    <span>{{ $room->roomType->capacity }} Kişi</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="bed" class="w-4 h-4"></i>
                                    <span>{{ $room->roomType->bed_type ?? 'Standart' }}</span>
                                </div>
                            </div>

                            <a href="{{ route('rooms.show', $room->id) }}" class="w-full">
                                <x-ui.button class="w-full gap-2">
                                    Detayları Görüntüle
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </x-ui.button>
                            </a>
                        </x-ui.card-content>
                    </x-ui.card>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i data-lucide="hotel" class="w-16 h-16 text-muted-foreground mx-auto mb-4"></i>
                        <p class="text-lg text-muted-foreground">Gösterilecek oda bulunamadı.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-muted/50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-12">
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight">Otel İmkânları</h2>
                <p class="text-lg text-muted-foreground">Konforunuz için sunduğumuz hizmetler</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-ui.card class="border-none shadow-sm text-center">
                    <x-ui.card-content class="p-6">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="wifi" class="w-6 h-6 text-primary"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Ücretsiz Wi-Fi</h3>
                        <p class="text-sm text-muted-foreground">Tüm alanlarda yüksek hızlı internet erişimi</p>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="border-none shadow-sm text-center">
                    <x-ui.card-content class="p-6">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="concierge-bell" class="w-6 h-6 text-primary"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">7/24 Concierge</h3>
                        <p class="text-sm text-muted-foreground">Her zaman yanınızda olan destek ekibimiz</p>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="border-none shadow-sm text-center">
                    <x-ui.card-content class="p-6">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="utensils-crossed" class="w-6 h-6 text-primary"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Restoran & Oda Servisi</h3>
                        <p class="text-sm text-muted-foreground">Lezzetli yemekler ve oda servisi</p>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4">
                <div class="flex items-center justify-center gap-3">
                    <i data-lucide="concierge-bell" class="w-6 h-6"></i>
                    <span class="font-semibold text-lg">Otel İletişim Portalı</span>
                </div>
                <p class="text-sm text-muted-foreground">
                    © {{ date('Y') }} {{ config('app.name', 'Roomie') }}. Tüm hakları saklıdır.
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
