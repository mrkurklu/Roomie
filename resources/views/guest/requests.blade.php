@extends('layouts.portal')

@section('title', 'Taleplerim - Misafir Paneli')

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
    @include('guest.partials.sidebar', ['active' => 'requests'])
@endsection

@section('content')
<div class="w-full space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-text-light dark:text-text-dark">Taleplerim</h1>
            <p class="text-sm sm:text-base text-text-light/70 dark:text-text-dark/70 mt-1">
                Konaklamanız sırasında yeni talepler oluşturun ve mevcut olanları takip edin.
            </p>
        </div>
        <button 
            type="button"
            x-data="{ open: false }"
            @click="open = !open"
            class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            Yeni Talep Oluştur
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2">
        <button class="px-4 py-2 rounded-full bg-primary-light dark:bg-primary-dark text-white text-sm font-medium">
            <i data-lucide="list" class="w-4 h-4 inline mr-2"></i>
            Tümü
        </button>
        <button class="px-4 py-2 rounded-full bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-sm font-medium">
            <i data-lucide="clock" class="w-4 h-4 inline mr-2"></i>
            Beklemede
        </button>
        <button class="px-4 py-2 rounded-full bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-sm font-medium">
            <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
            İşlemde
        </button>
        <button class="px-4 py-2 rounded-full bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-sm font-medium">
            <i data-lucide="check" class="w-4 h-4 inline mr-2"></i>
            Tamamlandı
        </button>
    </div>

    <!-- Request Cards -->
    <div class="space-y-4">
        @if(session('success'))
            <div class="p-3 rounded-md bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-3 rounded-md bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- New Request Form (Hidden by default) -->
        <div 
            x-data="{ open: false }"
            x-show="open"
            x-cloak
            x-transition
            class="p-4 rounded-lg border border-primary-light/20 dark:border-primary-dark/20 bg-white dark:bg-surface-dark"
        >
            <form method="POST" action="{{ route('guest.requests.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-text-light dark:text-text-dark">Talep Türü</label>
                        <select name="type" class="w-full rounded-lg border border-primary-light/20 dark:border-primary-dark/20 bg-white dark:bg-background-dark px-3 py-2 text-sm text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark" required>
                            <option value="room_service">Oda Servisi</option>
                            <option value="housekeeping">Temizlik</option>
                            <option value="maintenance">Bakım</option>
                            <option value="concierge">Konsiyerj</option>
                            <option value="other">Diğer</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-text-light dark:text-text-dark">Öncelik</label>
                        <select name="priority" class="w-full rounded-lg border border-primary-light/20 dark:border-primary-dark/20 bg-white dark:bg-background-dark px-3 py-2 text-sm text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                            <option value="low">Düşük</option>
                            <option value="medium" selected>Orta</option>
                            <option value="high">Yüksek</option>
                            <option value="urgent">Acil</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-text-light dark:text-text-dark">Başlık</label>
                    <input type="text" name="title" placeholder="Talep başlığı" required class="w-full rounded-lg border border-primary-light/20 dark:border-primary-dark/20 bg-white dark:bg-background-dark px-3 py-2 text-sm text-text-light dark:text-text-dark placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-text-light dark:text-text-dark">Açıklama</label>
                    <textarea name="description" rows="4" placeholder="Talep açıklaması" required class="w-full rounded-lg border border-primary-light/20 dark:border-primary-dark/20 bg-white dark:bg-background-dark px-3 py-2 text-sm text-text-light dark:text-text-dark placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="open = false" class="px-4 py-2 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-sm font-medium">
                        İptal
                    </button>
                    <button type="submit" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Gönder
                    </button>
                </div>
            </form>
        </div>

        <!-- Request Cards List -->
        @forelse($requests ?? [] as $request)
        <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
            <x-ui.card-content class="p-0">
                <div class="flex flex-col sm:flex-row gap-4 p-6">
                    <!-- Request Image -->
                    <div class="w-full sm:w-32 h-32 rounded-lg overflow-hidden flex-shrink-0 bg-white dark:bg-background-dark border border-primary-light/10 dark:border-primary-dark/10">
                        @php
                            $imageUrl = null;
                            if($request->category === 'housekeeping' || stripos($request->title, 'havlu') !== false) {
                                $imageUrl = 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=400';
                            } elseif($request->category === 'room_service' || stripos($request->title, 'oda servisi') !== false) {
                                $imageUrl = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400';
                            }
                        @endphp
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $request->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="file-text" class="w-12 h-12 text-text-light/30 dark:text-text-dark/30"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Request Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-text-light/60 dark:text-text-dark/60 mb-1">
                                    {{ ucfirst(str_replace('_', ' ', $request->category ?? 'Oda Hizmetleri')) }}
                                </p>
                                <h3 class="text-lg font-bold text-text-light dark:text-text-dark mb-2">{{ $request->title }}</h3>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $request->status === 'completed' ? 'bg-green-500/20 text-green-500' : ($request->status === 'in_progress' ? 'bg-blue-500/20 text-blue-500' : 'bg-yellow-500/20 text-yellow-500') }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status ?? 'Beklemede')) }}
                                </span>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs text-text-light dark:text-text-dark">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                                <span>Talep Alındı</span>
                                <span class="text-text-light/60 dark:text-text-dark/60 ml-auto">{{ $request->created_at->format('d F Y, H:i') }}</span>
                            </div>
                            @if($request->status === 'in_progress' || $request->status === 'completed')
                            <div class="flex items-center gap-2 text-xs text-text-light dark:text-text-dark">
                                <i data-lucide="user" class="w-4 h-4 text-blue-500"></i>
                                <span>Personel Atandı</span>
                                <span class="text-text-light/60 dark:text-text-dark/60 ml-auto">{{ $request->updated_at->format('d F Y, H:i') }}</span>
                            </div>
                            @endif
                            @if($request->status === 'completed')
                            <div class="flex items-center gap-2 text-xs text-text-light dark:text-text-dark">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                                <span>Tamamlandı</span>
                                <span class="text-text-light/60 dark:text-text-dark/60 ml-auto">{{ $request->updated_at->format('d F Y, H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>
        @empty
        <div class="text-center py-12">
            <i data-lucide="inbox" class="w-16 h-16 mx-auto text-text-light/30 dark:text-text-dark/30 mb-4"></i>
            <p class="text-sm text-text-light/70 dark:text-text-dark/70">Henüz talep yok</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
