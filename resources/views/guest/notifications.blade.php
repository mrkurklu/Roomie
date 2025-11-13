@extends('layouts.portal')

@section('title', 'Bildirimler')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush

@section('content')
<div class="w-full space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-text-light dark:text-text-dark">Bildirimler</h1>
            <p class="text-sm sm:text-base text-text-light/70 dark:text-text-dark/70 mt-1">Tüm bildirimlerinizi buradan görüntüleyebilirsiniz</p>
        </div>
        <x-ui.button variant="outline" onclick="window.location.reload()">
            <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
            <span class="hidden sm:inline">Yenile</span>
        </x-ui.button>
    </div>

    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-text-light dark:text-text-dark">Bildirimler ({{ count($notifications ?? []) }})</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="space-y-3">
                @forelse($notifications ?? [] as $notification)
                @php
                    $colorClasses = [
                        'blue' => 'bg-blue-500/20 text-blue-600 dark:text-blue-400',
                        'orange' => 'bg-orange-500/20 text-orange-600 dark:text-orange-400',
                        'green' => 'bg-green-500/20 text-green-600 dark:text-green-400',
                        'purple' => 'bg-purple-500/20 text-purple-600 dark:text-purple-400',
                        'red' => 'bg-red-500/20 text-red-600 dark:text-red-400',
                    ];
                    $colorClass = $colorClasses[$notification['color']] ?? 'bg-primary-light/20 text-primary-light dark:text-primary-dark';
                @endphp
                <div class="flex items-start gap-4 p-4 rounded-lg border border-primary-light/10 dark:border-primary-dark/10 hover:bg-primary-light/5 dark:hover:bg-primary-dark/10 transition-colors">
                    <div class="h-10 w-10 rounded-full {{ $colorClass }} flex items-center justify-center flex-shrink-0">
                        <i data-lucide="{{ $notification['icon'] }}" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1">
                                <div class="text-sm sm:text-base font-medium text-text-light dark:text-text-dark">{{ $notification['title'] }}</div>
                                <div class="text-sm text-text-light/80 dark:text-text-dark/80 mt-1">{{ $notification['description'] }}</div>
                                <div class="text-xs text-text-light/60 dark:text-text-dark/60 mt-2">{{ $notification['time']->diffForHumans() }}</div>
                            </div>
                            <x-ui.badge variant="outline" class="text-xs flex-shrink-0">
                                @if($notification['type'] === 'message')
                                    Mesaj
                                @elseif($notification['type'] === 'request')
                                    Talep
                                @elseif($notification['type'] === 'event')
                                    Etkinlik
                                @endif
                            </x-ui.badge>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <i data-lucide="bell-off" class="w-12 h-12 mx-auto text-text-light/30 dark:text-text-dark/30 mb-4"></i>
                    <div class="text-sm font-medium text-text-light/70 dark:text-text-dark/70">Henüz bildirim yok</div>
                    <div class="text-xs text-text-light/60 dark:text-text-dark/60 mt-1">Yeni bildirimler burada görünecek</div>
                </div>
                @endforelse
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>
@endsection

