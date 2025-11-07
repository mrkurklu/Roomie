@extends('layouts.portal')

@section('title', 'Misafirler - Yönetim Paneli')

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
    @include('admin.partials.sidebar', ['active' => 'guests'])
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Toplam Misafir</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-green-600">{{ $stats['active'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Aktif Misafir</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Misafir Listesi</x-ui.card-title>
        <div class="flex gap-2">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"></i>
                <x-ui.input placeholder="İsim veya oda ara" class="pl-9 w-64" />
            </div>
            <x-ui.button variant="outline" class="gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filtre
            </x-ui.button>
            <x-ui.button class="gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Yeni Misafir
            </x-ui.button>
        </div>
    </x-ui.card-header>
    <x-ui.card-content>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>İsim</x-ui.table-head>
                    <x-ui.table-head>E-posta</x-ui.table-head>
                    <x-ui.table-head>Rezervasyon</x-ui.table-head>
                    <x-ui.table-head>Durum</x-ui.table-head>
                    <x-ui.table-head>Aksiyon</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($guests ?? [] as $guest)
                @php
                    $activeReservation = $guest->reservations->where('status', 'confirmed')
                        ->where('check_out_date', '>=', today())
                        ->first();
                @endphp
                <x-ui.table-row>
                    <x-ui.table-cell>{{ $guest->name }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $guest->email }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($activeReservation)
                            Oda {{ $activeReservation->room->room_number ?? 'N/A' }}
                        @else
                            <span class="text-muted-foreground">Yok</span>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($activeReservation)
                            <x-ui.badge>Konaklıyor</x-ui.badge>
                        @else
                            <x-ui.badge variant="outline">Pasif</x-ui.badge>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <div class="flex gap-2">
                            <x-ui.button size="sm" variant="secondary">Detay</x-ui.button>
                            <x-ui.button size="sm" variant="outline">Mesaj</x-ui.button>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="5" class="text-center py-8 text-muted-foreground">
                        Henüz misafir yok
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card-content>
</x-ui.card>

@if(isset($guests) && $guests->count() > 15)
<div class="mt-6">
    {{ $guests->links() }}
</div>
@endif
@endsection
