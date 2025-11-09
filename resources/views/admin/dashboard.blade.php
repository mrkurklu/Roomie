@extends('layouts.portal')

@section('title', 'Yönetim Paneli - Dashboard')

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
    @include('admin.partials.sidebar', ['active' => 'dashboard'])
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 md:col-span-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-ui.card class="border-none shadow-sm">
                <x-ui.card-header class="pb-2 flex flex-row items-center justify-between">
                    <x-ui.card-title class="text-sm text-muted-foreground font-medium">Doluluk</x-ui.card-title>
                    <i data-lucide="hotel" class="w-4 h-4"></i>
                </x-ui.card-header>
                <x-ui.card-content>
                    @php
                        $totalRooms = $stats['total_rooms'] ?? 0;
                        $occupiedRooms = $stats['occupied_rooms'] ?? 0;
                        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
                    @endphp
                    <div class="text-2xl font-semibold tracking-tight">{{ $occupancyRate }}%</div>
                    <div class="text-xs text-muted-foreground mt-1">{{ $occupiedRooms }}/{{ $totalRooms }} oda</div>
                </x-ui.card-content>
            </x-ui.card>
            
            <x-ui.card class="border-none shadow-sm">
                <x-ui.card-header class="pb-2 flex flex-row items-center justify-between">
                    <x-ui.card-title class="text-sm text-muted-foreground font-medium">{{ __('active_tasks') }}</x-ui.card-title>
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="text-2xl font-semibold tracking-tight">{{ $stats['pending_tasks'] ?? 0 }}</div>
                    <div class="text-xs text-muted-foreground mt-1">{{ __('total') }}: {{ $stats['total_tasks'] ?? 0 }}</div>
                </x-ui.card-content>
            </x-ui.card>
        </div>
        
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>{{ __('recent_messages') }}</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4">
                @forelse($recentMessages ?? [] as $message)
                <div class="flex items-start gap-3">
                    <div class="h-8 w-8 rounded-full bg-secondary flex items-center justify-center">
                        <span class="text-xs font-medium">{{ strtoupper(substr($message->fromUser->name ?? 'U', 0, 1)) }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-medium">{{ $message->fromUser->name ?? 'Bilinmeyen' }}</div>
                        <div class="text-sm text-muted-foreground">{{ Str::limit($message->content, 60) }}</div>
                        <div class="text-xs text-muted-foreground mt-1">{{ $message->created_at->diffForHumans() }}</div>
                    </div>
                    @if(!isset($message->is_read) || !$message->is_read)
                        <span class="h-2 w-2 bg-primary rounded-full"></span>
                    @endif
                </div>
                @empty
                <div class="text-sm text-muted-foreground text-center py-4">{{ __('no_messages') }}</div>
                @endforelse
            </x-ui.card-content>
        </x-ui.card>
    </div>
    
    <div class="col-span-12 md:col-span-4 space-y-6">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>{{ __('recent_tasks') }}</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="space-y-3">
                    @forelse($recentTasks ?? [] as $task)
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-accent">
                        <div class="flex-1">
                            <div class="text-sm font-medium">{{ Str::limit($task->title, 30) }}</div>
                            <div class="text-xs text-muted-foreground">
                                {{ $task->assignedTo->name ?? 'Atanmamış' }}
                            </div>
                        </div>
                        <x-ui.badge variant="{{ $task->priority === 'urgent' ? 'destructive' : ($task->priority === 'high' ? 'default' : 'secondary') }}">
                            {{ ucfirst($task->priority) }}
                        </x-ui.badge>
                    </div>
                    @empty
                    <div class="text-sm text-muted-foreground text-center py-4">Henüz görev yok</div>
                    @endforelse
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
@endsection
