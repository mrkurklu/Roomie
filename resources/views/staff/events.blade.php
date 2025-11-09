@extends('layouts.portal')

@section('title', 'Etkinlikler - Personel Paneli')

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
    @include('staff.partials.sidebar', ['active' => 'events'])
@endsection

@section('content')
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

<x-ui.card class="border-none shadow-sm mb-6">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Yeni Etkinlik Ekle</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content>
        <form method="POST" action="{{ route('staff.events.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Başlık *</label>
                    <x-ui.input name="title" placeholder="Etkinlik başlığı" required />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Öncelik</label>
                    <x-ui.input name="priority" type="number" min="0" max="100" value="0" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Başlangıç Tarihi *</label>
                    <x-ui.input name="start_date" type="datetime-local" required />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Bitiş Tarihi</label>
                    <x-ui.input name="end_date" type="datetime-local" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Konum</label>
                    <x-ui.input name="location" placeholder="Etkinlik konumu" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Görsel Yolu</label>
                    <x-ui.input name="image_path" placeholder="storage/events/image.jpg" />
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium">Açıklama</label>
                <textarea name="description" rows="4" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Etkinlik açıklaması"></textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 rounded border-gray-300">
                <label for="is_active" class="text-sm font-medium">Aktif</label>
            </div>
            <div class="flex justify-end">
                <x-ui.button type="submit">Etkinlik Ekle</x-ui.button>
            </div>
        </form>
    </x-ui.card-content>
</x-ui.card>

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Etkinlikler</x-ui.card-title>
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
                            <br><span class="text-xs text-muted-foreground">- {{ \Carbon\Carbon::parse($event->end_date)->format('d.m.Y H:i') }}</span>
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
                            <form method="POST" action="{{ route('staff.events.update', $event) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_active" value="{{ $event->is_active ? 0 : 1 }}">
                                <x-ui.button type="submit" size="sm" variant="outline">
                                    {{ $event->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                                </x-ui.button>
                            </form>
                            <form method="POST" action="{{ route('staff.events.delete', $event) }}" class="inline" onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" size="sm" variant="destructive">Sil</x-ui.button>
                            </form>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="6" class="text-center py-8 text-muted-foreground">
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

