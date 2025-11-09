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
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-8">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Analitik Veriler</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="h-72 flex items-center justify-center text-muted-foreground">
                Analitik grafikler yakında eklenecektir.
            </x-ui.card-content>
        </x-ui.card>
    </div>
    <div class="col-span-12 lg:col-span-4 space-y-4">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Oda Doluluk</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="h-52">
                <canvas id="occupancyChart"></canvas>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Görev Durumu</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="h-52">
                <canvas id="taskChart"></canvas>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
@endsection
