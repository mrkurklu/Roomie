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
<div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden bg-white dark:bg-background-dark font-display">
    <div class="layout-container flex h-full grow flex-col">
        <div class="px-4 sm:px-6 md:px-8 lg:px-12 xl:px-16 2xl:px-24 flex flex-1 justify-center py-4 sm:py-5 md:py-6">
            <div class="layout-content-container flex flex-col w-full max-w-[1200px] flex-1">
                <!-- PageHeading (Hero Section) -->
                <div class="flex flex-wrap justify-between gap-4 p-2 sm:p-4">
                    <div class="flex min-w-0 flex-1 flex-col gap-2">
                        <p class="text-text-light dark:text-text-dark text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-[-0.033em]">
                            Hoş Geldiniz, {{ auth()->user()->name }}
                        </p>
                        <p class="text-text-light/70 dark:text-text-dark/70 text-base sm:text-lg font-normal leading-normal">
                            @if($hotel && $hotel->welcome_message)
                                {{ $hotel->welcome_message }}
                            @else
                                İhtiyaç duyabileceğiniz her şey bir tık uzağınızda.
                            @endif
                        </p>
                        @if($activeStay && $activeStay->room)
                        <div class="mt-4 p-4 rounded-xl bg-primary-light/10 dark:bg-primary-dark/10 border border-primary-light/20 dark:border-primary-dark/20">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary-light dark:text-primary-dark text-2xl">hotel</span>
                                <div>
                                    <p class="text-secondary-accent-light dark:text-secondary-accent-dark font-semibold">Oda {{ $activeStay->room->room_number }}</p>
                                    <p class="text-text-light/70 dark:text-text-dark/70 text-sm">
                                        Check-in: {{ \Carbon\Carbon::parse($activeStay->check_in)->format('d.m.Y H:i') }}
                                        @if($activeStay->room->roomType)
                                            • {{ $activeStay->room->roomType->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- TextGrid (Quick Access Cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 p-2 sm:p-4">
                    <a href="{{ route('guest.chat') }}" class="flex flex-1 gap-3 sm:gap-4 rounded-xl border border-transparent hover:border-primary-light dark:hover:border-primary-dark bg-surface-light dark:bg-surface-dark p-4 sm:p-6 flex-col transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="text-primary-light dark:text-primary-dark">
                            <span class="material-symbols-outlined text-2xl sm:text-3xl">chat</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h2 class="text-secondary-accent-light dark:text-secondary-accent-dark text-base sm:text-lg font-bold leading-tight">Canlı Destek</h2>
                            <p class="text-text-light/70 dark:text-text-dark/70 text-xs sm:text-sm font-normal leading-normal">Anında yardım alın</p>
                        </div>
                    </a>

                    <a href="{{ route('guest.services') }}" class="flex flex-1 gap-3 sm:gap-4 rounded-xl border border-transparent hover:border-primary-light dark:hover:border-primary-dark bg-surface-light dark:bg-surface-dark p-4 sm:p-6 flex-col transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="text-primary-light dark:text-primary-dark">
                            <span class="material-symbols-outlined text-2xl sm:text-3xl">room_service</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h2 class="text-secondary-accent-light dark:text-secondary-accent-dark text-base sm:text-lg font-bold leading-tight">Oda Servisi & Hizmetler</h2>
                            <p class="text-text-light/70 dark:text-text-dark/70 text-xs sm:text-sm font-normal leading-normal">Tüm hizmetler parmağınızın ucunda</p>
                        </div>
                    </a>

                    <a href="{{ route('guest.requests') }}" class="flex flex-1 gap-3 sm:gap-4 rounded-xl border border-transparent hover:border-primary-light dark:hover:border-primary-dark bg-surface-light dark:bg-surface-dark p-4 sm:p-6 flex-col transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="text-primary-light dark:text-primary-dark">
                            <span class="material-symbols-outlined text-2xl sm:text-3xl">concierge</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h2 class="text-secondary-accent-light dark:text-secondary-accent-dark text-base sm:text-lg font-bold leading-tight">Taleplerim</h2>
                            <p class="text-text-light/70 dark:text-text-dark/70 text-xs sm:text-sm font-normal leading-normal">Yeni havlu, yastık ve daha fazlası</p>
                        </div>
                    </a>

                    <a href="{{ route('guest.feedback') }}" class="flex flex-1 gap-3 sm:gap-4 rounded-xl border border-transparent hover:border-primary-light dark:hover:border-primary-dark bg-surface-light dark:bg-surface-dark p-4 sm:p-6 flex-col transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="text-primary-light dark:text-primary-dark">
                            <span class="material-symbols-outlined text-2xl sm:text-3xl">reviews</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h2 class="text-secondary-accent-light dark:text-secondary-accent-dark text-base sm:text-lg font-bold leading-tight">Geri Bildirim</h2>
                            <p class="text-text-light/70 dark:text-text-dark/70 text-xs sm:text-sm font-normal leading-normal">Deneyiminizi bizimle paylaşın</p>
                        </div>
                    </a>

                    <a href="{{ route('guest.events') }}" class="flex flex-1 gap-3 sm:gap-4 rounded-xl border border-transparent hover:border-primary-light dark:hover:border-primary-dark bg-surface-light dark:bg-surface-dark p-4 sm:p-6 flex-col transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="text-primary-light dark:text-primary-dark">
                            <span class="material-symbols-outlined text-2xl sm:text-3xl">calendar_month</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h2 class="text-secondary-accent-light dark:text-secondary-accent-dark text-base sm:text-lg font-bold leading-tight">Otel Etkinlikleri</h2>
                            <p class="text-text-light/70 dark:text-text-dark/70 text-xs sm:text-sm font-normal leading-normal">Güncel etkinlik takvimini görün</p>
                        </div>
                    </a>

                    <a href="{{ route('guest.services') }}" class="flex flex-1 gap-3 sm:gap-4 rounded-xl border border-transparent hover:border-primary-light dark:hover:border-primary-dark bg-surface-light dark:bg-surface-dark p-4 sm:p-6 flex-col transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="text-primary-light dark:text-primary-dark">
                            <span class="material-symbols-outlined text-2xl sm:text-3xl">pool</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h2 class="text-secondary-accent-light dark:text-secondary-accent-dark text-base sm:text-lg font-bold leading-tight">Tesis Olanakları</h2>
                            <p class="text-text-light/70 dark:text-text-dark/70 text-xs sm:text-sm font-normal leading-normal">Havuz, spa ve spor salonu</p>
                        </div>
                    </a>
                </div>

                <!-- SectionHeader -->
                <h2 class="text-secondary-accent-light dark:text-secondary-accent-dark text-xl sm:text-2xl font-bold leading-tight tracking-[-0.015em] px-2 sm:px-4 pb-3 pt-4 sm:pt-5">Öne Çıkan Hizmetler</h2>

                <!-- ImageGrid (Service Preview Cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 p-2 sm:p-4">
                    @if($hotel && $hotel->amenities && count($hotel->amenities) > 0)
                        @php
                            $featuredServices = [
                                ['name' => 'Spa & Wellness Merkezi', 'desc' => 'Günün yorgunluğunu atın ve yenilenin.', 'icon' => 'spa', 'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=800'],
                                ['name' => 'A\'la Carte Restoran', 'desc' => 'Usta şeflerimizin hazırladığı özel lezzetler.', 'icon' => 'restaurant', 'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800'],
                                ['name' => 'Açık Havuz Keyfi', 'desc' => 'Güneşin ve serin suların tadını çıkarın.', 'icon' => 'pool', 'image' => 'https://images.unsplash.com/photo-1576610616656-d3aa24d1a658?w=800'],
                            ];
                            $amenityNames = $hotel->amenities->pluck('name')->toArray();
                            $displayedServices = [];
                            foreach($featuredServices as $service) {
                                foreach($amenityNames as $amenity) {
                                    if(stripos($amenity, $service['name']) !== false || 
                                       stripos($amenity, 'Spa') !== false && stripos($service['name'], 'Spa') !== false ||
                                       stripos($amenity, 'Restoran') !== false && stripos($service['name'], 'Restoran') !== false ||
                                       stripos($amenity, 'Havuz') !== false && stripos($service['name'], 'Havuz') !== false) {
                                        $displayedServices[] = $service;
                                        break;
                                    }
                                }
                            }
                            if(count($displayedServices) < 3) {
                                $displayedServices = array_slice($featuredServices, 0, 3);
                            }
                        @endphp
                        @foreach(array_slice($displayedServices, 0, 3) as $service)
                        <div class="flex flex-col gap-4">
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl" style="background-image: url('{{ $service['image'] }}'); background-color: #d6e4f0;"></div>
                            <div>
                                <p class="text-secondary-accent-light dark:text-secondary-accent-dark text-lg font-bold leading-normal">{{ $service['name'] }}</p>
                                <p class="text-text-light/70 dark:text-text-dark/70 text-sm font-normal leading-normal">{{ $service['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="flex flex-col gap-4">
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl" style="background-image: url('https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=800'); background-color: #d6e4f0;"></div>
                            <div>
                                <p class="text-secondary-accent-light dark:text-secondary-accent-dark text-lg font-bold leading-normal">Spa & Wellness Merkezi</p>
                                <p class="text-text-light/70 dark:text-text-dark/70 text-sm font-normal leading-normal">Günün yorgunluğunu atın ve yenilenin.</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl" style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800'); background-color: #d6e4f0;"></div>
                            <div>
                                <p class="text-secondary-accent-light dark:text-secondary-accent-dark text-lg font-bold leading-normal">A'la Carte Restoran</p>
                                <p class="text-text-light/70 dark:text-text-dark/70 text-sm font-normal leading-normal">Usta şeflerimizin hazırladığı özel lezzetler.</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl" style="background-image: url('https://images.unsplash.com/photo-1576610616656-d3aa24d1a658?w=800'); background-color: #d6e4f0;"></div>
                            <div>
                                <p class="text-secondary-accent-light dark:text-secondary-accent-dark text-lg font-bold leading-normal">Açık Havuz Keyfi</p>
                                <p class="text-text-light/70 dark:text-text-dark/70 text-sm font-normal leading-normal">Güneşin ve serin suların tadını çıkarın.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Etkinlikler ve Duyurular -->
                @if($events && $events->count() > 0)
                <div id="events" class="px-2 sm:px-4 pt-6 sm:pt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-secondary-accent-light dark:text-secondary-accent-dark text-xl sm:text-2xl font-bold leading-tight tracking-[-0.015em]">Etkinlikler & Duyurular</h2>
                        <a href="{{ route('guest.events') }}" class="text-sm font-medium text-primary-light dark:text-primary-dark hover:underline flex items-center gap-1">
                            Tümünü Gör
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                    <div class="space-y-4 mt-4">
                        @foreach($events->take(5) as $event)
                        <a href="{{ route('guest.events.show', $event->id) }}" class="block p-3 sm:p-4 rounded-xl border border-transparent hover:border-primary-light dark:hover:border-primary-dark bg-surface-light dark:bg-surface-dark transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                            <div class="flex items-start gap-3 sm:gap-4">
                                @if($event->image_path)
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="{{ asset($event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                </div>
                                @else
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-2xl sm:text-3xl text-primary-light dark:text-primary-dark">calendar_month</span>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                                        <h3 class="font-semibold text-base sm:text-lg text-secondary-accent-light dark:text-secondary-accent-dark">{{ $event->title }}</h3>
                                        @if($event->start_date)
                                        <div class="flex items-center gap-1 text-xs text-text-light/70 dark:text-text-dark/70 flex-shrink-0">
                                            <span class="material-symbols-outlined text-sm">schedule</span>
                                            <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d.m.Y H:i') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @if($event->description)
                                    <p class="text-xs sm:text-sm text-text-light/80 dark:text-text-dark/80 mb-2 line-clamp-2">
                                        {{ $event->description }}
                                    </p>
                                    @endif
                                    @if($event->location)
                                    <div class="flex items-center gap-1 text-xs text-text-light/70 dark:text-text-dark/70">
                                        <span class="material-symbols-outlined text-sm">location_on</span>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Information Panel -->
                @if($hotel)
                <div class="p-2 sm:p-4 mt-6 sm:mt-8">
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-4 sm:p-6 flex flex-col sm:flex-row flex-wrap justify-around items-center gap-4 sm:gap-6">
                        @if($hotel->address)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary-light dark:text-primary-dark">location_on</span>
                            <span class="text-text-light dark:text-text-dark text-sm">{{ $hotel->address }}</span>
                        </div>
                        @endif
                        @if($hotel->phone)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary-light dark:text-primary-dark">call</span>
                            <span class="text-text-light dark:text-text-dark text-sm">{{ $hotel->phone }}</span>
                        </div>
                        @endif
                        @if($hotel->email)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary-light dark:text-primary-dark">email</span>
                            <span class="text-text-light dark:text-text-dark text-sm">{{ $hotel->email }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Footer -->
                <footer class="mt-8 sm:mt-12 md:mt-16 py-4 sm:py-6 px-2 sm:px-4 border-t border-surface-light dark:border-surface-dark">
                    <div class="flex flex-col sm:flex-row justify-between items-center text-center gap-3 sm:gap-4">
                        <p class="text-xs sm:text-sm text-text-light/60 dark:text-text-dark/60 break-words">{{ $hotel->name ?? 'Roomie' }} Otel Yönetim Sistemi. Tüm hakları saklıdır.</p>
                        <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
                            <a class="text-xs sm:text-sm text-text-light/60 dark:text-text-dark/60 hover:text-primary-light dark:hover:text-primary-dark transition-colors" href="#">Gizlilik Politikası</a>
                            <a class="text-xs sm:text-sm text-text-light/60 dark:text-text-dark/60 hover:text-primary-light dark:hover:text-primary-dark transition-colors" href="#">Kullanım Şartları</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>
@endsection
