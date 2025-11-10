@props(['active' => 'welcome'])

<div class="space-y-4">
    <x-ui.card class="border border-[#929aab] shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-muted-foreground">{{ __('guest_menu') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <a href="{{ route('guest.welcome') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'welcome' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="hotel" class="w-4 h-4"></i>
                <span>{{ __('welcome') }}</span>
            </a>
            <a href="{{ route('guest.chat') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'chat' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                <span>{{ __('live_chat') }}</span>
            </a>
            <a href="{{ route('guest.requests') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'requests' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="concierge-bell" class="w-4 h-4"></i>
                <span>{{ __('requests') }}</span>
            </a>
            <a href="{{ route('guest.services') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'services' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
                <span>Hizmetlerimiz</span>
            </a>
            <a href="{{ route('guest.amenities') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'amenities' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="wifi" class="w-4 h-4"></i>
                <span>{{ __('hotel_amenities') }}</span>
            </a>
            <a href="{{ route('guest.feedback') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'feedback' ? 'bg-first-color text-white shadow-md' : 'bg-transparent hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-300 hover:text-first-color dark:hover:text-blue-400 border-2 border-[#929aab] dark:border-slate-600 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg' }}">
                <i data-lucide="star" class="w-4 h-4"></i>
                <span>{{ __('feedback') }}</span>
            </a>
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border border-[#929aab] shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-muted-foreground">{{ __('help') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <x-ui.button variant="outline" class="w-full gap-2">
                <i data-lucide="phone-call" class="w-4 h-4"></i>
                {{ __('call_reception') }}
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

