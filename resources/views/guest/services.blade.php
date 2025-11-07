@extends('layouts.portal')

@section('title', 'Oda Servisi - Misafir Paneli')

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
    @include('guest.partials.sidebar', ['active' => 'services'])
@endsection

@section('content')
<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Müsait Odalar</x-ui.card-title>
        <x-ui.badge variant="secondary">{{ count($rooms ?? []) }} Oda</x-ui.badge>
    </x-ui.card-header>
    <x-ui.card-content>
        @if(isset($rooms) && $rooms->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($rooms as $room)
            <x-ui.card class="border border-border/60">
                <x-ui.card-content class="p-4 space-y-2">
                    <div class="font-medium text-lg">Oda {{ $room->room_number }}</div>
                    <div class="text-sm text-muted-foreground">
                        {{ $room->roomType->name ?? 'N/A' }}
                    </div>
                    <div class="text-xs text-muted-foreground">
                        Kapasite: {{ $room->roomType->capacity ?? 'N/A' }} kişi
                    </div>
                    <div class="font-semibold text-primary mt-2">
                        ₺{{ number_format($room->roomType->price_per_night ?? 0, 2) }}/gece
                    </div>
                    @if($room->roomType->description)
                    <div class="text-xs text-muted-foreground mt-2">
                        {{ Str::limit($room->roomType->description, 80) }}
                    </div>
                    @endif
                    <x-ui.button size="sm" class="mt-3 w-full">Rezervasyon Yap</x-ui.button>
                </x-ui.card-content>
            </x-ui.card>
            @endforeach
        </div>
        @else
        <div class="text-sm text-muted-foreground text-center py-8">
            Şu anda müsait oda yok
        </div>
        @endif
    </x-ui.card-content>
</x-ui.card>
@endsection
