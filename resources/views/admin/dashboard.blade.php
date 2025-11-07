@extends('layouts.portal')

@section('title', 'Yönetim Paneli - Dashboard')

@push('scripts')
<script>
    // Veritabanından gelen veriler
    const monthlyReservations = @json($monthlyReservations ?? []);
    const monthlyRevenue = @json($monthlyRevenue ?? []);
    
    // Aylık rezervasyon verilerini hazırla
    const monthNames = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
    const reservationData = [];
    for (let i = 1; i <= 12; i++) {
        reservationData.push(monthlyReservations[i] || 0);
    }
    
    // Aylık gelir verilerini hazırla
    const revenueData = [];
    for (let i = 1; i <= 12; i++) {
        const monthData = monthlyRevenue.find(m => m.month == i);
        revenueData.push(monthData ? parseFloat(monthData.revenue) : 0);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Doluluk grafiği (rezervasyon verilerine göre)
        const occupancyCtx = document.getElementById('occupancyChart');
        if (occupancyCtx) {
            new Chart(occupancyCtx, {
                type: 'line',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Rezervasyon Sayısı',
                        data: reservationData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Gelir grafiği
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Gelir (₺)',
                        data: revenueData,
                        backgroundColor: 'rgba(34, 197, 94, 0.5)',
                        borderColor: 'rgb(34, 197, 94)',
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
    @include('admin.partials.sidebar', ['active' => 'dashboard'])
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 md:col-span-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-ui.card class="border-none shadow-sm">
                <x-ui.card-header class="pb-2 flex flex-row items-center justify-between">
                    <x-ui.card-title class="text-sm text-muted-foreground font-medium">Doluluk</x-ui.card-title>
                    <i data-lucide="hotel" class="w-4 h-4"></i>
                </x-ui.card-header>
                <x-ui.card-content>
                    @php
                        $totalRooms = $stats['total_rooms'] ?? 0;
                        $occupiedRooms = $stats['occupied_rooms'] ?? 0;
                        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
                    @endphp
                    <div class="text-2xl font-semibold tracking-tight">{{ $occupancyRate }}%</div>
                    <div class="text-xs text-muted-foreground mt-1">{{ $occupiedRooms }}/{{ $totalRooms }} oda</div>
                </x-ui.card-content>
            </x-ui.card>
            
            <x-ui.card class="border-none shadow-sm">
                <x-ui.card-header class="pb-2 flex flex-row items-center justify-between">
                    <x-ui.card-title class="text-sm text-muted-foreground font-medium">Bugünkü Gelir</x-ui.card-title>
                    <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="text-2xl font-semibold tracking-tight">₺{{ number_format($todayRevenue ?? 0, 2) }}</div>
                    <div class="text-xs text-muted-foreground mt-1">Bugün</div>
                </x-ui.card-content>
            </x-ui.card>
            
            <x-ui.card class="border-none shadow-sm">
                <x-ui.card-header class="pb-2 flex flex-row items-center justify-between">
                    <x-ui.card-title class="text-sm text-muted-foreground font-medium">{{ __('active_tasks') }}</x-ui.card-title>
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="text-2xl font-semibold tracking-tight">{{ $stats['pending_tasks'] ?? 0 }}</div>
                    <div class="text-xs text-muted-foreground mt-1">{{ __('total') }}: {{ $stats['total_tasks'] ?? 0 }}</div>
                </x-ui.card-content>
            </x-ui.card>
        </div>
        
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2 flex items-center justify-between">
                <x-ui.card-title>{{ __('monthly_reservations') }}</x-ui.card-title>
                <x-ui.badge>{{ __('live') }}</x-ui.badge>
            </x-ui.card-header>
            <x-ui.card-content class="h-64">
                <canvas id="occupancyChart"></canvas>
            </x-ui.card-content>
        </x-ui.card>
        
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>{{ __('recent_messages') }}</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4">
                @forelse($recentMessages ?? [] as $message)
                <div class="flex items-start gap-3">
                    <div class="h-8 w-8 rounded-full bg-secondary flex items-center justify-center">
                        <span class="text-xs font-medium">{{ strtoupper(substr($message->fromUser->name ?? 'U', 0, 1)) }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-medium">{{ $message->fromUser->name ?? 'Bilinmeyen' }}</div>
                        <div class="text-sm text-muted-foreground">{{ Str::limit($message->content, 60) }}</div>
                        <div class="text-xs text-muted-foreground mt-1">{{ $message->created_at->diffForHumans() }}</div>
                    </div>
                    @if(!isset($message->is_read) || !$message->is_read)
                        <span class="h-2 w-2 bg-primary rounded-full"></span>
                    @endif
                </div>
                @empty
                <div class="text-sm text-muted-foreground text-center py-4">{{ __('no_messages') }}</div>
                @endforelse
            </x-ui.card-content>
        </x-ui.card>
    </div>
    
    <div class="col-span-12 md:col-span-4 space-y-6">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2 flex items-center justify-between">
                <x-ui.card-title>{{ __('recent_reservations') }}</x-ui.card-title>
                <x-ui.badge variant="secondary">{{ count($recentReservations ?? []) }}</x-ui.badge>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="space-y-3">
                    @forelse($recentReservations ?? [] as $reservation)
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-accent">
                        <div>
                            <div class="text-sm font-medium">{{ $reservation->user->name ?? 'Bilinmeyen' }}</div>
                            <div class="text-xs text-muted-foreground">Oda {{ $reservation->room->room_number ?? 'N/A' }}</div>
                        </div>
                        <x-ui.badge variant="{{ $reservation->status === 'confirmed' ? 'default' : 'outline' }}">
                            {{ ucfirst($reservation->status) }}
                        </x-ui.badge>
                    </div>
                    @empty
                    <div class="text-sm text-muted-foreground text-center py-4">Henüz rezervasyon yok</div>
                    @endforelse
                </div>
            </x-ui.card-content>
        </x-ui.card>
        
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Gelir Trend</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="h-52">
                <canvas id="revenueChart"></canvas>
            </x-ui.card-content>
        </x-ui.card>
        
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>{{ __('recent_tasks') }}</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="space-y-3">
                    @forelse($recentTasks ?? [] as $task)
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-accent">
                        <div class="flex-1">
                            <div class="text-sm font-medium">{{ Str::limit($task->title, 30) }}</div>
                            <div class="text-xs text-muted-foreground">
                                {{ $task->assignedTo->name ?? 'Atanmamış' }}
                            </div>
                        </div>
                        <x-ui.badge variant="{{ $task->priority === 'urgent' ? 'destructive' : ($task->priority === 'high' ? 'default' : 'secondary') }}">
                            {{ ucfirst($task->priority) }}
                        </x-ui.badge>
                    </div>
                    @empty
                    <div class="text-sm text-muted-foreground text-center py-4">Henüz görev yok</div>
                    @endforelse
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
@endsection
