@props(['active' => 'welcome'])

<div class="space-y-4">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-muted-foreground">{{ __('guest_menu') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <a href="{{ route('guest.welcome') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'welcome' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="hotel" class="w-4 h-4"></i>
                {{ __('welcome') }}
            </a>
            <a href="{{ route('guest.chat') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'chat' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                {{ __('live_chat') }}
            </a>
            <a href="{{ route('guest.requests') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'requests' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="concierge-bell" class="w-4 h-4"></i>
                {{ __('requests') }}
            </a>
            <a href="{{ route('guest.services') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'services' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
                Hizmetlerimiz
            </a>
            <a href="{{ route('guest.amenities') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'amenities' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="wifi" class="w-4 h-4"></i>
                {{ __('hotel_amenities') }}
            </a>
            <a href="{{ route('guest.feedback') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'feedback' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="star" class="w-4 h-4"></i>
                {{ __('feedback') }}
            </a>
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border-none shadow-sm">
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

