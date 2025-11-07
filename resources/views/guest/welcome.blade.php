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
@php
    $activeReservation = $reservations->where('status', 'confirmed')
        ->where('check_out_date', '>=', today())
        ->first();
@endphp

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Hoş Geldiniz 🌊</x-ui.card-title>
        @if($activeReservation)
            <x-ui.badge variant="secondary">Oda {{ $activeReservation->room->room_number ?? 'N/A' }}</x-ui.badge>
        @endif
    </x-ui.card-header>
    <x-ui.card-content class="space-y-4 text-sm">
        <div class="p-3 rounded-xl border">
            Sevgili misafirimiz, hoş geldiniz. Wi‑Fi bilgileri ve diğer hizmetler için lütfen iletişime geçin.
        </div>
        
        @if($activeReservation)
        <div class="p-3 rounded-xl border bg-accent/50">
            <div class="font-medium mb-2">Aktif Rezervasyonunuz</div>
            <div class="text-xs text-muted-foreground space-y-1">
                <div>Oda: {{ $activeReservation->room->room_number ?? 'N/A' }}</div>
                <div>Giriş: {{ \Carbon\Carbon::parse($activeReservation->check_in_date)->format('d.m.Y') }}</div>
                <div>Çıkış: {{ \Carbon\Carbon::parse($activeReservation->check_out_date)->format('d.m.Y') }}</div>
            </div>
        </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <x-ui.card class="border border-border/60">
                <x-ui.card-content class="p-4 flex items-center gap-3">
                    <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
                    Oda Servisi
                </x-ui.card-content>
            </x-ui.card>
            <x-ui.card class="border border-border/60">
                <x-ui.card-content class="p-4 flex items-center gap-3">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    Spa Randevu
                </x-ui.card-content>
            </x-ui.card>
            <x-ui.card class="border border-border/60">
                <x-ui.card-content class="p-4 flex items-center gap-3">
                    <i data-lucide="key-square" class="w-5 h-5"></i>
                    Geç Çıkış
                </x-ui.card-content>
            </x-ui.card>
        </div>
        
        <div class="p-3 rounded-xl border flex items-center gap-3">
            <i data-lucide="message-square" class="w-5 h-5"></i>
            <div>
                Sorunuz mu var? <a href="{{ route('guest.chat') }}" class="font-medium text-primary">Canlı Sohbet</a> ile anında yazabilirsiniz.
            </div>
        </div>
        
        @if($rooms->count() > 0)
        <div>
            <div class="font-medium mb-3">Müsait Odalar</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($rooms->take(4) as $room)
                <x-ui.card class="border border-border/60">
                    <x-ui.card-content class="p-4">
                        <div class="font-medium">Oda {{ $room->room_number }}</div>
                        <div class="text-xs text-muted-foreground mt-1">
                            {{ $room->roomType->name ?? 'N/A' }} - 
                            ₺{{ number_format($room->roomType->price_per_night ?? 0, 2) }}/gece
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
                @endforeach
            </div>
        </div>
        @endif
    </x-ui.card-content>
</x-ui.card>
@endsection
