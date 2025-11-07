@extends('layouts.portal')

@section('title', 'Ayarlar - Yönetim Paneli')

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
    @include('admin.partials.sidebar', ['active' => 'settings'])
@endsection

@section('content')
<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2">
        <x-ui.card-title>Otel Ayarları</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content class="space-y-6">
        @if(session('success'))
            <div class="p-3 rounded-md bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-3 rounded-md bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-sm mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="p-3 rounded-md bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-sm mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Otel Adı</label>
                    <x-ui.input name="name" value="{{ $hotel->name ?? '' }}" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">E-posta</label>
                    <x-ui.input name="email" type="email" value="{{ $hotel->email ?? '' }}" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Telefon</label>
                    <x-ui.input name="phone" value="{{ $hotel->phone ?? '' }}" />
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-medium">Adres</label>
                    <x-ui.textarea name="address" rows="3">{{ $hotel->address ?? '' }}</x-ui.textarea>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <x-ui.button type="button" variant="outline">İptal</x-ui.button>
                <x-ui.button type="submit">Kaydet</x-ui.button>
            </div>
        </form>
        <hr class="border-border">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 border rounded-xl">
                <div class="font-medium mb-2">RBAC (Yetkilendirme)</div>
                <div class="text-sm text-muted-foreground mb-3">Rol bazlı izinler: görüntüleme, atama, raporlama.</div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm">Personel • Görev Atama</span>
                    <input type="checkbox" class="h-4 w-4 rounded border-gray-300">
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm">Misafir • Sohbet</span>
                    <input type="checkbox" checked class="h-4 w-4 rounded border-gray-300">
                </div>
            </div>
            <div class="p-4 border rounded-xl">
                <div class="font-medium mb-2">Tasarım Tokenları</div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <x-ui.badge>bg-primary</x-ui.badge>
                    <x-ui.badge variant="secondary">bg-secondary</x-ui.badge>
                    <x-ui.badge variant="outline">rounded-2xl</x-ui.badge>
                    <x-ui.badge>shadow-sm</x-ui.badge>
                </div>
            </div>
            <div class="p-4 border rounded-xl">
                <div class="font-medium mb-2">Mobil Yerleşim</div>
                <div class="text-sm text-muted-foreground">Alt navigasyon, büyük dokunma hedefleri, 1 sütun grid.</div>
            </div>
        </div>
    </x-ui.card-content>
</x-ui.card>
@endsection
