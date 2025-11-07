@extends('layouts.portal')

@section('title', 'Taleplerim - Misafir Paneli')

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
    @include('guest.partials.sidebar', ['active' => 'requests'])
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Toplam Talep</div>
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
            <div class="text-2xl font-semibold text-blue-600">{{ $stats['in_progress'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">İşlemde</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-green-600">{{ $stats['completed'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Tamamlanan</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Taleplerim</x-ui.card-title>
        <x-ui.button 
            x-data="{ open: false }"
            @click="open = !open"
            class="gap-2"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            Yeni Talep
        </x-ui.button>
    </x-ui.card-header>
    <x-ui.card-content>
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
        <div 
            x-data="{ open: false }"
            x-show="open"
            x-cloak
            x-transition
            class="mb-6 p-4 border rounded-lg"
        >
            <form method="POST" action="{{ route('guest.requests.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Talep Türü</label>
                        <select name="type" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required>
                            <option value="room_service">Oda Servisi</option>
                            <option value="housekeeping">Temizlik</option>
                            <option value="maintenance">Bakım</option>
                            <option value="concierge">Konsiyerj</option>
                            <option value="other">Diğer</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Öncelik</label>
                        <select name="priority" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <option value="low">Düşük</option>
                            <option value="medium" selected>Orta</option>
                            <option value="high">Yüksek</option>
                            <option value="urgent">Acil</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Başlık</label>
                    <x-ui.input name="title" placeholder="Talep başlığı" required />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Açıklama</label>
                    <textarea name="description" rows="4" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Talep açıklaması" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <x-ui.button type="button" variant="outline" @click="open = false">İptal</x-ui.button>
                    <x-ui.button type="submit">Gönder</x-ui.button>
                </div>
            </form>
        </div>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Oda</x-ui.table-head>
                    <x-ui.table-head>Tür</x-ui.table-head>
                    <x-ui.table-head>Başlık</x-ui.table-head>
                    <x-ui.table-head>Öncelik</x-ui.table-head>
                    <x-ui.table-head>Durum</x-ui.table-head>
                    <x-ui.table-head>Tarih</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($requests ?? [] as $request)
                <x-ui.table-row>
                    <x-ui.table-cell>{{ $request->guest_room ?? 'N/A' }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="outline">{{ ucfirst(str_replace('_', ' ', $request->category)) }}</x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell class="font-medium">{{ Str::limit($request->title, 40) }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="{{ $request->priority === 'urgent' ? 'destructive' : ($request->priority === 'high' ? 'default' : 'secondary') }}">
                            {{ ucfirst($request->priority) }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="{{ $request->status === 'completed' ? 'default' : ($request->status === 'in_progress' ? 'secondary' : 'outline') }}">
                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell class="text-sm text-muted-foreground">
                        {{ $request->created_at->format('d.m.Y H:i') }}
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="6" class="text-center py-8 text-muted-foreground">
                        Henüz talep yok
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card-content>
</x-ui.card>

@if(isset($requests) && $requests->count() > 15)
<div class="mt-6">
    {{ $requests->links() }}
</div>
@endif
@endsection
