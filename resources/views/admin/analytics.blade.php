@extends('layouts.portal')

@section('title', 'Analitik - Yönetim Paneli')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthNames = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
        
        
        // Oda doluluk grafiği
        const occupancyCtx = document.getElementById('occupancyChart');
        if (occupancyCtx) {
            const occupancyData = @json($roomOccupancy ?? []);
            new Chart(occupancyCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Müsait', 'Dolu', 'Bakım'],
                    datasets: [{
                        data: [
                            occupancyData.available || 0,
                            occupancyData.occupied || 0,
                            occupancyData.maintenance || 0
                        ],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.5)',
                            'rgba(59, 130, 246, 0.5)',
                            'rgba(251, 191, 36, 0.5)'
                        ],
                        borderColor: [
                            'rgb(34, 197, 94)',
                            'rgb(59, 130, 246)',
                            'rgb(251, 191, 36)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        // Görev durumu grafiği
        const taskCtx = document.getElementById('taskChart');
        if (taskCtx) {
            const taskData = @json($taskStatus ?? []);
            new Chart(taskCtx, {
                type: 'bar',
                data: {
                    labels: ['Bekleyen', 'Devam Eden', 'Tamamlanan'],
                    datasets: [{
                        label: 'Görev Sayısı',
                        data: [
                            taskData.pending || 0,
                            taskData.in_progress || 0,
                            taskData.completed || 0
                        ],
                        backgroundColor: [
                            'rgba(251, 191, 36, 0.5)',
                            'rgba(59, 130, 246, 0.5)',
                            'rgba(34, 197, 94, 0.5)'
                        ],
                        borderColor: [
                            'rgb(251, 191, 36)',
                            'rgb(59, 130, 246)',
                            'rgb(34, 197, 94)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'analytics'])
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-text-light/70 dark:text-text-dark/70 mb-1">
                <span>Home</span>
                <span>/</span>
                <span>Analytics</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-text-light dark:text-text-dark">Analytics Dashboard</h1>
        </div>
        <div class="flex gap-2 flex-wrap">
            <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-surface-dark text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-sm font-medium border border-primary-light/20 dark:border-primary-dark/20">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                Last 30 Days
            </button>
            <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-surface-dark text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-sm font-medium border border-primary-light/20 dark:border-primary-dark/20">
                <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                Compare
            </button>
            <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-surface-dark text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-sm font-medium border border-primary-light/20 dark:border-primary-dark/20">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                Export PDF
            </button>
            <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export Excel
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="pt-6">
                <div class="text-2xl font-semibold text-text-light dark:text-text-dark">${{ number_format($stats['total_revenue'] ?? 125430, 2) }}</div>
                <div class="text-sm text-text-light/70 dark:text-text-dark/70 mt-1">Total Revenue</div>
                <div class="flex items-center gap-1 mt-2 text-xs text-green-500">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    <span>↑ +5.2% vs last month</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="pt-6">
                @php
                    $totalRooms = $stats['total_rooms'] ?? 100;
                    $occupiedRooms = $stats['occupied_rooms'] ?? 85;
                    $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 85;
                @endphp
                <div class="text-2xl font-semibold text-text-light dark:text-text-dark">{{ $occupancyRate }}%</div>
                <div class="text-sm text-text-light/70 dark:text-text-dark/70 mt-1">Occupancy Rate</div>
                <div class="flex items-center gap-1 mt-2 text-xs text-green-500">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    <span>↑ +1.8% vs last month</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="pt-6">
                <div class="text-2xl font-semibold text-text-light dark:text-text-dark">${{ number_format($stats['average_daily_rate'] ?? 150.75, 2) }}</div>
                <div class="text-sm text-text-light/70 dark:text-text-dark/70 mt-1">Average Daily Rate</div>
                <div class="flex items-center gap-1 mt-2 text-xs text-red-500">
                    <i data-lucide="trending-down" class="w-3 h-3"></i>
                    <span>↓ -0.5% vs last month</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="pt-6">
                <div class="text-2xl font-semibold text-text-light dark:text-text-dark">{{ $stats['new_bookings'] ?? 210 }}</div>
                <div class="text-sm text-text-light/70 dark:text-text-dark/70 mt-1">New Bookings</div>
                <div class="flex items-center gap-1 mt-2 text-xs text-green-500">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    <span>↑ +12% vs last month</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title class="text-text-light dark:text-text-dark">Revenue Over Time</x-ui.card-title>
                <p class="text-xs text-text-light/70 dark:text-text-dark/70 mt-1">Monthly revenue trends</p>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="h-64 flex items-center justify-center text-text-light/50 dark:text-text-dark/50">
                    <div class="text-center">
                        <i data-lucide="line-chart" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                        <p class="text-sm">Revenue chart will be displayed here</p>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title class="text-text-light dark:text-text-dark">Booking Sources</x-ui.card-title>
                <p class="text-xs text-text-light/70 dark:text-text-dark/70 mt-1">Distribution by source</p>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="h-64 flex items-center justify-center text-text-light/50 dark:text-text-dark/50">
                    <div class="text-center">
                        <i data-lucide="pie-chart" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                        <p class="text-sm">Booking sources chart will be displayed here</p>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title class="text-text-light dark:text-text-dark">Occupancy by Room Type</x-ui.card-title>
                <p class="text-xs text-text-light/70 dark:text-text-dark/70 mt-1">Room category breakdown</p>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="h-64 flex items-center justify-center text-text-light/50 dark:text-text-dark/50">
                    <div class="text-center">
                        <i data-lucide="bar-chart-3" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                        <p class="text-sm">Occupancy chart will be displayed here</p>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title class="text-text-light dark:text-text-dark">Recent Bookings</x-ui.card-title>
                <p class="text-xs text-text-light/70 dark:text-text-dark/70 mt-1">Latest reservation activity</p>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-primary-light/10 dark:border-background-dark/10">
                                <th class="text-left py-3 px-4 text-text-light/70 dark:text-text-dark/70 font-medium">GUEST NAME</th>
                                <th class="text-left py-3 px-4 text-text-light/70 dark:text-text-dark/70 font-medium">CHECK-IN</th>
                                <th class="text-left py-3 px-4 text-text-light/70 dark:text-text-dark/70 font-medium">ROOM TYPE</th>
                                <th class="text-left py-3 px-4 text-text-light/70 dark:text-text-dark/70 font-medium">STATUS</th>
                                <th class="text-right py-3 px-4 text-text-light/70 dark:text-text-dark/70 font-medium">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-primary-light/10 dark:border-background-dark/10">
                                <td class="py-3 px-4 text-text-light dark:text-text-dark">John Doe</td>
                                <td class="py-3 px-4 text-text-light/70 dark:text-text-dark/70">2024-12-15</td>
                                <td class="py-3 px-4 text-text-light/70 dark:text-text-dark/70">Deluxe</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-500">Confirmed</span>
                                </td>
                                <td class="py-3 px-4 text-right text-text-light dark:text-text-dark font-medium">$450.00</td>
                            </tr>
                            <tr class="border-b border-primary-light/10 dark:border-background-dark/10">
                                <td class="py-3 px-4 text-text-light dark:text-text-dark">Jane Smith</td>
                                <td class="py-3 px-4 text-text-light/70 dark:text-text-dark/70">2024-12-16</td>
                                <td class="py-3 px-4 text-text-light/70 dark:text-text-dark/70">Standard</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-500/20 text-yellow-500">Pending</span>
                                </td>
                                <td class="py-3 px-4 text-right text-text-light dark:text-text-dark font-medium">$180.00</td>
                            </tr>
                            <tr class="border-b border-primary-light/10 dark:border-background-dark/10">
                                <td class="py-3 px-4 text-text-light dark:text-text-dark">Michael Johnson</td>
                                <td class="py-3 px-4 text-text-light/70 dark:text-text-dark/70">2024-12-17</td>
                                <td class="py-3 px-4 text-text-light/70 dark:text-text-dark/70">Suite</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs bg-red-500/20 text-red-500">Cancelled</span>
                                </td>
                                <td class="py-3 px-4 text-right text-text-light dark:text-text-dark font-medium">$320.00</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-text-light dark:text-text-dark">Emily Davis</td>
                                <td class="py-3 px-4 text-text-light/70 dark:text-text-dark/70">2024-12-18</td>
                                <td class="py-3 px-4 text-text-light/70 dark:text-text-dark/70">Deluxe</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-500">Confirmed</span>
                                </td>
                                <td class="py-3 px-4 text-right text-text-light dark:text-text-dark font-medium">$275.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
@endsection
