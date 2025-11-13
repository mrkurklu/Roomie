@extends('layouts.portal')

@section('title', 'Otel İmkânları - Misafir Paneli')

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
    @include('guest.partials.sidebar', ['active' => 'amenities'])
@endsection

@section('content')
@if($hotel)
    @if($hotel->description)
    <x-ui.card class="border-none shadow-sm mb-6">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title>Otel Hakkında</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <p class="text-sm text-muted-foreground leading-relaxed">{{ $hotel->description }}</p>
        </x-ui.card-content>
    </x-ui.card>
    @endif

    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title>Otel İmkânları</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            @if($hotel->amenities && is_array($hotel->amenities) && count($hotel->amenities) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    @foreach($hotel->amenities as $amenity)
                        @php
                            $amenityName = is_array($amenity) ? ($amenity['name'] ?? '') : $amenity;
                            $amenityDesc = is_array($amenity) ? ($amenity['description'] ?? '') : '';
                            $amenityIcon = is_array($amenity) ? ($amenity['icon'] ?? 'star') : 'star';
                        @endphp
                        <div class="p-4 border rounded-xl flex items-start gap-3 hover:bg-primary-light/5 dark:hover:bg-primary-dark/5 transition-colors">
                            <i data-lucide="{{ $amenityIcon }}" class="w-5 h-5 text-primary-light dark:text-primary-dark flex-shrink-0 mt-0.5"></i>
                            <div>
                                <div class="font-medium text-secondary-accent-light dark:text-secondary-accent-dark">{{ $amenityName }}</div>
                                @if($amenityDesc)
                                <div class="text-muted-foreground text-xs mt-1">{{ $amenityDesc }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <div class="p-4 border rounded-xl flex items-start gap-3">
                        <i data-lucide="wifi" class="w-5 h-5 text-primary-light dark:text-primary-dark flex-shrink-0 mt-0.5"></i>
                        <div>
                            <div class="font-medium">Ücretsiz Wi‑Fi</div>
                            <div class="text-muted-foreground text-xs mt-1">Tüm alanlarda</div>
                        </div>
                    </div>
                    <div class="p-4 border rounded-xl flex items-start gap-3">
                        <i data-lucide="concierge-bell" class="w-5 h-5 text-primary-light dark:text-primary-dark flex-shrink-0 mt-0.5"></i>
                        <div>
                            <div class="font-medium">Concierge</div>
                            <div class="text-muted-foreground text-xs mt-1">7/24 destek</div>
                        </div>
                    </div>
                    <div class="p-4 border rounded-xl flex items-start gap-3">
                        <i data-lucide="utensils-crossed" class="w-5 h-5 text-primary-light dark:text-primary-dark flex-shrink-0 mt-0.5"></i>
                        <div>
                            <div class="font-medium">Restoran</div>
                            <div class="text-muted-foreground text-xs mt-1">Yerel & dünya mutfağı</div>
                        </div>
                    </div>
                </div>
            @endif
        </x-ui.card-content>
    </x-ui.card>
@else
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content>
            <p class="text-muted-foreground text-center py-8">Otel bilgileri bulunamadı.</p>
        </x-ui.card-content>
    </x-ui.card>
@endif
@endsection

