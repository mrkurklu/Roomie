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
<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2">
        <x-ui.card-title>Otel İmkânları</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div class="p-4 border rounded-xl flex items-start gap-3">
            <i data-lucide="wifi" class="w-4 h-4"></i>
            <div>
                <div class="font-medium">Ücretsiz Wi‑Fi</div>
                <div class="text-muted-foreground">Tüm alanlarda</div>
            </div>
        </div>
        <div class="p-4 border rounded-xl flex items-start gap-3">
            <i data-lucide="concierge-bell" class="w-4 h-4"></i>
            <div>
                <div class="font-medium">Concierge</div>
                <div class="text-muted-foreground">7/24 destek</div>
            </div>
        </div>
        <div class="p-4 border rounded-xl flex items-start gap-3">
            <i data-lucide="utensils-crossed" class="w-4 h-4"></i>
            <div>
                <div class="font-medium">Restoran</div>
                <div class="text-muted-foreground">Yerel & dünya mutfağı</div>
            </div>
        </div>
    </x-ui.card-content>
</x-ui.card>
@endsection

