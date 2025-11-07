@extends('layouts.portal')

@section('title', 'Rezervasyonlar - Yönetim Paneli')

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
    @include('admin.partials.sidebar', ['active' => 'reservations'])
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Toplam Rezervasyon</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-yellow-600">{{ $stats['pending'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Bekleyen</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-green-600">{{ $stats['confirmed'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Onaylı</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-red-600">{{ $stats['cancelled'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">İptal</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>{{ __('reservations') }}</x-ui.card-title>
        <div class="flex gap-2">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"></i>
                <x-ui.input placeholder="İsim / kod ara" class="pl-9 w-64" />
            </div>
            <x-ui.button class="gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Yeni
            </x-ui.button>
        </div>
    </x-ui.card-header>
    <x-ui.card-content>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Kod</x-ui.table-head>
                    <x-ui.table-head>Misafir</x-ui.table-head>
                    <x-ui.table-head>Oda</x-ui.table-head>
                    <x-ui.table-head>Tarihler</x-ui.table-head>
                    <x-ui.table-head>Tutar</x-ui.table-head>
                    <x-ui.table-head>Durum</x-ui.table-head>
                    <x-ui.table-head></x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($reservations ?? [] as $reservation)
                <x-ui.table-row>
                    <x-ui.table-cell>#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $reservation->user->name ?? 'Bilinmeyen' }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $reservation->room->room_number ?? 'N/A' }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d.m.Y') }} - 
                        {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d.m.Y') }}
                    </x-ui.table-cell>
                    <x-ui.table-cell>₺{{ number_format($reservation->total_price, 2) }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="{{ $reservation->status === 'confirmed' ? 'default' : ($reservation->status === 'pending' ? 'outline' : 'destructive') }}">
                            {{ ucfirst($reservation->status) }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.button size="sm" variant="outline">Detay</x-ui.button>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="7" class="text-center py-8 text-muted-foreground">
                        Henüz rezervasyon yok
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card-content>
</x-ui.card>

@if(isset($reservations) && $reservations->count() > 15)
<div class="mt-6">
    {{ $reservations->links() }}
</div>
@endif
@endsection
