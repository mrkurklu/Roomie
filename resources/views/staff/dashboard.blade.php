@extends('layouts.portal')

@section('title', 'Dashboard - Personel Paneli')

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
    @include('staff.partials.sidebar', ['active' => 'dashboard'])
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

<!-- İstatistikler -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-semibold">{{ $stats['unread_messages'] ?? 0 }}</div>
                    <div class="text-sm text-muted-foreground mt-1">Okunmamış Mesaj</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <i data-lucide="mail" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-semibold text-yellow-600">{{ $stats['pending_tasks'] ?? 0 }}</div>
                    <div class="text-sm text-muted-foreground mt-1">Bekleyen Görev</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-semibold text-blue-600">{{ $stats['in_progress_tasks'] ?? 0 }}</div>
                    <div class="text-sm text-muted-foreground mt-1">Devam Eden Görev</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <i data-lucide="play-circle" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<!-- Gelen Mesajlar (Uyarılar) -->
@if($unreadMessages && $unreadMessages->count() > 0)
<x-ui.card class="border-none shadow-sm mb-6">
    <x-ui.card-header class="pb-3">
        <div class="flex items-center justify-between">
            <x-ui.card-title class="flex items-center gap-2">
                <i data-lucide="bell" class="w-5 h-5 text-orange-500"></i>
                <span>Yeni Mesajlar</span>
                @if($stats['unread_messages'] > 0)
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-orange-100 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400">
                        {{ $stats['unread_messages'] }}
                    </span>
                @endif
            </x-ui.card-title>
            <a href="{{ route('staff.inbox') }}" class="text-sm text-primary-light dark:text-primary-dark hover:underline">
                Tümünü Gör
            </a>
        </div>
    </x-ui.card-header>
    <x-ui.card-content>
        <div class="space-y-3">
            @foreach($unreadMessages as $message)
            <div class="p-4 rounded-lg border border-orange-200 dark:border-orange-800 bg-orange-50/50 dark:bg-orange-900/10 hover:bg-orange-100/50 dark:hover:bg-orange-900/20 transition-colors">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-sm text-gray-900 dark:text-gray-100">
                                {{ $message->fromUser->name ?? 'Bilinmeyen' }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $message->created_at->diffForHumans() }}
                            </span>
                        </div>
                        @if($message->subject)
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">
                            {{ $message->subject }}
                        </p>
                        @endif
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                            {{ $message->content }}
                        </p>
                    </div>
                    <a href="{{ route('staff.inbox', ['to_user_id' => $message->from_user_id]) }}" 
                       class="flex-shrink-0 px-3 py-1.5 text-xs font-medium rounded-md bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity">
                        Görüntüle
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </x-ui.card-content>
</x-ui.card>
@endif

<!-- Atanan Görevler -->
<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-3">
        <div class="flex items-center justify-between">
            <x-ui.card-title class="flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                <span>Atanan Görevler</span>
            </x-ui.card-title>
            <a href="{{ route('staff.tasks') }}" class="text-sm text-primary-light dark:text-primary-dark hover:underline">
                Tümünü Gör
            </a>
        </div>
    </x-ui.card-header>
    <x-ui.card-content>
        @if($tasks && $tasks->count() > 0)
        <div class="space-y-3">
            @foreach($tasks as $task)
            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-light/50 dark:hover:border-primary-dark/50 hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-all">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="font-semibold text-base text-gray-900 dark:text-gray-100">
                                {{ $task->title }}
                            </h3>
                            @if($task->priority === 'high')
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400">
                                    Yüksek Öncelik
                                </span>
                            @elseif($task->priority === 'medium')
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400">
                                    Orta Öncelik
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                    Düşük Öncelik
                                </span>
                            @endif
                            @if($task->status === 'pending')
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400">
                                    Bekliyor
                                </span>
                            @elseif($task->status === 'in_progress')
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                                    Devam Ediyor
                                </span>
                            @endif
                        </div>
                        @if($task->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">
                            {{ $task->description }}
                        </p>
                        @endif
                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                            @if($task->due_date)
                            <span class="flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3"></i>
                                {{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}
                            </span>
                            @endif
                            @if($task->createdBy)
                            <span class="flex items-center gap-1">
                                <i data-lucide="user" class="w-3 h-3"></i>
                                {{ $task->createdBy->name }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 flex-shrink-0">
                        @if($task->status === 'pending')
                        <form action="{{ route('staff.tasks.updateStatus', $task->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="in_progress">
                            <x-ui.button type="submit" size="sm" class="gap-1.5">
                                <i data-lucide="play" class="w-3 h-3"></i>
                                Başlat
                            </x-ui.button>
                        </form>
                        @elseif($task->status === 'in_progress')
                        <form action="{{ route('staff.tasks.updateStatus', $task->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <x-ui.button type="submit" size="sm" variant="outline" class="gap-1.5">
                                <i data-lucide="check" class="w-3 h-3"></i>
                                Tamamla
                            </x-ui.button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-background-dark flex items-center justify-center mx-auto mb-4">
                <i data-lucide="clipboard-check" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Henüz atanan görev yok</p>
        </div>
        @endif
    </x-ui.card-content>
</x-ui.card>
@endsection

