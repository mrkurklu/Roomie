@extends('layouts.portal')

@section('title', 'Yönetim Paneli - Dashboard')

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
    @include('admin.partials.sidebar', ['active' => 'dashboard'])
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-text-light dark:text-text-dark">Admin Dashboard</h1>
            <p class="text-sm sm:text-base text-text-light/70 dark:text-text-dark/70 mt-1">
                Welcome back, {{ auth()->user()->name }}. Here's your hotel's performance overview.
            </p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-sm font-medium">
                <i data-lucide="download" class="w-4 h-4"></i>
                Generate Report
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="pt-6">
                <div class="flex items-center justify-between mb-2">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-primary-light dark:text-primary-dark"></i>
                </div>
                <div class="text-2xl font-semibold text-text-light dark:text-text-dark">{{ $stats['total_guests'] ?? 0 }}</div>
                <div class="text-sm text-text-light/70 dark:text-text-dark/70 mt-1">Toplam Misafir</div>
                <div class="flex items-center gap-1 mt-2 text-xs text-text-light/60 dark:text-text-dark/60">
                    <i data-lucide="users" class="w-3 h-3"></i>
                    <span>{{ $stats['total_rooms'] ?? 0 }} oda</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="pt-6">
                <div class="flex items-center justify-between mb-2">
                    <i data-lucide="pie-chart" class="w-5 h-5 text-primary-light dark:text-primary-dark"></i>
                </div>
                <div class="text-2xl font-semibold text-text-light dark:text-text-dark">{{ $stats['occupancy_rate'] ?? 0 }}%</div>
                <div class="text-sm text-text-light/70 dark:text-text-dark/70 mt-1">Doluluk Oranı</div>
                <div class="flex items-center gap-1 mt-2 text-xs text-text-light/60 dark:text-text-dark/60">
                    <i data-lucide="bed" class="w-3 h-3"></i>
                    <span>{{ $stats['occupied_rooms'] ?? 0 }}/{{ $stats['total_rooms'] ?? 0 }} dolu</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="pt-6">
                <div class="flex items-center justify-between mb-2">
                    <i data-lucide="calendar-plus" class="w-5 h-5 text-primary-light dark:text-primary-dark"></i>
                </div>
                <div class="text-2xl font-semibold text-text-light dark:text-text-dark">{{ $stats['new_bookings'] ?? 0 }}</div>
                <div class="text-sm text-text-light/70 dark:text-text-dark/70 mt-1">Yeni Rezervasyonlar</div>
                <div class="flex items-center gap-1 mt-2 text-xs text-text-light/60 dark:text-text-dark/60">
                    <i data-lucide="calendar" class="w-3 h-3"></i>
                    <span>Son 30 gün</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="pt-6">
                <div class="flex items-center justify-between mb-2">
                    <i data-lucide="log-in" class="w-5 h-5 text-primary-light dark:text-primary-dark"></i>
                </div>
                <div class="text-2xl font-semibold text-text-light dark:text-text-dark">{{ $stats['guest_checkins'] ?? 0 }}</div>
                <div class="text-sm text-text-light/70 dark:text-text-dark/70 mt-1">Bugün Check-in</div>
                <div class="flex items-center gap-1 mt-2 text-xs text-text-light/60 dark:text-text-dark/60">
                    <i data-lucide="log-in" class="w-3 h-3"></i>
                    <span>Bugün</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <!-- Charts and Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Trends Chart -->
        <div class="lg:col-span-2">
            <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
                <x-ui.card-header class="pb-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card-title class="text-text-light dark:text-text-dark">Mesaj İstatistikleri</x-ui.card-title>
                            <p class="text-xs text-text-light/70 dark:text-text-dark/70 mt-1">Son 30 Gün</p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-text-light dark:text-text-dark">{{ $stats['total_messages'] ?? 0 }}</div>
                            <div class="flex items-center gap-1 text-xs text-text-light/60 dark:text-text-dark/60">
                                <i data-lucide="message-square" class="w-3 h-3"></i>
                                <span>Toplam mesaj</span>
                            </div>
                        </div>
                    </div>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="h-64 flex items-center justify-center text-text-light/50 dark:text-text-dark/50">
                        <div class="text-center">
                            <i data-lucide="message-square" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                            <p class="text-sm">Mesaj grafiği burada gösterilecek</p>
                            <p class="text-xs mt-2">Okunmamış: {{ $stats['unread_messages'] ?? 0 }}</p>
                        </div>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-1">
            <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
                <x-ui.card-header class="pb-2">
                    <x-ui.card-title class="text-text-light dark:text-text-dark">Recent Activity</x-ui.card-title>
                    <p class="text-xs text-text-light/70 dark:text-text-dark/70 mt-1">Latest updates and notifications.</p>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-3">
                    @forelse($recentActivity ?? [] as $activity)
                    <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-primary-light/5 dark:hover:bg-primary-dark/10 transition-colors">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $activity['color'] ?? 'bg-primary-light/20 dark:bg-primary-dark/20' }}">
                            <i data-lucide="{{ $activity['icon'] ?? 'circle' }}" class="w-4 h-4 {{ $activity['iconColor'] ?? 'text-primary-light dark:text-primary-dark' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-text-light dark:text-text-dark">{{ $activity['message'] ?? '' }}</p>
                            <p class="text-xs text-text-light/60 dark:text-text-dark/60 mt-1">{{ $activity['time'] ?? '' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i data-lucide="activity" class="w-12 h-12 mx-auto text-text-light/30 dark:text-text-dark/30 mb-2"></i>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70">Henüz aktivite yok</p>
                    </div>
                    @endforelse
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </div>
</div>
@endsection
