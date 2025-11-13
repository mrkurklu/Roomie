@extends('layouts.portal')

@section('title', 'Etkinlikler - Misafir Paneli')

@section('content')
<div class="min-h-screen bg-background-light dark:bg-background-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-secondary-accent-light dark:text-secondary-accent-dark mb-2">
                Etkinlikler & Duyurular
            </h1>
            <p class="text-sm sm:text-base text-text-light/70 dark:text-text-dark/70">
                Otelimizdeki güncel etkinlikleri ve duyuruları keşfedin
            </p>
        </div>

        <!-- Events Grid -->
        @if($events && $events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            @foreach($events as $event)
            <a href="{{ route('guest.events.show', $event->id) }}" class="group block">
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-transparent group-hover:border-primary-light dark:group-hover:border-primary-dark transition-all duration-300 transform group-hover:-translate-y-1 overflow-hidden h-full flex flex-col">
                    <!-- Event Image -->
                    @if($event->image_path)
                    <div class="w-full h-48 sm:h-56 bg-center bg-cover bg-no-repeat" style="background-image: url('{{ asset($event->image_path) }}');">
                    </div>
                    @else
                    <div class="w-full h-48 sm:h-56 bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-5xl sm:text-6xl text-primary-light dark:text-primary-dark">calendar_month</span>
                    </div>
                    @endif

                    <!-- Event Content -->
                    <div class="p-4 sm:p-6 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="text-lg sm:text-xl font-bold text-secondary-accent-light dark:text-secondary-accent-dark line-clamp-2 flex-1">
                                {{ $event->title }}
                            </h3>
                            @if($event->priority > 0)
                            <span class="flex-shrink-0 px-2 py-1 text-xs font-semibold rounded-full bg-primary-light/20 dark:bg-primary-dark/20 text-primary-light dark:text-primary-dark">
                                Öncelikli
                            </span>
                            @endif
                        </div>

                        @if($event->start_date)
                        <div class="flex items-center gap-2 text-sm text-text-light/70 dark:text-text-dark/70 mb-3">
                            <span class="material-symbols-outlined text-base">schedule</span>
                            <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d.m.Y H:i') }}</span>
                            @if($event->end_date)
                            <span> - </span>
                            <span>{{ \Carbon\Carbon::parse($event->end_date)->format('d.m.Y H:i') }}</span>
                            @endif
                        </div>
                        @endif

                        @if($event->description)
                        <p class="text-sm text-text-light/80 dark:text-text-dark/80 line-clamp-3 mb-3 flex-1">
                            {{ $event->description }}
                        </p>
                        @endif

                        @if($event->location)
                        <div class="flex items-center gap-2 text-sm text-text-light/70 dark:text-text-dark/70 mt-auto">
                            <span class="material-symbols-outlined text-base">location_on</span>
                            <span class="line-clamp-1">{{ $event->location }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
        <div class="flex justify-center items-center gap-2 mt-6 sm:mt-8">
            @if($events->onFirstPage())
            <span class="px-4 py-2 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light/50 dark:text-text-dark/50 cursor-not-allowed">
                <span class="material-symbols-outlined text-lg">chevron_left</span>
            </span>
            @else
            <a href="{{ $events->previousPageUrl() }}" class="px-4 py-2 rounded-lg bg-surface-light dark:bg-surface-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark transition-colors">
                <span class="material-symbols-outlined text-lg">chevron_left</span>
            </a>
            @endif

            <span class="px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white text-sm font-medium">
                {{ $events->currentPage() }} / {{ $events->lastPage() }}
            </span>

            @if($events->hasMorePages())
            <a href="{{ $events->nextPageUrl() }}" class="px-4 py-2 rounded-lg bg-surface-light dark:bg-surface-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 text-text-light dark:text-text-dark transition-colors">
                <span class="material-symbols-outlined text-lg">chevron_right</span>
            </a>
            @else
            <span class="px-4 py-2 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light/50 dark:text-text-dark/50 cursor-not-allowed">
                <span class="material-symbols-outlined text-lg">chevron_right</span>
            </span>
            @endif
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="text-center py-12 sm:py-16">
            <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-4 sm:mb-6 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl sm:text-5xl text-primary-light dark:text-primary-dark">event_busy</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold text-secondary-accent-light dark:text-secondary-accent-dark mb-2">
                Henüz Etkinlik Yok
            </h3>
            <p class="text-sm sm:text-base text-text-light/70 dark:text-text-dark/70 max-w-md mx-auto">
                Şu anda görüntülenecek aktif etkinlik bulunmamaktadır. Yeni etkinlikler eklendiğinde burada görünecektir.
            </p>
        </div>
        @endif
    </div>
</div>
@endsection


