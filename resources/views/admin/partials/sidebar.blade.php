@props(['active' => 'dashboard'])

<div class="space-y-4">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-muted-foreground">{{ __('menu') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'dashboard' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                {{ __('dashboard') }}
            </a>
            <a href="{{ route('admin.tasks') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'tasks' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                {{ __('tasks') }}
            </a>
            <a href="{{ route('admin.messages') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'messages' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                {{ __('messages') }}
            </a>
            <a href="{{ route('admin.guests') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'guests' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="users" class="w-4 h-4"></i>
                {{ __('guests') }}
            </a>
            <a href="{{ route('admin.reservations') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'reservations' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                {{ __('reservations') }}
            </a>
            <a href="{{ route('admin.billing') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'billing' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="key-square" class="w-4 h-4"></i>
                {{ __('billing') }}
            </a>
            <a href="{{ route('admin.analytics') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'analytics' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="trending-up" class="w-4 h-4"></i>
                {{ __('analytics') }}
            </a>
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'settings' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="settings" class="w-4 h-4"></i>
                {{ __('settings') }}
            </a>
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-muted-foreground">{{ __('filters') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"></i>
                <x-ui.input placeholder="{{ __('search_placeholder') }}" class="pl-9" />
            </div>
            <div class="flex items-center justify-between">
                <label for="onlyOpen" class="text-sm">{{ __('only_open') }}</label>
                <input type="checkbox" id="onlyOpen" checked class="h-4 w-4 rounded border-gray-300">
            </div>
            <div class="flex items-center justify-between">
                <label for="vipOnly" class="text-sm">{{ __('vip') }}</label>
                <input type="checkbox" id="vipOnly" class="h-4 w-4 rounded border-gray-300">
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

