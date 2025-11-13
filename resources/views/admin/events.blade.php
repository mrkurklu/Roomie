@extends('layouts.portal')

@section('title', 'Etkinlikler - Yönetim Paneli')

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
    @include('admin.partials.sidebar', ['active' => 'events'])
@endsection

@section('content')
@if(session('success'))
    <div class="p-3 rounded-md bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm mb-4">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="p-3 rounded-md bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-sm mb-4">
        {{ session('error') }}
    </div>
@endif

<x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark mb-6">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title class="text-text-light dark:text-text-dark">Yeni Etkinlik Ekle</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content>
        <form method="POST" action="{{ route('admin.events.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-text-light dark:text-text-dark">Başlık *</label>
                    <x-ui.input name="title" placeholder="Etkinlik başlığı" required />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-text-light dark:text-text-dark">Öncelik</label>
                    <x-ui.input name="priority" type="number" min="0" max="100" value="0" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-text-light dark:text-text-dark">Başlangıç Tarihi *</label>
                    <x-ui.input name="start_date" type="datetime-local" required />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-text-light dark:text-text-dark">Bitiş Tarihi</label>
                    <x-ui.input name="end_date" type="datetime-local" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-text-light dark:text-text-dark">Konum</label>
                    <x-ui.input name="location" placeholder="Etkinlik konumu" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-text-light dark:text-text-dark">Görsel Yolu</label>
                    <x-ui.input name="image_path" placeholder="storage/events/image.jpg" />
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium text-text-light dark:text-text-dark">Açıklama</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border border-primary-light/20 dark:border-primary-dark/20 bg-white dark:bg-background-dark px-3 py-2 text-sm text-text-light dark:text-text-dark placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark resize-none" placeholder="Etkinlik açıklaması"></textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 rounded border-primary-light/20 dark:border-primary-dark/20">
                <label for="is_active" class="text-sm font-medium text-text-light dark:text-text-dark">Aktif</label>
            </div>
            <div class="flex justify-end">
                <x-ui.button type="submit">Etkinlik Ekle</x-ui.button>
            </div>
        </form>
    </x-ui.card-content>
</x-ui.card>

<x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title class="text-text-light dark:text-text-dark">Etkinlikler</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Başlık</x-ui.table-head>
                    <x-ui.table-head>Tarih</x-ui.table-head>
                    <x-ui.table-head>Konum</x-ui.table-head>
                    <x-ui.table-head>Durum</x-ui.table-head>
                    <x-ui.table-head>Öncelik</x-ui.table-head>
                    <x-ui.table-head></x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($events ?? [] as $event)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $event->title }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        {{ \Carbon\Carbon::parse($event->start_date)->format('d.m.Y H:i') }}
                        @if($event->end_date)
                            <br><span class="text-xs text-text-light/60 dark:text-text-dark/60">- {{ \Carbon\Carbon::parse($event->end_date)->format('d.m.Y H:i') }}</span>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>{{ $event->location ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="{{ $event->is_active ? 'default' : 'outline' }}">
                            {{ $event->is_active ? 'Aktif' : 'Pasif' }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>{{ $event->priority }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.events.update', $event) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_active" value="{{ $event->is_active ? 0 : 1 }}">
                                <x-ui.button type="submit" size="sm" variant="outline">
                                    {{ $event->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                                </x-ui.button>
                            </form>
                            <form method="POST" action="{{ route('admin.events.delete', $event) }}" class="inline" onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" size="sm" variant="destructive">Sil</x-ui.button>
                            </form>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="6" class="text-center py-8 text-text-light/70 dark:text-text-dark/70">
                        Henüz etkinlik yok
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card-content>
</x-ui.card>

@if(isset($events) && $events->count() > 15)
<div class="mt-6">
    {{ $events->links() }}
</div>
@endif
@endsection

