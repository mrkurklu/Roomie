@extends('layouts.portal')

@section('title', 'Taleplerim - Misafir Paneli')

@push('scripts')
<script>
    function openRequestModal() {
        document.getElementById('request-modal').classList.remove('hidden');
        document.getElementById('request-modal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeRequestModal(e) {
        if (e && e.target !== e.currentTarget) return;
        document.getElementById('request-modal').classList.add('hidden');
        document.getElementById('request-modal').classList.remove('flex');
        document.body.style.overflow = '';
        resetForm();
    }

    function selectCategory(category, button) {
        // Remove selection from all buttons
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('border-primary-light', 'dark:border-primary-dark', 'bg-primary-light/10', 'dark:bg-primary-dark/10');
            btn.classList.add('border-gray-200', 'dark:border-gray-700');
        });
        
        // Add selection to clicked button
        button.classList.remove('border-gray-200', 'dark:border-gray-700');
        button.classList.add('border-primary-light', 'dark:border-primary-dark', 'bg-primary-light/10', 'dark:bg-primary-dark/10');
        
        // Set category
        document.getElementById('selected-category').value = category;
        document.getElementById('category-error').classList.add('hidden');
        
        // Show request details
        document.getElementById('request-details').classList.remove('hidden');
    }

    function submitRequest() {
        const category = document.getElementById('selected-category').value;
        if (!category) {
            document.getElementById('category-error').classList.remove('hidden');
            return;
        }
        
        document.getElementById('request-form').submit();
    }

    function resetForm() {
        document.getElementById('selected-category').value = '';
        document.getElementById('request-details').classList.add('hidden');
        document.getElementById('request-form').reset();
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('border-primary-light', 'dark:border-primary-dark', 'bg-primary-light/10', 'dark:bg-primary-dark/10');
            btn.classList.add('border-gray-200', 'dark:border-gray-700');
        });
    }

    // ESC tuşu ile kapat
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRequestModal();
        }
    });

    function filterRequests(status) {
        // Update button styles
        document.querySelectorAll('.filter-btn').forEach(btn => {
            if (btn.dataset.filter === status) {
                btn.classList.remove('bg-white', 'dark:bg-surface-dark', 'border', 'border-primary-light/20', 'dark:border-primary-dark/20', 'text-text-light', 'dark:text-text-dark');
                btn.classList.add('bg-primary-light', 'dark:bg-primary-dark', 'text-white');
            } else {
                btn.classList.remove('bg-primary-light', 'dark:bg-primary-dark', 'text-white');
                btn.classList.add('bg-white', 'dark:bg-surface-dark', 'border', 'border-primary-light/20', 'dark:border-primary-dark/20', 'text-text-light', 'dark:text-text-dark');
            }
        });

        // Filter request cards
        const cards = document.querySelectorAll('.request-card');
        let visibleCount = 0;
        cards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            if (status === 'all' || cardStatus === status) {
                card.style.display = '';
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.style.display = 'none';
                card.classList.add('hidden');
            }
        });
        
        console.log('Filter:', status, 'Visible cards:', visibleCount, 'Total cards:', cards.length);
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Initialize filter to 'all'
        filterRequests('all');
    });
</script>
<style>
    .scrollbar-hide {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;  /* Chrome, Safari and Opera */
    }
</style>
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
            onclick="openRequestModal()"
            class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium"
        >
            <span class="material-symbols-outlined text-lg">add</span>
            Yeni Talep Oluştur
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2">
        <button onclick="filterRequests('all')" data-filter="all" class="filter-btn px-4 py-2 rounded-full bg-primary-light dark:bg-primary-dark text-white text-sm font-medium">
            <span class="material-symbols-outlined text-base align-middle mr-1">list</span>
            Tümü
        </button>
        <button onclick="filterRequests('in_progress')" data-filter="in_progress" class="filter-btn px-4 py-2 rounded-full bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-sm font-medium">
            <span class="material-symbols-outlined text-base align-middle mr-1">refresh</span>
            İşlemde
        </button>
        <button onclick="filterRequests('completed')" data-filter="completed" class="filter-btn px-4 py-2 rounded-full bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-sm font-medium">
            <span class="material-symbols-outlined text-base align-middle mr-1">check_circle</span>
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

        <!-- Request Modal -->
        <div id="request-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4" onclick="closeRequestModal(event)">
            <div class="bg-white dark:bg-surface-dark rounded-2xl max-w-2xl w-full flex flex-col shadow-2xl max-h-[calc(100vh-2rem)]" onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div class="flex-shrink-0 bg-white dark:bg-surface-dark border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Yeni Talep Oluştur</h2>
                    <button onclick="closeRequestModal()" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">close</span>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="flex-1 overflow-y-auto p-6 scrollbar-hide" style="max-height: calc(100vh - 200px);">
                    <form method="POST" action="{{ route('guest.requests.store') }}" id="request-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="type" id="selected-category" required>
                        
                        <!-- Category Selection -->
                        <div>
                            <label class="text-sm font-medium text-text-light dark:text-text-dark mb-3 block">Kategori Seçin *</label>
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Yiyecek İçecek -->
                                <button type="button" onclick="selectCategory('room_service', this)" class="category-btn p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark hover:border-primary-light dark:hover:border-primary-dark hover:bg-primary-light/5 dark:hover:bg-primary-dark/5 transition-all text-left group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-orange-100 dark:bg-orange-900/20 flex items-center justify-center group-hover:bg-orange-200 dark:group-hover:bg-orange-900/30 transition-colors">
                                            <span class="material-symbols-outlined text-2xl text-orange-600 dark:text-orange-400">restaurant</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-text-light dark:text-text-dark">Yiyecek İçecek</h3>
                                            <p class="text-xs text-text-light/60 dark:text-text-dark/60">Oda servisi, yemek, içecek</p>
                                        </div>
                                    </div>
                                </button>

                                <!-- Temizlik -->
                                <button type="button" onclick="selectCategory('housekeeping', this)" class="category-btn p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark hover:border-primary-light dark:hover:border-primary-dark hover:bg-primary-light/5 dark:hover:bg-primary-dark/5 transition-all text-left group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/30 transition-colors">
                                            <span class="material-symbols-outlined text-2xl text-blue-600 dark:text-blue-400">cleaning_services</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-text-light dark:text-text-dark">Temizlik</h3>
                                            <p class="text-xs text-text-light/60 dark:text-text-dark/60">Oda temizliği, havlu, yatak</p>
                                        </div>
                                    </div>
                                </button>

                                <!-- Bakım -->
                                <button type="button" onclick="selectCategory('maintenance', this)" class="category-btn p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark hover:border-primary-light dark:hover:border-primary-dark hover:bg-primary-light/5 dark:hover:bg-primary-dark/5 transition-all text-left group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center group-hover:bg-yellow-200 dark:group-hover:bg-yellow-900/30 transition-colors">
                                            <span class="material-symbols-outlined text-2xl text-yellow-600 dark:text-yellow-400">build</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-text-light dark:text-text-dark">Bakım</h3>
                                            <p class="text-xs text-text-light/60 dark:text-text-dark/60">Arıza, tamir, teknik sorun</p>
                                        </div>
                                    </div>
                                </button>

                                <!-- Eksik Şeyler -->
                                <button type="button" onclick="selectCategory('concierge', this)" class="category-btn p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark hover:border-primary-light dark:hover:border-primary-dark hover:bg-primary-light/5 dark:hover:bg-primary-dark/5 transition-all text-left group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center group-hover:bg-purple-200 dark:group-hover:bg-purple-900/30 transition-colors">
                                            <span class="material-symbols-outlined text-2xl text-purple-600 dark:text-purple-400">shopping_bag</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-text-light dark:text-text-dark">Eksik Şeyler</h3>
                                            <p class="text-xs text-text-light/60 dark:text-text-dark/60">Eksik eşya, malzeme</p>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            <p id="category-error" class="text-xs text-red-600 dark:text-red-400 mt-2 hidden">Lütfen bir kategori seçin</p>
                        </div>

                        <!-- Request Details -->
                        <div id="request-details" class="hidden space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-text-light dark:text-text-dark">Başlık *</label>
                                <input type="text" name="title" placeholder="Örn: Odaya su getirilmesi" required class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark px-4 py-2 text-sm text-text-light dark:text-text-dark placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-text-light dark:text-text-dark">Açıklama *</label>
                                <textarea name="description" rows="4" placeholder="Lütfen talebinizi detaylı olarak açıklayın..." required class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark px-4 py-2 text-sm text-text-light dark:text-text-dark placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark resize-none"></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-text-light dark:text-text-dark">Öncelik</label>
                                <select name="priority" class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark px-4 py-2 text-sm text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                                    <option value="low">Düşük</option>
                                    <option value="medium" selected>Orta</option>
                                    <option value="high">Yüksek</option>
                                    <option value="urgent">Acil</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="flex-shrink-0 bg-white dark:bg-surface-dark border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button type="button" onclick="closeRequestModal()" class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-sm font-medium">
                        İptal
                    </button>
                    <button type="button" onclick="submitRequest()" class="px-4 py-2 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">send</span>
                        Gönder
                    </button>
                </div>
            </div>
        </div>

        <!-- Request Cards List -->
        @forelse($requests ?? [] as $request)
        <div data-status="{{ $request->status }}" class="request-card">
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
        </div>
        @empty
        <div class="text-center py-12">
            <i data-lucide="inbox" class="w-16 h-16 mx-auto text-text-light/30 dark:text-text-dark/30 mb-4"></i>
            <p class="text-sm text-text-light/70 dark:text-text-dark/70">Henüz talep yok</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
