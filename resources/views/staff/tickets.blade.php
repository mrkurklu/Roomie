@extends('layouts.portal')

@section('title', 'Bakım Talepleri - Personel Paneli')

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
            <div class="text-2xl font-semibold">{{ ($stats['total_guest_requests'] ?? 0) + ($stats['total_tickets'] ?? 0) }}</div>
            <div class="text-sm text-muted-foreground mt-1">Toplam Talep</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-blue-600">{{ $stats['in_progress_guest_requests'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">İşlemde</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-green-600">{{ $stats['completed_guest_requests'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Tamamlandı</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['total_guest_requests'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Misafir Talepleri</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<!-- Misafir Talepleri -->
@if(isset($guestRequests) && $guestRequests->count() > 0)
<x-ui.card class="border-none shadow-sm mb-6">
    <x-ui.card-header class="pb-2">
        <x-ui.card-title>Misafir Talepleri</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content>
        <div class="space-y-4">
            @foreach($guestRequests as $request)
            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 rounded text-xs font-medium 
                                @if($request->category === 'room_service') bg-orange-100 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400
                                @elseif($request->category === 'housekeeping') bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400
                                @elseif($request->category === 'maintenance') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400
                                @else bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400
                                @endif">
                                @if($request->category === 'room_service') Yiyecek İçecek
                                @elseif($request->category === 'housekeeping') Temizlik
                                @elseif($request->category === 'maintenance') Bakım
                                @elseif($request->category === 'concierge') Eksik Şeyler
                                @else Diğer
                                @endif
                            </span>
                            <span class="px-2 py-1 rounded text-xs font-medium 
                                @if($request->status === 'completed') bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400
                                @elseif($request->status === 'in_progress') bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400
                                @else bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </div>
                        <h3 class="font-semibold text-text-light dark:text-text-dark mb-1">{{ $request->title }}</h3>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70 mb-2">{{ $request->description }}</p>
                        <div class="flex items-center gap-4 text-xs text-text-light/60 dark:text-text-dark/60">
                            <span>Misafir: {{ $request->user->name ?? 'N/A' }}</span>
                            <span>{{ $request->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        @if($request->status !== 'completed')
                        <form method="POST" action="{{ route('staff.guest-requests.updateStatus', $request->id) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-500 hover:bg-green-600 text-white text-sm font-medium transition-colors">
                                Tamamla
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($guestRequests->hasPages())
        <div class="mt-4">
            {{ $guestRequests->links() }}
        </div>
        @endif
    </x-ui.card-content>
</x-ui.card>
@endif

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Bakım Talepleri</x-ui.card-title>
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
