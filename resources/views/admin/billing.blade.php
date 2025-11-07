@extends('layouts.portal')

@section('title', 'Faturalandırma - Yönetim Paneli')

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
    @include('admin.partials.sidebar', ['active' => 'billing'])
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-4 space-y-4">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Özet</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-2 text-sm">
                <div class="flex items-center justify-between">
                    <span>Bugünkü Gelir</span>
                    <b>₺{{ number_format($stats['today_revenue'] ?? 0, 2) }}</b>
                </div>
                <div class="flex items-center justify-between">
                    <span>Aylık Gelir</span>
                    <b>₺{{ number_format($stats['month_revenue'] ?? 0, 2) }}</b>
                </div>
                <div class="flex items-center justify-between">
                    <span>Yıllık Gelir</span>
                    <b>₺{{ number_format($stats['year_revenue'] ?? 0, 2) }}</b>
                </div>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Ödeme Yöntemleri</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="text-sm space-y-2">
                <div>• Kredi Kartı • Nakit • Oda Hesabı • Kurumsal</div>
            </x-ui.card-content>
        </x-ui.card>
    </div>
    <div class="col-span-12 lg:col-span-8">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2 flex items-center justify-between">
                <x-ui.card-title>Faturalar</x-ui.card-title>
                <x-ui.button class="gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Yeni Fatura
                </x-ui.button>
            </x-ui.card-header>
            <x-ui.card-content>
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.table-head>Rezervasyon</x-ui.table-head>
                            <x-ui.table-head>Misafir</x-ui.table-head>
                            <x-ui.table-head>Oda</x-ui.table-head>
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
                            <x-ui.table-cell>₺{{ number_format($reservation->total_price, 2) }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $reservation->status === 'confirmed' ? 'default' : 'outline' }}">
                                    {{ ucfirst($reservation->status) }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.button size="sm" variant="outline">Detay</x-ui.button>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="6" class="text-center py-8 text-muted-foreground">
                                Henüz fatura yok
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @endforelse
                    </x-ui.table-body>
                </x-ui.table>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>

@if(isset($reservations) && $reservations->count() > 15)
<div class="mt-6">
    {{ $reservations->links() }}
</div>
@endif
@endsection
