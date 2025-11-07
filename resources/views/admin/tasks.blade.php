@extends('layouts.portal')

@section('title', 'Görevler - Yönetim Paneli')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'tasks'])
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Toplam Görev</div>
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
            <div class="text-sm text-muted-foreground mt-1">Devam Eden</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-green-600">{{ $stats['completed'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Tamamlanan</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

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
        <x-ui.card-title>{{ __('create_task') }}</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content>
        <form method="POST" action="{{ route('admin.tasks.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium">{{ __('task_title') }}</label>
                    <x-ui.input name="title" placeholder="{{ __('task_title_placeholder') }}" required />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">{{ __('priority') }}</label>
                    <select name="priority" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required>
                        <option value="low">{{ __('low') }}</option>
                        <option value="medium" selected>{{ __('medium') }}</option>
                        <option value="high">{{ __('high') }}</option>
                        <option value="urgent">{{ __('urgent') }}</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">{{ __('assigned_person') }}</label>
                    <select name="assigned_to" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">{{ __('unassigned') }}</option>
                        @foreach(\App\Models\User::where('hotel_id', auth()->user()->hotel_id)->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">{{ __('due_date') }} ({{ __('optional') }})</label>
                    <x-ui.input name="due_date" type="date" />
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium">{{ __('description') }}</label>
                <textarea name="description" rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="{{ __('description') }}"></textarea>
            </div>
            <div class="flex justify-end">
                <x-ui.button type="submit">{{ __('create') }}</x-ui.button>
            </div>
        </form>
    </x-ui.card-content>
</x-ui.card>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm">Yapılacak</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-3">
            @forelse(($tasks ?? collect())->where('status', 'pending') as $task)
            <x-ui.card class="p-3 border border-border/60">
                <div class="flex items-center justify-between">
                    <div class="font-medium">{{ Str::limit($task->title, 30) }}</div>
                    <x-ui.badge variant="{{ $task->priority === 'urgent' ? 'destructive' : ($task->priority === 'high' ? 'default' : 'secondary') }}">
                        {{ ucfirst($task->priority) }}
                    </x-ui.badge>
                </div>
                <div class="mt-2 flex items-center gap-2 text-xs text-muted-foreground">
                    @if($task->assignedTo)
                    <div class="h-6 w-6 rounded-full bg-secondary flex items-center justify-center">
                        <span class="text-xs font-medium">{{ strtoupper(substr($task->assignedTo->name, 0, 2)) }}</span>
                    </div>
                    <span>{{ $task->assignedTo->name }}</span>
                    @else
                    <span class="text-muted-foreground">Atanmamış</span>
                    @endif
                </div>
                @if($task->due_date)
                <div class="text-xs text-muted-foreground mt-1">
                    <i data-lucide="calendar" class="w-3 h-3 inline"></i>
                    {{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}
                </div>
                @endif
            </x-ui.card>
            @empty
            <div class="text-sm text-muted-foreground text-center py-4">Yapılacak görev yok</div>
            @endforelse
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm">Devam Eden</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-3">
            @forelse(($tasks ?? collect())->where('status', 'in_progress') as $task)
            <x-ui.card class="p-3 border border-border/60">
                <div class="flex items-center justify-between">
                    <div class="font-medium">{{ Str::limit($task->title, 30) }}</div>
                    <x-ui.badge variant="{{ $task->priority === 'urgent' ? 'destructive' : ($task->priority === 'high' ? 'default' : 'secondary') }}">
                        {{ ucfirst($task->priority) }}
                    </x-ui.badge>
                </div>
                <div class="mt-2 flex items-center gap-2 text-xs text-muted-foreground">
                    @if($task->assignedTo)
                    <div class="h-6 w-6 rounded-full bg-secondary flex items-center justify-center">
                        <span class="text-xs font-medium">{{ strtoupper(substr($task->assignedTo->name, 0, 2)) }}</span>
                    </div>
                    <span>{{ $task->assignedTo->name }}</span>
                    @else
                    <span class="text-muted-foreground">Atanmamış</span>
                    @endif
                </div>
            </x-ui.card>
            @empty
            <div class="text-sm text-muted-foreground text-center py-4">Devam eden görev yok</div>
            @endforelse
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm">Tamamlanan</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-3">
            @forelse(($tasks ?? collect())->where('status', 'completed')->take(5) as $task)
            <x-ui.card class="p-3 border border-border/60 opacity-75">
                <div class="flex items-center justify-between">
                    <div class="font-medium line-through">{{ Str::limit($task->title, 30) }}</div>
                    <x-ui.badge variant="secondary">{{ ucfirst($task->priority) }}</x-ui.badge>
                </div>
                <div class="mt-2 flex items-center gap-2 text-xs text-muted-foreground">
                    @if($task->assignedTo)
                    <div class="h-6 w-6 rounded-full bg-secondary flex items-center justify-center">
                        <span class="text-xs font-medium">{{ strtoupper(substr($task->assignedTo->name, 0, 2)) }}</span>
                    </div>
                    <span>{{ $task->assignedTo->name }}</span>
                    @endif
                    @if($task->completed_at)
                    <span class="ml-auto">{{ \Carbon\Carbon::parse($task->completed_at)->format('d.m.Y') }}</span>
                    @endif
                </div>
            </x-ui.card>
            @empty
            <div class="text-sm text-muted-foreground text-center py-4">Tamamlanan görev yok</div>
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
