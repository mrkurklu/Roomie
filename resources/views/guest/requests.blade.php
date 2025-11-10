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
    <x-ui.card class="border-2 border-white/20 shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-white">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-white/70 mt-1">Toplam Talep</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-2 border-white/20 shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-third-color">{{ $stats['pending'] ?? 0 }}</div>
            <div class="text-sm text-white/70 mt-1">Bekleyen</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-2 border-white/20 shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-white">{{ $stats['in_progress'] ?? 0 }}</div>
            <div class="text-sm text-white/70 mt-1">İşlemde</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-2 border-white/20 shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-white">{{ $stats['completed'] ?? 0 }}</div>
            <div class="text-sm text-white/70 mt-1">Tamamlanan</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<x-ui.card class="border-2 border-white/20 shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title class="text-white">Taleplerim</x-ui.card-title>
        <button 
            type="button"
            x-data="{ open: false }"
            @click="open = !open"
            class="flex items-center justify-center gap-2 px-4 py-2 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 text-first-color dark:text-blue-400 font-medium"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            Yeni Talep
        </button>
    </x-ui.card-header>
    <x-ui.card-content>
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
        <div 
            x-data="{ open: false }"
            x-show="open"
            x-cloak
            x-transition
            class="mb-6 p-4 border border-white/20 rounded-lg bg-white/10"
        >
            <form method="POST" action="{{ route('guest.requests.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white">Talep Türü</label>
                        <select name="type" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-first-color dark:text-blue-400" required>
                            <option value="room_service">Oda Servisi</option>
                            <option value="housekeeping">Temizlik</option>
                            <option value="maintenance">Bakım</option>
                            <option value="concierge">Konsiyerj</option>
                            <option value="other">Diğer</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white">Öncelik</label>
                        <select name="priority" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-first-color dark:text-blue-400">
                            <option value="low">Düşük</option>
                            <option value="medium" selected>Orta</option>
                            <option value="high">Yüksek</option>
                            <option value="urgent">Acil</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-white">Başlık</label>
                    <x-ui.input name="title" placeholder="Talep başlığı" required class="text-first-color dark:text-blue-400" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-white">Açıklama</label>
                    <textarea name="description" rows="4" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-first-color dark:text-blue-400" placeholder="Talep açıklaması" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="open = false" class="px-4 py-2 rounded-md second-color hover:bg-[#d4c18a] transition-all duration-200 hover:scale-105 text-first-color dark:text-blue-400 font-medium border-2 border-transparent">
                        İptal
                    </button>
                    <button type="submit" class="flex items-center justify-center gap-2 px-4 py-2 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 text-first-color dark:text-blue-400 font-medium">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Gönder
                    </button>
                </div>
            </form>
        </div>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head class="text-white">Oda</x-ui.table-head>
                    <x-ui.table-head class="text-white">Tür</x-ui.table-head>
                    <x-ui.table-head class="text-white">Başlık</x-ui.table-head>
                    <x-ui.table-head class="text-white">Öncelik</x-ui.table-head>
                    <x-ui.table-head class="text-white">Durum</x-ui.table-head>
                    <x-ui.table-head class="text-white">Tarih</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($requests ?? [] as $request)
                <x-ui.table-row>
                    <x-ui.table-cell class="text-white">{{ $request->guest_room ?? 'N/A' }}</x-ui.table-cell>
                    <x-ui.table-cell class="text-white">
                        <x-ui.badge variant="outline" class="text-white border-white/30">{{ ucfirst(str_replace('_', ' ', $request->category)) }}</x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell class="font-medium text-white">{{ Str::limit($request->title, 40) }}</x-ui.table-cell>
                    <x-ui.table-cell class="text-white">
                        <x-ui.badge variant="{{ $request->priority === 'urgent' ? 'destructive' : ($request->priority === 'high' ? 'default' : 'secondary') }}">
                            {{ ucfirst($request->priority) }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell class="text-white">
                        <x-ui.badge variant="{{ $request->status === 'completed' ? 'default' : ($request->status === 'in_progress' ? 'secondary' : 'outline') }}">
                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell class="text-sm text-white">
                        {{ $request->created_at->format('d.m.Y H:i') }}
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="6" class="text-center py-8 text-white">
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
