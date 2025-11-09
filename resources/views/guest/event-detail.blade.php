@extends('layouts.portal')

@section('title', $event->title . ' - Etkinlik Detayı')

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
<div class="w-full space-y-6">
    <!-- Geri Dön Butonu -->
    <div>
        <a href="{{ route('guest.welcome') }}" class="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Geri Dön
        </a>
    </div>

    <!-- Etkinlik Detay Kartı -->
    <x-ui.card class="border-none shadow-sm">
        @if($event->image_path)
        <div class="w-full h-64 md:h-80 rounded-t-lg overflow-hidden">
            <img src="{{ asset($event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
        </div>
        @endif
        <x-ui.card-header class="pb-4">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <x-ui.card-title class="text-2xl md:text-3xl mb-2">{{ $event->title }}</x-ui.card-title>
                    @if($event->start_date)
                    <div class="flex flex-wrap items-center gap-4 text-sm text-muted-foreground">
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d.m.Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            <span>{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</span>
                        </div>
                        @if($event->end_date)
                        <div class="flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            <span>{{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}</span>
                        </div>
                        @endif
                        @if($event->location)
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-4">
            @if($event->description)
            <div class="prose prose-sm max-w-none">
                <p class="text-muted-foreground leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
            </div>
            @endif

            @if($event->location)
            <div class="pt-4 border-t">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <div class="font-medium mb-1">Konum</div>
                        <div class="text-sm text-muted-foreground">{{ $event->location }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="pt-4 border-t flex items-center justify-between">
                <a href="{{ route('guest.welcome') }}" class="inline-flex items-center gap-2">
                    <x-ui.button variant="outline">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Geri Dön
                    </x-ui.button>
                </a>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>
@endsection

