@extends('layouts.portal')

@section('title', 'Görevlerim - Personel Paneli')

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
    @include('staff.partials.sidebar', ['active' => 'mytasks'])
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

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between flex-wrap gap-2">
        <x-ui.card-title>Görevlerim</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content>
        <div class="overflow-x-auto">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head class="min-w-[200px]">Görev</x-ui.table-head>
                        <x-ui.table-head class="hidden sm:table-cell">Öncelik</x-ui.table-head>
                        <x-ui.table-head class="hidden md:table-cell">Durum</x-ui.table-head>
                        <x-ui.table-head class="hidden lg:table-cell">Bitiş Tarihi</x-ui.table-head>
                        <x-ui.table-head class="text-right">İşlem</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($tasks ?? [] as $task)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">
                            <div class="flex flex-col gap-1">
                                <span>{{ $task->title }}</span>
                                <div class="flex flex-wrap gap-2 sm:hidden">
                                    <x-ui.badge variant="{{ $task->priority === 'urgent' ? 'destructive' : ($task->priority === 'high' ? 'default' : 'secondary') }}" class="text-xs">
                                        {{ ucfirst($task->priority) }}
                                    </x-ui.badge>
                                    <x-ui.badge variant="{{ $task->status === 'completed' ? 'default' : ($task->status === 'in_progress' ? 'secondary' : 'outline') }}" class="text-xs">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </x-ui.badge>
                                    @if($task->due_date)
                                        <span class="text-xs text-muted-foreground">
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </x-ui.table-cell>
                        <x-ui.table-cell class="hidden sm:table-cell">
                            <x-ui.badge variant="{{ $task->priority === 'urgent' ? 'destructive' : ($task->priority === 'high' ? 'default' : 'secondary') }}">
                                {{ ucfirst($task->priority) }}
                            </x-ui.badge>
                        </x-ui.table-cell>
                        <x-ui.table-cell class="hidden md:table-cell">
                            <x-ui.badge variant="{{ $task->status === 'completed' ? 'default' : ($task->status === 'in_progress' ? 'secondary' : 'outline') }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </x-ui.badge>
                        </x-ui.table-cell>
                        <x-ui.table-cell class="hidden lg:table-cell">
                            @if($task->due_date)
                                {{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}
                            @else
                                <span class="text-muted-foreground">-</span>
                            @endif
                        </x-ui.table-cell>
                        <x-ui.table-cell class="text-right">
                            <form method="POST" action="{{ route('staff.tasks.updateStatus', $task) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $task->status === 'pending' ? 'in_progress' : ($task->status === 'in_progress' ? 'completed' : 'pending') }}" />
                                <x-ui.button size="sm" variant="outline" type="submit" class="w-full sm:w-auto">
                                    {{ $task->status === 'pending' ? 'Başlat' : ($task->status === 'in_progress' ? 'Tamamla' : 'Yeniden Aç') }}
                                </x-ui.button>
                            </form>
                        </x-ui.table-cell>
                    </x-ui.table-row>
                    @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="5" class="text-center py-8 text-muted-foreground">
                            Henüz görev yok
                        </x-ui.table-cell>
                    </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </div>
    </x-ui.card-content>
</x-ui.card>

@if(isset($tasks) && $tasks->count() > 15)
<div class="mt-6">
    {{ $tasks->links() }}
</div>
@endif
@endsection
