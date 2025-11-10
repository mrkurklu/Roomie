@props(['active' => 'dashboard'])

<div class="space-y-4">
    <x-ui.card class="border border-[#929aab] shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-muted-foreground">{{ __('menu') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'dashboard' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                <span>{{ __('dashboard') }}</span>
            </a>
            <a href="{{ route('admin.tasks') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'tasks' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                <span>{{ __('tasks') }}</span>
            </a>
            <a href="{{ route('admin.messages') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'messages' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                <span>Mesajlar</span>
            </a>
            <a href="{{ route('admin.guests') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'guests' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>{{ __('guests') }}</span>
            </a>
            <a href="{{ route('admin.billing') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'billing' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="key-square" class="w-4 h-4"></i>
                <span>{{ __('billing') }}</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'analytics' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="trending-up" class="w-4 h-4"></i>
                <span>{{ __('analytics') }}</span>
            </a>
            <a href="{{ route('admin.events') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'events' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>Etkinlikler</span>
            </a>
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'settings' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="settings" class="w-4 h-4"></i>
                <span>{{ __('settings') }}</span>
            </a>
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border border-[#929aab] shadow-sm">
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

