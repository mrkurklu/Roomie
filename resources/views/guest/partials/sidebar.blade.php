@props(['active' => 'welcome'])

<div class="space-y-4">
    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-text-light/70 dark:text-text-dark/70">{{ __('guest_menu') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <a href="{{ route('guest.welcome') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'welcome' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="hotel" class="w-4 h-4"></i>
                <span>{{ __('welcome') }}</span>
            </a>
            <a href="{{ route('guest.chat') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'chat' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                <span>{{ __('live_chat') }}</span>
            </a>
            <a href="{{ route('guest.requests') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'requests' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="concierge-bell" class="w-4 h-4"></i>
                <span>{{ __('requests') }}</span>
            </a>
            <a href="{{ route('guest.services') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'services' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
                <span>Hizmetlerimiz</span>
            </a>
            <a href="{{ route('guest.events') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'events' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>Etkinlikler</span>
            </a>
            <a href="{{ route('guest.amenities') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'amenities' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="wifi" class="w-4 h-4"></i>
                <span>{{ __('hotel_amenities') }}</span>
            </a>
            <a href="{{ route('guest.feedback') }}" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ $active === 'feedback' ? 'bg-primary-light dark:bg-primary-dark text-white shadow-md' : 'bg-transparent hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark' }}">
                <i data-lucide="star" class="w-4 h-4"></i>
                <span>{{ __('feedback') }}</span>
            </a>
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-text-light/70 dark:text-text-dark/70">{{ __('help') }}</x-ui.card-title>
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

