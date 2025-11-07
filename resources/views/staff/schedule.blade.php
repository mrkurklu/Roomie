@extends('layouts.portal')

@section('title', 'Vardiya - Personel Paneli')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush

@section('sidebar')
    @include('staff.partials.sidebar', ['active' => 'schedule'])
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-5">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Vardiya Takvimi</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="space-y-3">
                    @forelse($schedules ?? [] as $schedule)
                    <div class="p-3 rounded-xl border">
                        <div class="flex items-center justify-between mb-2">
                            <div class="font-medium">{{ \Carbon\Carbon::parse($schedule->date)->format('d.m.Y') }}</div>
                            <x-ui.badge variant="secondary">{{ ucfirst($schedule->shift_type) }}</x-ui.badge>
                        </div>
                        <div class="text-sm text-muted-foreground">
                            <i data-lucide="clock" class="w-3 h-3 inline"></i>
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </div>
                        @if($schedule->notes)
                        <div class="text-xs text-muted-foreground mt-2">{{ $schedule->notes }}</div>
                        @endif
                    </div>
                    @empty
                    <div class="text-sm text-muted-foreground text-center py-8">Henüz vardiya yok</div>
                    @endforelse
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>
    <div class="col-span-12 lg:col-span-7">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Yaklaşan Vardiyalar</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-3">
                @forelse($upcomingSchedules ?? [] as $schedule)
                <div class="flex items-center gap-3 p-3 border rounded-xl">
                    <div class="h-9 w-9 rounded-full bg-secondary flex items-center justify-center">
                        <span class="text-xs font-medium">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium">{{ \Carbon\Carbon::parse($schedule->date)->format('d.m.Y') }}</div>
                        <div class="text-xs text-muted-foreground">{{ ucfirst($schedule->shift_type) }} Vardiya</div>
                    </div>
                    <x-ui.badge variant="secondary" class="mr-2">
                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                    </x-ui.badge>
                    @if($schedule->date == today())
                        <x-ui.badge>Bugün</x-ui.badge>
                    @elseif($schedule->date > today())
                        <x-ui.badge variant="outline">Yaklaşan</x-ui.badge>
                    @endif
                </div>
                @empty
                <div class="text-sm text-muted-foreground text-center py-8">Yaklaşan vardiya yok</div>
                @endforelse
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>

@if(isset($schedules) && $schedules->count() > 15)
<div class="mt-6">
    {{ $schedules->links() }}
</div>
@endif
@endsection
