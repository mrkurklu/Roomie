@extends('layouts.portal')

@section('title', 'Canlı Sohbet - Misafir Paneli')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Tarayıcı dilini algıla ve form'a ekle
        const browserLanguage = navigator.language || navigator.userLanguage || 'tr';
        // Örnek: "de-DE" -> "de", "en-US" -> "en"
        const langCode = browserLanguage.split('-')[0].toLowerCase();
        
        // Desteklenen dilleri kontrol et (tr, en, de, fr, es, it, ru, ar, zh, ja)
        const supportedLanguages = ['tr', 'en', 'de', 'fr', 'es', 'it', 'ru', 'ar', 'zh', 'ja'];
        const finalLangCode = supportedLanguages.includes(langCode) ? langCode : 'tr';
        
        const browserLangInput = document.getElementById('browser_language');
        if (browserLangInput) {
            browserLangInput.value = finalLangCode;
        }
    });
</script>
@endpush

@section('sidebar')
    @include('guest.partials.sidebar', ['active' => 'chat'])
@endsection

@section('content')
<x-ui.card class="border-2 border-white/20 shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title class="text-white">Resepsiyon ile Sohbet</x-ui.card-title>
        <button type="button" class="flex items-center justify-center gap-2 px-3 py-1.5 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 text-first-color dark:text-blue-400 font-medium text-sm">
            <i data-lucide="circle" class="w-2 h-2 fill-current"></i>
            Çevrimiçi
        </button>
    </x-ui.card-header>
    <x-ui.card-content class="space-y-4">
        <div class="max-h-80 overflow-auto space-y-3">
            @forelse($messages ?? [] as $message)
            @if($message->from_user_id === auth()->id())
            <div class="flex justify-end">
                <div class="max-w-[75%] rounded-2xl p-3 text-sm third-color text-first-color dark:text-blue-400">
                    <div class="font-medium text-xs opacity-70 mb-1">{{ $message->fromUser->name ?? 'Siz' }}</div>
                    {{ $message->display_content ?? $message->content }}
                    <div class="text-xs opacity-70 mt-1">{{ $message->created_at->format('H:i') }}</div>
                </div>
            </div>
            @else
            <div class="flex justify-start">
                <div class="max-w-[75%] rounded-2xl p-3 text-sm bg-white/20 text-white">
                    <div class="font-medium text-xs opacity-70 mb-1">{{ $message->fromUser->name ?? 'Resepsiyon' }}</div>
                    {{ $message->display_content ?? $message->content }}
                    <div class="text-xs opacity-70 mt-1">{{ $message->created_at->format('H:i') }}</div>
                </div>
            </div>
            @endif
            @empty
            <div class="text-sm text-white/70 text-center py-8">Henüz mesaj yok. İlk mesajınızı gönderin!</div>
            @endforelse
        </div>
        @if(session('success'))
            <div class="p-3 rounded-md bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-3 rounded-md bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-sm mb-4">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('guest.chat.store') }}" id="chat-form" class="flex gap-2">
            @csrf
            <!-- Browser Language (Hidden) -->
            <input type="hidden" name="browser_language" id="browser_language" value="">
            <x-ui.input name="content" placeholder="Mesajınızı yazın..." class="flex-1 text-first-color dark:text-blue-400 placeholder:text-first-color/60 dark:placeholder:text-blue-400/60" required />
            <button type="submit" class="flex items-center justify-center gap-2 px-4 py-2 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 text-first-color dark:text-blue-400 font-medium">
                <i data-lucide="send" class="w-4 h-4"></i>
                Gönder
            </button>
        </form>
    </x-ui.card-content>
</x-ui.card>

@if(isset($messages) && $messages->count() > 20)
<div class="mt-6">
    {{ $messages->links() }}
</div>
@endif
@endsection
