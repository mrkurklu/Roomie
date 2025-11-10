@props(['active' => 'mytasks'])

<div class="space-y-4">
    <x-ui.card class="border border-[#929aab] shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-muted-foreground">{{ __('personnel_menu') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <a href="{{ route('staff.tasks') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'mytasks' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                <span>{{ __('my_tasks') }}</span>
            </a>
            <a href="{{ route('staff.schedule') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'schedule' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>{{ __('shift') }}</span>
            </a>
            <a href="{{ route('staff.tickets') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'tickets' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="wrench" class="w-4 h-4"></i>
                <span>{{ __('maintenance') }}</span>
            </a>
            <a href="{{ route('staff.inbox') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'inbox' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="inbox" class="w-4 h-4"></i>
                <span>Mesajlar</span>
            </a>
            <a href="{{ route('staff.resources') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'resources' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="utensils-crossed" class="w-4 h-4"></i>
                <span>{{ __('resources') }}</span>
            </a>
            <a href="{{ route('staff.events') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'events' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>Etkinlikler</span>
            </a>
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border border-[#929aab] shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-muted-foreground">{{ __('quick_actions') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <x-ui.button variant="secondary" class="w-full gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                {{ __('start_shift') }}
            </x-ui.button>
            <x-ui.button variant="outline" class="w-full gap-2">
                <i data-lucide="wrench" class="w-4 h-4"></i>
                {{ __('report_issue') }}
            </x-ui.button>
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

