@extends('layouts.portal')

@section('title', 'Bildirimler')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'notifications'])
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Bildirimler</h1>
            <p class="text-sm text-muted-foreground mt-1">Tüm bildirimlerinizi buradan görüntüleyebilirsiniz</p>
        </div>
        <x-ui.button variant="outline" onclick="window.location.reload()">
            <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
            Yenile
        </x-ui.button>
    </div>

    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title>Bildirimler ({{ count($notifications ?? []) }})</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="space-y-3">
                @forelse($notifications ?? [] as $notification)
                @php
                    $colorClasses = [
                        'blue' => 'bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400',
                        'orange' => 'bg-orange-100 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400',
                        'green' => 'bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400',
                        'purple' => 'bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400',
                        'red' => 'bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400',
                    ];
                    $colorClass = $colorClasses[$notification['color']] ?? 'bg-secondary text-secondary-foreground';
                @endphp
                <div class="flex items-start gap-4 p-4 rounded-lg border hover:bg-accent/50 transition-colors">
                    <div class="h-10 w-10 rounded-full {{ $colorClass }} flex items-center justify-center flex-shrink-0">
                        <i data-lucide="{{ $notification['icon'] }}" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1">
                                <div class="text-sm font-medium">{{ $notification['title'] }}</div>
                                <div class="text-sm text-muted-foreground mt-1">{{ $notification['description'] }}</div>
                                <div class="text-xs text-muted-foreground mt-2">{{ $notification['time']->diffForHumans() }}</div>
                            </div>
                            <x-ui.badge variant="outline" class="text-xs">
                                @if($notification['type'] === 'message')
                                    Mesaj
                                @elseif($notification['type'] === 'task')
                                    Görev
                                @elseif($notification['type'] === 'reservation')
                                    Rezervasyon
                                @elseif($notification['type'] === 'request')
                                    Talep
                                @elseif($notification['type'] === 'ticket')
                                    Arıza
                                @endif
                            </x-ui.badge>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <i data-lucide="bell-off" class="w-12 h-12 mx-auto text-muted-foreground mb-4"></i>
                    <div class="text-sm font-medium text-muted-foreground">Henüz bildirim yok</div>
                    <div class="text-xs text-muted-foreground mt-1">Yeni bildirimler burada görünecek</div>
                </div>
                @endforelse
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection

