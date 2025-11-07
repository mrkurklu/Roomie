@extends('layouts.portal')

@section('title', 'Kaynaklar - Personel Paneli')

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
    @include('staff.partials.sidebar', ['active' => 'resources'])
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Toplam Kaynak</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-green-600">{{ $stats['available'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Müsait</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-yellow-600">{{ $stats['low_stock'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Düşük Stok</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-red-600">{{ $stats['out_of_stock'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Stokta Yok</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2">
        <x-ui.card-title>Kaynaklar</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>İsim</x-ui.table-head>
                    <x-ui.table-head>Kategori</x-ui.table-head>
                    <x-ui.table-head>Miktar</x-ui.table-head>
                    <x-ui.table-head>Müsait</x-ui.table-head>
                    <x-ui.table-head>Durum</x-ui.table-head>
                    <x-ui.table-head>Birim Fiyat</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($resources ?? [] as $resource)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $resource->name }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="outline">{{ ucfirst($resource->category) }}</x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>{{ $resource->quantity }} {{ $resource->unit ?? 'adet' }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $resource->available_quantity }} {{ $resource->unit ?? 'adet' }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="{{ $resource->status === 'available' ? 'default' : ($resource->status === 'low_stock' ? 'secondary' : 'destructive') }}">
                            {{ ucfirst(str_replace('_', ' ', $resource->status)) }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($resource->cost_per_unit)
                            ₺{{ number_format($resource->cost_per_unit, 2) }}
                        @else
                            <span class="text-muted-foreground">-</span>
                        @endif
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="6" class="text-center py-8 text-muted-foreground">
                        Henüz kaynak yok
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card-content>
</x-ui.card>

@if(isset($resources) && $resources->count() > 15)
<div class="mt-6">
    {{ $resources->links() }}
</div>
@endif
@endsection
