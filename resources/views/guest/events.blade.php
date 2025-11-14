@extends('layouts.portal')

@section('title', 'Etkinlikler - Misafir Paneli')

@section('content')
<div class="min-h-screen bg-white dark:bg-background-dark">
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

        @php
            $eventsData = ($events && $events->count() > 0) ? $events->map(function($event) {
                $images = $event->images->map(function($img) {
                    return asset($img->image_path);
                })->toArray();
                if (empty($images) && $event->image_path) {
                    $images = array(asset($event->image_path));
                }
                return array(
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'location' => $event->location,
                    'images' => $images,
                );
            }) : array();
        @endphp

        <!-- Events Grid -->
        @if($events && $events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            @foreach($events as $event)
            <div onclick="openEventModal({{ $event->id }})" class="group block cursor-pointer">
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-transparent group-hover:border-primary-light dark:group-hover:border-primary-dark transition-all duration-300 transform group-hover:-translate-y-1 overflow-hidden h-full flex flex-col">
                    <!-- Event Image -->
                    @php
                        $firstImage = $event->images->first();
                        $imagePath = $firstImage ? $firstImage->image_path : ($event->image_path ?? null);
                    @endphp
                    @if($imagePath)
                    <div class="w-full h-48 sm:h-56 bg-center bg-cover bg-no-repeat" style="background-image: url('{{ asset($imagePath) }}');">
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
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(isset($events) && $events->hasPages())
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

        <!-- Event Detail Modal -->
        <div id="event-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm" onclick="closeEventModal(event)">
            <div class="bg-white dark:bg-surface-dark rounded-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col" onclick="event.stopPropagation()">
                <div class="flex-1 overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="sticky top-0 bg-white dark:bg-surface-dark border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between z-10">
                        <h2 id="modal-title" class="text-2xl font-bold text-gray-900 dark:text-gray-100"></h2>
                        <button onclick="closeEventModal()" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center transition-colors">
                            <i data-lucide="x" class="w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6">
                        <!-- Image Gallery -->
                        <div id="modal-images" class="mb-6">
                            <div id="main-image-container" class="relative w-full rounded-xl overflow-hidden mb-4 bg-gray-100 dark:bg-gray-800 hidden flex items-center justify-center" style="min-height: 200px; max-height: 70vh;">
                                <img id="main-image-src" src="" alt="" class="max-w-full max-h-[70vh] w-auto h-auto object-contain">
                                
                                <!-- Navigation Buttons -->
                                <button id="prev-image-btn" onclick="prevImage(); event.stopPropagation();" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); z-index: 20;" class="w-12 h-12 rounded-full bg-black/60 hover:bg-black/80 text-white flex items-center justify-center transition-all shadow-xl">
                                    <span class="material-symbols-outlined text-2xl">chevron_left</span>
                                </button>
                                <button id="next-image-btn" onclick="nextImage(); event.stopPropagation();" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); z-index: 20;" class="w-12 h-12 rounded-full bg-black/60 hover:bg-black/80 text-white flex items-center justify-center transition-all shadow-xl">
                                    <span class="material-symbols-outlined text-2xl">chevron_right</span>
                                </button>
                                
                                <!-- Image Counter -->
                                <div id="image-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-black/50 text-white text-sm font-medium z-10"></div>
                            </div>
                        </div>

                        <!-- Event Info -->
                        <div class="space-y-4">
                            <div id="modal-dates" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400"></div>
                            <div id="modal-location" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400"></div>
                            <div id="modal-description" class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const eventsData = @json($eventsData);
            let currentEventImages = [];
            let currentImageIndex = 0;

            function openEventModal(eventId) {
                const event = eventsData.find(e => e.id === eventId);
                if (!event) return;

                document.getElementById('modal-title').textContent = event.title;
                
                // Dates
                const datesHtml = `
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>${new Date(event.start_date).toLocaleDateString('tr-TR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>
                    ${event.end_date ? `<span> - </span><span>${new Date(event.end_date).toLocaleDateString('tr-TR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>` : ''}
                `;
                document.getElementById('modal-dates').innerHTML = datesHtml;

                // Location
                if (event.location) {
                    document.getElementById('modal-location').innerHTML = `
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>${event.location}</span>
                    `;
                } else {
                    document.getElementById('modal-location').innerHTML = '';
                }

                // Description
                document.getElementById('modal-description').textContent = event.description || 'Açıklama yok';

                // Images
                currentEventImages = event.images && event.images.length > 0 ? event.images : [];
                currentImageIndex = 0;
                const mainImage = document.getElementById('main-image-src');
                const mainImageContainer = document.getElementById('main-image-container');
                
                if (currentEventImages.length > 0) {
                    mainImage.src = currentEventImages[0];
                    mainImage.alt = event.title;
                    mainImageContainer.classList.remove('hidden');
                    
                    // İlk fotoğraf yüklendiğinde boyutunu ayarla
                    mainImage.onload = function() {
                        adjustImageContainer();
                    };
                    
                    // Eğer fotoğraf cache'den yüklenirse
                    if (mainImage.complete) {
                        adjustImageContainer();
                    }
                    
                    updateImageCounter();
                    updateNavigationButtons();
                } else {
                    mainImageContainer.classList.add('hidden');
                }

                document.getElementById('event-modal').classList.remove('hidden');
                document.getElementById('event-modal').classList.add('flex');
                document.body.style.overflow = 'hidden';
                
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            function showImage(index) {
                if (currentEventImages.length === 0 || index < 0 || index >= currentEventImages.length) return;
                
                currentImageIndex = index;
                const mainImage = document.getElementById('main-image-src');
                const mainImageContainer = document.getElementById('main-image-container');
                
                // Fotoğraf yüklenene kadar container'ı göster
                mainImageContainer.classList.remove('hidden');
                
                // Yeni fotoğraf yükle
                mainImage.src = currentEventImages[index];
                mainImage.alt = `Event Image ${index + 1}`;
                
                // Fotoğraf yüklendiğinde boyutunu ayarla
                mainImage.onload = function() {
                    adjustImageContainer();
                };
                
                // Eğer fotoğraf cache'den yüklenirse
                if (mainImage.complete) {
                    adjustImageContainer();
                }
                
                updateImageCounter();
                updateNavigationButtons();
            }
            
            function adjustImageContainer() {
                const mainImage = document.getElementById('main-image-src');
                const mainImageContainer = document.getElementById('main-image-container');
                
                if (!mainImage || !mainImageContainer) return;
                
                // Fotoğrafın gerçek boyutlarını al
                const imgWidth = mainImage.naturalWidth;
                const imgHeight = mainImage.naturalHeight;
                
                if (imgWidth === 0 || imgHeight === 0) return;
                
                // Container'ın maksimum genişliği ve yüksekliği
                const maxWidth = window.innerWidth * 0.9; // Modal genişliğinin %90'ı
                const maxHeight = window.innerHeight * 0.7; // Ekran yüksekliğinin %70'i
                
                // Aspect ratio'yu koruyarak boyutları hesapla
                const aspectRatio = imgWidth / imgHeight;
                let displayWidth = imgWidth;
                let displayHeight = imgHeight;
                
                // Maksimum boyutlara göre ölçekle
                if (displayWidth > maxWidth) {
                    displayWidth = maxWidth;
                    displayHeight = displayWidth / aspectRatio;
                }
                
                if (displayHeight > maxHeight) {
                    displayHeight = maxHeight;
                    displayWidth = displayHeight * aspectRatio;
                }
                
                // Minimum boyutlar
                displayWidth = Math.max(displayWidth, 300);
                displayHeight = Math.max(displayHeight, 200);
                
                // Container'ı ayarla
                mainImageContainer.style.width = displayWidth + 'px';
                mainImageContainer.style.height = displayHeight + 'px';
            }

            function nextImage() {
                if (currentEventImages.length === 0) return;
                const nextIndex = (currentImageIndex + 1) % currentEventImages.length;
                showImage(nextIndex);
            }

            function prevImage() {
                if (currentEventImages.length === 0) return;
                const prevIndex = (currentImageIndex - 1 + currentEventImages.length) % currentEventImages.length;
                showImage(prevIndex);
            }

            function updateImageCounter() {
                const counter = document.getElementById('image-counter');
                if (currentEventImages.length > 1) {
                    counter.textContent = `${currentImageIndex + 1} / ${currentEventImages.length}`;
                    counter.classList.remove('hidden');
                } else {
                    counter.classList.add('hidden');
                }
            }

            function updateNavigationButtons() {
                const prevBtn = document.getElementById('prev-image-btn');
                const nextBtn = document.getElementById('next-image-btn');
                
                if (currentEventImages.length <= 1) {
                    prevBtn.classList.add('hidden');
                    nextBtn.classList.add('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                    nextBtn.classList.remove('hidden');
                }
            }

            function closeEventModal(e) {
                if (e && e.target !== e.currentTarget) return;
                document.getElementById('event-modal').classList.add('hidden');
                document.getElementById('event-modal').classList.remove('flex');
                document.body.style.overflow = '';
            }

            // Klavye kısayolları
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('event-modal');
                if (!modal || modal.classList.contains('hidden')) return;
                
                if (e.key === 'Escape') {
                    closeEventModal();
                } else if (e.key === 'ArrowLeft') {
                    prevImage();
                } else if (e.key === 'ArrowRight') {
                    nextImage();
                }
            });
        </script>
    </div>
</div>
@endsection



