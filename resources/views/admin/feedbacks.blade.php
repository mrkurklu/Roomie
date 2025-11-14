@extends('layouts.portal')

@section('title', 'Geri Bildirimler - Yönetim Paneli')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    function markAsResponded(feedbackId) {
        fetch(`/admin/feedbacks/${feedbackId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                is_responded: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function togglePublic(feedbackId, currentValue) {
        fetch(`/admin/feedbacks/${feedbackId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                is_public: !currentValue
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endpush

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'feedbacks'])
@endsection

@section('content')
<div class="w-full space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl sm:text-4xl font-bold text-text-light dark:text-text-dark">Geri Bildirimler</h1>
        <p class="text-sm sm:text-base text-text-light/70 dark:text-text-dark/70 mt-1">
            Misafirlerinizin geri bildirimlerini görüntüleyin ve yönetin
        </p>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-lg bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70">Toplam Geri Bildirim</p>
                        <p class="text-2xl font-bold text-text-light dark:text-text-dark mt-1">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center">
                        <i data-lucide="message-square-heart" class="w-6 h-6 text-primary-light dark:text-primary-dark"></i>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70">Ortalama Puan</p>
                        <p class="text-2xl font-bold text-text-light dark:text-text-dark mt-1">{{ number_format($stats['average_rating'] ?? 0, 1) }}/5</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                        <i data-lucide="star" class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-light/70 dark:text-text-dark/70">Yanıtlanmamış</p>
                        <p class="text-2xl font-bold text-text-light dark:text-text-dark mt-1">{{ $stats['unresponded'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/20 flex items-center justify-center">
                        <i data-lucide="alert-circle" class="w-6 h-6 text-orange-600 dark:text-orange-400"></i>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <!-- Feedback List -->
    <div class="space-y-4">
        @forelse($feedbacks ?? [] as $feedback)
        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="p-6">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Left: User Info & Rating -->
                    <div class="flex items-start gap-4 flex-1">
                        <div class="w-12 h-12 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center flex-shrink-0">
                            <span class="text-lg font-medium text-primary-light dark:text-primary-dark">
                                {{ strtoupper(substr($feedback->user->name ?? $feedback->guest_name ?? 'A', 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-lg font-semibold text-text-light dark:text-text-dark">
                                    {{ $feedback->user->name ?? $feedback->guest_name ?? 'Anonim' }}
                                </h3>
                                @if($feedback->is_responded)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                                    Yanıtlandı
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs rounded-full bg-orange-100 dark:bg-orange-900/20 text-orange-800 dark:text-orange-200">
                                    Beklemede
                                </span>
                                @endif
                            </div>
                            
                            @if($feedback->title)
                            <h4 class="text-base font-medium text-text-light dark:text-text-dark mb-2">
                                {{ $feedback->title }}
                            </h4>
                            @endif

                            <div class="flex items-center gap-1 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= ($feedback->rating ?? 0) ? 'fill-yellow-400 text-yellow-400' : 'text-text-light/30 dark:text-text-dark/30' }}"></i>
                                @endfor
                                <span class="text-sm text-text-light/70 dark:text-text-dark/70 ml-2">
                                    {{ $feedback->rating }}/5
                                </span>
                            </div>

                            @php
                                $categoryText = [
                                    'service' => 'Hizmet',
                                    'cleanliness' => 'Temizlik',
                                    'comfort' => 'Konfor',
                                    'value' => 'Değer',
                                    'other' => 'Diğer'
                                ];
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-primary-light/10 dark:bg-primary-dark/10 text-primary-light dark:text-primary-dark mb-2">
                                {{ $categoryText[$feedback->category] ?? 'Diğer' }}
                            </span>

                            @if($feedback->comment)
                            <p class="text-sm text-text-light/80 dark:text-text-dark/80 mt-2">
                                {{ $feedback->comment }}
                            </p>
                            @endif

                            <p class="text-xs text-text-light/60 dark:text-text-dark/60 mt-3">
                                {{ $feedback->created_at->format('d F Y, H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Right: Actions -->
                    <div class="flex flex-col gap-2 lg:flex-shrink-0">
                        @if(!$feedback->is_responded)
                        <button onclick="markAsResponded({{ $feedback->id }})" class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white text-sm font-medium transition-colors flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Yanıtlandı Olarak İşaretle
                        </button>
                        @endif

                        <button onclick="togglePublic({{ $feedback->id }}, {{ $feedback->is_public ? 'true' : 'false' }})" class="px-4 py-2 rounded-lg {{ $feedback->is_public ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-500 hover:bg-gray-600' }} text-white text-sm font-medium transition-colors flex items-center gap-2">
                            <i data-lucide="{{ $feedback->is_public ? 'eye' : 'eye-off' }}" class="w-4 h-4"></i>
                            {{ $feedback->is_public ? 'Herkese Açık' : 'Gizli' }}
                        </button>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>
        @empty
        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="p-12 text-center">
                <i data-lucide="message-square-x" class="w-16 h-16 mx-auto text-text-light/30 dark:text-text-dark/30 mb-4"></i>
                <p class="text-sm text-text-light/70 dark:text-text-dark/70">Henüz geri bildirim yok</p>
            </x-ui.card-content>
        </x-ui.card>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($feedbacks) && $feedbacks->hasPages())
    <div class="flex justify-center">
        {{ $feedbacks->links() }}
    </div>
    @endif
</div>
@endsection

