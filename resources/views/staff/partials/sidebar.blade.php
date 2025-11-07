@props(['active' => 'mytasks'])

<div class="space-y-4">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header class="pb-2">
            <x-ui.card-title class="text-sm text-muted-foreground">{{ __('personnel_menu') }}</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-2">
            <a href="{{ route('staff.tasks') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'mytasks' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                {{ __('my_tasks') }}
            </a>
            <a href="{{ route('staff.schedule') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'schedule' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                {{ __('shift') }}
            </a>
            <a href="{{ route('staff.tickets') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'tickets' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="wrench" class="w-4 h-4"></i>
                {{ __('maintenance') }}
            </a>
            <a href="{{ route('staff.inbox') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'inbox' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="inbox" class="w-4 h-4"></i>
                {{ __('messages') }}
            </a>
            <a href="{{ route('staff.resources') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $active === 'resources' ? 'bg-primary text-primary-foreground' : 'hover:bg-accent' }}">
                <i data-lucide="utensils-crossed" class="w-4 h-4"></i>
                {{ __('resources') }}
            </a>
        </x-ui.card-content>
    </x-ui.card>
    
    <x-ui.card class="border-none shadow-sm">
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

