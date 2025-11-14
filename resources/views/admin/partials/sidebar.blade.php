@props(['active' => 'dashboard'])

<div class="space-y-4">
    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-text-light/70 dark:text-text-dark/70">{{ __('menu') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'dashboard' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                <span>{{ __('dashboard') }}</span>
            </a>
            <a href="{{ route('admin.tasks') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'tasks' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                <span>{{ __('tasks') }}</span>
            </a>
            <a href="{{ route('admin.messages') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'messages' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                <span>Mesajlar</span>
            </a>
            <a href="{{ route('admin.guests') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'guests' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>{{ __('guests') }}</span>
            </a>
            <a href="{{ route('admin.rooms') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'rooms' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="door-open" class="w-4 h-4"></i>
                <span>Odalar</span>
            </a>
            <a href="{{ route('admin.staff') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'staff' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="user-check" class="w-4 h-4"></i>
                <span>Personeller</span>
            </a>
            <a href="{{ route('admin.billing') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'billing' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="key-square" class="w-4 h-4"></i>
                <span>{{ __('billing') }}</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'analytics' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="trending-up" class="w-4 h-4"></i>
                <span>{{ __('analytics') }}</span>
            </a>
            <a href="{{ route('admin.events') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'events' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>Etkinlikler</span>
            </a>
            <a href="{{ route('admin.feedbacks') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'feedbacks' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="message-square-heart" class="w-4 h-4"></i>
                <span>Geri Bildirimler</span>
            </a>
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'settings' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="settings" class="w-4 h-4"></i>
                <span>{{ __('settings') }}</span>
            </a>
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-text-light/70 dark:text-text-dark/70">{{ __('filters') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-text-light/50 dark:text-text-dark/50"></i>
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

