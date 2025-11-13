@extends('layouts.portal')

@section('title', 'Görevler - Yönetim Paneli')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'tasks'])
@endsection

@section('content')
<!-- Header Section -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl sm:text-4xl font-bold text-text-light dark:text-text-dark">Görev Yönetimi</h1>
        <p class="text-sm sm:text-base text-text-light/70 dark:text-text-dark/70 mt-1">
            Size atanan görevleri yönetin ve takip edin.
        </p>
    </div>
    <div class="flex gap-2 flex-wrap">
        <div class="relative flex-1 sm:flex-initial sm:w-64">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-text-light/50 dark:text-text-dark/50"></i>
            <input type="text" placeholder="Görevlerde ara..." class="w-full pl-10 pr-4 py-2 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
        </div>
        <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Yeni Görev Ekle
        </button>
    </div>
</div>

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

<!-- Kanban Board -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
        <x-ui.card-header class="pb-3 border-b border-primary-light/10 dark:border-background-dark/10">
            <div class="flex items-center justify-between">
                <x-ui.card-title class="text-sm text-text-light dark:text-text-dark">Yapılacak</x-ui.card-title>
                <span class="text-xs text-text-light/60 dark:text-text-dark/60 bg-primary-light/10 dark:bg-background-dark/20 px-2 py-1 rounded-full">
                    {{ ($tasks ?? collect())->where('status', 'pending')->count() }}
                </span>
            </div>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-3 pt-3">
            @forelse(($tasks ?? collect())->where('status', 'pending') as $task)
            <div class="p-3 rounded-lg bg-white dark:bg-background-dark border border-primary-light/20 dark:border-primary-dark/20 hover:border-primary-light dark:hover:border-primary-dark hover:bg-primary-light/5 dark:hover:bg-primary-dark/10 transition-colors cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="font-medium text-text-light dark:text-text-dark text-sm">{{ Str::limit($task->title, 30) }}</div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $task->priority === 'urgent' || $task->priority === 'high' ? 'bg-red-500/20 text-red-500' : ($task->priority === 'medium' ? 'bg-yellow-500/20 text-yellow-500' : 'bg-gray-500/20 text-gray-400') }}">
                        {{ ucfirst($task->priority === 'urgent' ? 'Yüksek' : ($task->priority === 'high' ? 'Yüksek' : ($task->priority === 'medium' ? 'Orta' : 'Düşük'))) }}
                    </span>
                </div>
                @if($task->description)
                <p class="text-xs text-text-light/70 dark:text-text-dark/70 mb-2 line-clamp-2">{{ $task->description }}</p>
                @endif
                <div class="flex items-center gap-2 text-xs text-text-light/60 dark:text-text-dark/60">
                    @if($task->due_date)
                    <div class="flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3 h-3"></i>
                        <span>{{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}</span>
                    </div>
                    @endif
                    @if($task->assignedTo)
                    <div class="ml-auto flex items-center gap-2">
                        <div class="h-6 w-6 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center">
                            <span class="text-xs font-medium text-primary-light dark:text-primary-dark">{{ strtoupper(substr($task->assignedTo->name, 0, 2)) }}</span>
                        </div>
                    </div>
                    @else
                    <span class="ml-auto text-text-light/40 dark:text-text-dark/40">Atanmamış</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-sm text-text-light/50 dark:text-text-dark/50 text-center py-8">Yapılacak görev yok</div>
            @endforelse
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
        <x-ui.card-header class="pb-3 border-b border-primary-light/10 dark:border-background-dark/10">
            <div class="flex items-center justify-between">
                <x-ui.card-title class="text-sm text-text-light dark:text-text-dark">Devam Ediyor</x-ui.card-title>
                <span class="text-xs text-text-light/60 dark:text-text-dark/60 bg-primary-light/10 dark:bg-background-dark/20 px-2 py-1 rounded-full">
                    {{ ($tasks ?? collect())->where('status', 'in_progress')->count() }}
                </span>
            </div>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-3 pt-3">
            @forelse(($tasks ?? collect())->where('status', 'in_progress') as $task)
            <div class="p-3 rounded-lg bg-white dark:bg-background-dark border border-primary-light/20 dark:border-primary-dark/20 hover:border-primary-light dark:hover:border-primary-dark hover:bg-primary-light/5 dark:hover:bg-primary-dark/10 transition-colors cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="font-medium text-text-light dark:text-text-dark text-sm">{{ Str::limit($task->title, 30) }}</div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $task->priority === 'urgent' || $task->priority === 'high' ? 'bg-red-500/20 text-red-500' : ($task->priority === 'medium' ? 'bg-yellow-500/20 text-yellow-500' : 'bg-gray-500/20 text-gray-400') }}">
                        {{ ucfirst($task->priority === 'urgent' ? 'Yüksek' : ($task->priority === 'high' ? 'Yüksek' : ($task->priority === 'medium' ? 'Orta' : 'Düşük'))) }}
                    </span>
                </div>
                @if($task->description)
                <p class="text-xs text-text-light/70 dark:text-text-dark/70 mb-2 line-clamp-2">{{ $task->description }}</p>
                @endif
                <div class="flex items-center gap-2 text-xs text-text-light/60 dark:text-text-dark/60">
                    @if($task->due_date)
                    <div class="flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3 h-3"></i>
                        <span>{{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}</span>
                    </div>
                    @endif
                    @if($task->assignedTo)
                    <div class="ml-auto flex items-center gap-2">
                        <div class="h-6 w-6 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center">
                            <span class="text-xs font-medium text-primary-light dark:text-primary-dark">{{ strtoupper(substr($task->assignedTo->name, 0, 2)) }}</span>
                        </div>
                    </div>
                    @else
                    <span class="ml-auto text-text-light/40 dark:text-text-dark/40">Atanmamış</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-sm text-text-light/50 dark:text-text-dark/50 text-center py-8">Devam eden görev yok</div>
            @endforelse
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
        <x-ui.card-header class="pb-3 border-b border-primary-light/10 dark:border-background-dark/10">
            <div class="flex items-center justify-between">
                <x-ui.card-title class="text-sm text-text-light dark:text-text-dark">Onay Bekliyor</x-ui.card-title>
                <span class="text-xs text-text-light/60 dark:text-text-dark/60 bg-primary-light/10 dark:bg-background-dark/20 px-2 py-1 rounded-full">
                    {{ ($tasks ?? collect())->where('status', 'awaiting_approval')->count() }}
                </span>
            </div>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-3 pt-3">
            @forelse(($tasks ?? collect())->where('status', 'awaiting_approval') as $task)
            <div class="p-3 rounded-lg bg-white dark:bg-background-dark border border-primary-light/20 dark:border-primary-dark/20 hover:border-primary-light dark:hover:border-primary-dark hover:bg-primary-light/5 dark:hover:bg-primary-dark/10 transition-colors cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="font-medium text-text-light dark:text-text-dark text-sm">{{ Str::limit($task->title, 30) }}</div>
                </div>
                <div class="flex items-center gap-2 text-xs text-text-light/60 dark:text-text-dark/60">
                    @if($task->due_date)
                    <div class="flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3 h-3"></i>
                        <span>{{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}</span>
                    </div>
                    @endif
                    @if($task->assignedTo)
                    <div class="ml-auto flex items-center gap-2">
                        <div class="h-6 w-6 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center">
                            <span class="text-xs font-medium text-primary-light dark:text-primary-dark">{{ strtoupper(substr($task->assignedTo->name, 0, 2)) }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-sm text-text-light/50 dark:text-text-dark/50 text-center py-8">Onay bekleyen görev yok</div>
            @endforelse
        </x-ui.card-content>
    </x-ui.card>

    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
        <x-ui.card-header class="pb-3 border-b border-primary-light/10 dark:border-background-dark/10">
            <div class="flex items-center justify-between">
                <x-ui.card-title class="text-sm text-text-light dark:text-text-dark">Tamamlanan</x-ui.card-title>
                <span class="text-xs text-text-light/60 dark:text-text-dark/60 bg-primary-light/10 dark:bg-background-dark/20 px-2 py-1 rounded-full">
                    {{ ($tasks ?? collect())->where('status', 'completed')->count() }}
                </span>
            </div>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-3 pt-3">
            @forelse(($tasks ?? collect())->where('status', 'completed')->take(5) as $task)
            <div class="p-3 rounded-lg bg-background-light dark:bg-background-dark border border-surface-light dark:border-surface-dark opacity-75">
                <div class="flex items-center justify-between mb-2">
                    <div class="font-medium text-text-light dark:text-text-dark text-sm line-through">{{ Str::limit($task->title, 30) }}</div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400">
                        {{ ucfirst($task->priority === 'urgent' ? 'Yüksek' : ($task->priority === 'high' ? 'Yüksek' : ($task->priority === 'medium' ? 'Orta' : 'Düşük'))) }}
                    </span>
                </div>
                <div class="flex items-center gap-2 text-xs text-text-light/60 dark:text-text-dark/60">
                    @if($task->assignedTo)
                    <div class="flex items-center gap-2">
                        <div class="h-6 w-6 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center">
                            <span class="text-xs font-medium text-primary-light dark:text-primary-dark">{{ strtoupper(substr($task->assignedTo->name, 0, 2)) }}</span>
                        </div>
                    </div>
                    @endif
                    @if($task->completed_at)
                    <div class="ml-auto flex items-center gap-1">
                        <i data-lucide="check" class="w-3 h-3 text-green-500"></i>
                        <span>{{ \Carbon\Carbon::parse($task->completed_at)->format('d.m.Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-sm text-text-light/50 dark:text-text-dark/50 text-center py-8">Tamamlanan görev yok</div>
            @endforelse
        </x-ui.card-content>
    </x-ui.card>
</div>

@if(($tasks ?? collect())->count() > 15)
<div class="mt-6">
    {{ $tasks->links() }}
</div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
@endsection
