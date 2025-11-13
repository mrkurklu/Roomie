@extends('layouts.portal')

@section('title', 'Geri Bildirim - Misafir Paneli')

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
    @include('guest.partials.sidebar', ['active' => 'feedback'])
@endsection

@section('content')
<div class="w-full space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl sm:text-4xl font-bold text-text-light dark:text-text-dark">Hizmetlerimiz Hakkında Geri Bildirimde Bulunun</h1>
        <p class="text-sm sm:text-base text-text-light/70 dark:text-text-dark/70 mt-1">
            Otelimizdeki deneyiminizle ilgili düşüncelerinizi duymaktan mutluluk duyarız.
        </p>
    </div>

    <!-- Feedback Form Card -->
    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
        <x-ui.card-header class="pb-3">
            <x-ui.card-title class="text-text-light dark:text-text-dark">Deneyiminizi Paylaşın</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-6">
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
            @if($errors->any())
                <div class="p-3 rounded-md bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('guest.feedback.store') }}" class="space-y-6">
                @csrf
                <!-- Rating Section -->
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-text-light dark:text-text-dark">Genel Deneyim</label>
                        <div class="flex gap-2" x-data="{ selected: 0 }">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden" x-model="selected">
                                <button type="button" class="w-10 h-10 rounded-full border-2 transition-all duration-200 hover:scale-110 flex items-center justify-center {{ $i <= 4 ? 'bg-primary-light dark:bg-primary-dark border-primary-light dark:border-primary-dark text-white' : 'bg-transparent border-primary-light/50 dark:border-primary-dark/50 text-primary-light dark:text-primary-dark' }}">
                                    <i data-lucide="star" class="w-5 h-5 {{ $i <= 4 ? 'fill-current' : '' }}"></i>
                                </button>
                            </label>
                            @endfor
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-text-light dark:text-text-dark">Temizlik</label>
                        <div class="flex gap-2" x-data="{ selected: 0 }">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="cleanliness_rating" value="{{ $i }}" class="hidden" x-model="selected">
                                <button type="button" class="w-10 h-10 rounded-full border-2 transition-all duration-200 hover:scale-110 flex items-center justify-center {{ $i <= 4 ? 'bg-primary-light dark:bg-primary-dark border-primary-light dark:border-primary-dark text-white' : 'bg-transparent border-primary-light/50 dark:border-primary-dark/50 text-primary-light dark:text-primary-dark' }}">
                                    <i data-lucide="star" class="w-5 h-5 {{ $i <= 4 ? 'fill-current' : '' }}"></i>
                                </button>
                            </label>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-text-light dark:text-text-dark">Başlık</label>
                    <input type="text" name="title" placeholder="Geri bildiriminizi özetleyin" class="w-full rounded-lg border border-primary-light/20 dark:border-primary-dark/20 bg-white dark:bg-background-dark px-4 py-3 text-sm text-text-light dark:text-text-dark placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-text-light dark:text-text-dark">Yorumunuz</label>
                    <textarea name="comment" rows="6" placeholder="Deneyiminiz hakkında daha fazla bilgi verin..." class="w-full rounded-lg border border-primary-light/20 dark:border-primary-dark/20 bg-white dark:bg-background-dark px-4 py-3 text-sm text-text-light dark:text-text-dark placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark resize-none"></textarea>
                </div>

                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 transition-opacity text-sm font-medium">
                    Gönder
                </button>
            </form>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Previous Feedback Section -->
    <div>
        <h2 class="text-2xl sm:text-3xl font-bold text-text-light dark:text-text-dark mb-2">Önceki Geri Bildirimler</h2>
        <p class="text-sm sm:text-base text-text-light/70 dark:text-text-dark/70 mb-6">
            Diğer misafirlerimizin deneyimleri hakkında ne söylediğini görün.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($feedbacks ?? [] as $feedback)
            <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark">
                <x-ui.card-content class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center">
                            <span class="text-sm font-medium text-primary-light dark:text-primary-dark">{{ strtoupper(substr($feedback->user->name ?? 'A', 0, 1)) }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-text-light dark:text-text-dark">{{ $feedback->user->name ?? 'Anonim' }}</div>
                            <div class="text-xs text-text-light/60 dark:text-text-dark/60">{{ $feedback->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" class="w-4 h-4 {{ $i <= ($feedback->rating ?? 0) ? 'fill-primary-light dark:fill-primary-dark text-primary-light dark:text-primary-dark' : 'text-text-light/30 dark:text-text-dark/30' }}"></i>
                        @endfor
                    </div>
                    @if($feedback->title)
                    <h3 class="text-base font-semibold text-text-light dark:text-text-dark mb-2">{{ $feedback->title }}</h3>
                    @endif
                    @if($feedback->comment)
                    <p class="text-sm text-text-light/80 dark:text-text-dark/80 line-clamp-3">{{ $feedback->comment }}</p>
                    @endif
                </x-ui.card-content>
            </x-ui.card>
            @empty
            <div class="col-span-full text-center py-12">
                <i data-lucide="message-square-x" class="w-12 h-12 mx-auto text-text-light/30 dark:text-text-dark/30 mb-4"></i>
                <p class="text-sm text-text-light/70 dark:text-text-dark/70">Henüz geri bildirim yok</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
