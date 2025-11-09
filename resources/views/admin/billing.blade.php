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
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-center py-8 text-muted-foreground">
                    Faturalandırma özelliği yakında eklenecektir.
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>

@endsection
