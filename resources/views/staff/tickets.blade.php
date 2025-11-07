@extends('layouts.portal')

@section('title', 'Arıza/Teknik - Personel Paneli')

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
    @include('staff.partials.sidebar', ['active' => 'tickets'])
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
            <div class="text-2xl font-semibold text-yellow-600">{{ $stats['open'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Açık</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-blue-600">{{ $stats['in_progress'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Devam Eden</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-green-600">{{ $stats['resolved'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Çözülen</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Arıza / Teknik Talepler</x-ui.card-title>
        <x-ui.button class="gap-2">
            <i data-lucide="wrench" class="w-4 h-4"></i>
            Yeni Talep
        </x-ui.button>
    </x-ui.card-header>
    <x-ui.card-content>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Oda</x-ui.table-head>
                    <x-ui.table-head>Konu</x-ui.table-head>
                    <x-ui.table-head>Kategori</x-ui.table-head>
                    <x-ui.table-head>Öncelik</x-ui.table-head>
                    <x-ui.table-head>Durum</x-ui.table-head>
                    <x-ui.table-head>Atanan</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($tickets ?? [] as $ticket)
                <x-ui.table-row>
                    <x-ui.table-cell>{{ $ticket->room_number ?? 'N/A' }}</x-ui.table-cell>
                    <x-ui.table-cell class="font-medium">{{ Str::limit($ticket->title, 40) }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="outline">{{ ucfirst($ticket->category) }}</x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="{{ $ticket->priority === 'urgent' ? 'destructive' : ($ticket->priority === 'high' ? 'default' : 'secondary') }}">
                            {{ ucfirst($ticket->priority) }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="{{ $ticket->status === 'resolved' ? 'default' : ($ticket->status === 'in_progress' ? 'secondary' : 'outline') }}">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($ticket->assignedTo)
                        <div class="h-6 w-6 rounded-full bg-secondary flex items-center justify-center">
                            <span class="text-xs font-medium">{{ strtoupper(substr($ticket->assignedTo->name, 0, 2)) }}</span>
                        </div>
                        @else
                        <span class="text-muted-foreground text-xs">Atanmamış</span>
                        @endif
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

@if(isset($tickets) && $tickets->count() > 15)
<div class="mt-6">
    {{ $tickets->links() }}
</div>
@endif
@endsection
