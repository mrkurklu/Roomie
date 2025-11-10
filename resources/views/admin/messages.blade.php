@extends('layouts.portal')

@section('title', 'Mesajlar - Yönetim Paneli')

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
    @include('admin.partials.sidebar', ['active' => 'messages'])
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">{{ __('total_messages') }}</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-blue-600">{{ $stats['unread'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">{{ __('unread_messages') }}</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['internal'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">{{ __('internal_message') }}</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['guest'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">{{ __('guest_message') }}</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-4">
        <x-ui.card class="border-none shadow-sm h-full">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Mesajlar</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-2 max-h-[600px] overflow-y-auto">
                @forelse($messages ?? [] as $message)
                <div class="p-3 rounded-lg hover:bg-accent cursor-pointer {{ (!isset($message->is_read) || !$message->is_read) ? 'bg-accent/50' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-medium">{{ strtoupper(substr($message->fromUser->name ?? 'U', 0, 2)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium truncate">{{ $message->fromUser->name ?? 'Bilinmeyen' }}</div>
                                @if(!isset($message->is_read) || !$message->is_read)
                                <span class="h-2 w-2 bg-primary rounded-full flex-shrink-0"></span>
                                @endif
                            </div>
                            <div class="text-xs text-muted-foreground truncate mt-1">{{ Str::limit($message->subject ?? $message->content, 40) }}</div>
                            <div class="text-xs text-muted-foreground mt-1">{{ $message->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-sm text-muted-foreground text-center py-8">{{ __('no_messages') }}</div>
                @endforelse
            </x-ui.card-content>
        </x-ui.card>
    </div>
    <div class="col-span-12 lg:col-span-8 space-y-4">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2 flex items-center justify-between">
                <x-ui.card-title>{{ __('message_detail') }}</x-ui.card-title>
                <x-ui.badge>{{ __('active') }}</x-ui.badge>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4 max-h-[420px] overflow-auto">
                @if(isset($messages) && $messages->count() > 0)
                    @php $selectedMessage = $messages->first(); @endphp
                    <div class="flex justify-start">
                        <div class="max-w-[75%] rounded-2xl p-3 text-sm bg-muted">
                            <div class="font-medium text-xs opacity-70 mb-1">{{ $selectedMessage->fromUser->name ?? 'Bilinmeyen' }}</div>
                            {{ $selectedMessage->content }}
                        </div>
                    </div>
                    @if($selectedMessage->toUser)
                    <div class="flex justify-end">
                        <div class="max-w-[75%] rounded-2xl p-3 text-sm bg-primary text-primary-foreground">
                            <div class="font-medium text-xs opacity-70 mb-1">{{ $selectedMessage->toUser->name ?? 'Bilinmeyen' }}</div>
                            Yanıt mesajı burada görünecek
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-sm text-muted-foreground text-center py-8">{{ __('select_message') }}</div>
                @endif
            </x-ui.card-content>
        </x-ui.card>
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
        <form method="POST" action="{{ route('admin.messages.store') }}" id="messages-form" class="flex gap-2">
            @csrf
            <!-- Browser Language (Hidden) -->
            <input type="hidden" name="browser_language" id="browser_language" value="">
            <input type="hidden" name="to_user_id" value="{{ isset($messages) && $messages->count() > 0 ? $messages->first()->from_user_id : '' }}" />
            <x-ui.input name="content" placeholder="{{ __('write_reply') }}" class="flex-1" required />
            <x-ui.button type="submit" class="gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                {{ __('send') }}
            </x-ui.button>
        </form>
    </div>
</div>

@if(isset($messages) && $messages->count() > 15)
<div class="mt-6">
    {{ $messages->links() }}
</div>
@endif
@endsection
