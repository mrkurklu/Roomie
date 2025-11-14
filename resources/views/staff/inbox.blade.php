@extends('layouts.portal')

@section('title', 'Mesajlar - Personel Paneli')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Tarayıcı dilini algıla ve form'a ekle
        const browserLanguage = navigator.language || navigator.userLanguage || 'tr';
        const langCode = browserLanguage.split('-')[0].toLowerCase();
        const supportedLanguages = ['tr', 'en', 'de', 'fr', 'es', 'it', 'ru', 'ar', 'zh', 'ja'];
        const finalLangCode = supportedLanguages.includes(langCode) ? langCode : 'tr';
        
        const browserLangInput = document.getElementById('browser_language');
        if (browserLangInput) {
            browserLangInput.value = finalLangCode;
        }

        // Chat container'ı scroll et
        const chatContainer = document.getElementById('chat-messages');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Form submit handler
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        
        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(chatForm);
                const content = messageInput.value.trim();
                
                if (!content) {
                    return;
                }

                // Disable form
                const submitBtn = chatForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>';

                fetch(chatForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        return response.json();
                    }
                })
                .then(data => {
                    if (data && data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.location.reload();
                });
            });
        }
    });
</script>
@endpush

@section('sidebar')
    @include('staff.partials.sidebar', ['active' => 'inbox'])
@endsection

@section('content')
@if($unreadCount > 0)
<div class="mb-4">
    <x-ui.card class="border-none shadow-sm bg-primary/10">
        <x-ui.card-content class="pt-6">
            <div class="flex items-center gap-2">
                <i data-lucide="mail" class="w-5 h-5 text-primary"></i>
                <div class="text-sm font-medium">{{ $unreadCount }} okunmamış mesajınız var</div>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>
@endif

<div class="flex flex-col h-[calc(100vh-200px)] min-h-[600px]">
    <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-sm bg-white dark:bg-surface-dark flex flex-col flex-1 overflow-hidden">
        <x-ui.card-header class="pb-3 flex items-center justify-between border-b border-primary-light/10 dark:border-primary-dark/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center">
                    <i data-lucide="message-circle" class="w-5 h-5 text-primary-light dark:text-primary-dark"></i>
                </div>
                <div>
                    <x-ui.card-title class="text-text-light dark:text-text-dark text-lg mb-0">
                        @if($chatUser)
                            {{ $chatUser->name ?? 'Kullanıcı' }} ile Sohbet
                        @else
                            Mesajlar
                        @endif
                    </x-ui.card-title>
                    <p class="text-xs text-text-light/60 dark:text-text-dark/60 mt-0.5">
                        @if($chatUser)
                            {{ $chatUser->email ?? '' }}
                        @else
                            Tüm mesajlarınız
                        @endif
                    </p>
                </div>
            </div>
            <button type="button" class="flex items-center justify-center gap-2 px-3 py-1.5 rounded-md bg-green-500 dark:bg-green-600 hover:opacity-90 transition-all duration-300 shadow-sm text-white font-medium text-sm">
                <i data-lucide="circle" class="w-2 h-2 fill-current"></i>
                Çevrimiçi
            </button>
        </x-ui.card-header>
        
        <x-ui.card-content class="flex-1 flex flex-col p-0 overflow-hidden">
            <!-- Messages Container -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-white dark:bg-gray-900/20">
                @if(!$chatUser && isset($chatUsers) && $chatUsers->count() > 0)
                    <!-- Kullanıcı Listesi -->
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Mesajlaşma geçmişi olan kullanıcılar:</p>
                        @foreach($chatUsers as $userItem)
                            <a href="{{ route('staff.inbox', ['to_user_id' => $userItem->id]) }}" class="flex items-center gap-3 p-3 rounded-lg bg-white dark:bg-surface-dark border border-primary-light/10 dark:border-primary-dark/10 hover:bg-primary-light/5 dark:hover:bg-primary-dark/10 transition-all">
                                <div class="w-10 h-10 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center">
                                    <span class="text-sm font-medium text-primary-light dark:text-primary-dark">{{ strtoupper(substr($userItem->name ?? 'U', 0, 2)) }}</span>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-800 dark:text-text-dark">{{ $userItem->name ?? 'Kullanıcı' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $userItem->email ?? '' }}</div>
                                </div>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                            </a>
                        @endforeach
                    </div>
                @else
                    @php
                        $prevDate = null;
                    @endphp
                    @forelse($messages ?? [] as $message)
                    @php
                        // sender_id veya from_user_id kullan (model'de map ediliyor)
                        $senderId = $message->sender_id ?? $message->from_user_id ?? ($message->attributes['sender_id'] ?? null);
                        $isFromMe = $senderId == auth()->id();
                        $displayName = $isFromMe ? 'Siz' : ($message->fromUser->name ?? 'Kullanıcı');
                        $displayContent = $message->display_content ?? $message->content;
                        $time = $message->created_at->format('H:i');
                        $date = $message->created_at->format('d.m.Y');
                        $currentDate = $message->created_at->format('Y-m-d');
                        $showDate = $prevDate === null || $currentDate !== $prevDate;
                        $prevDate = $currentDate;
                    @endphp
                    
                    @if($showDate)
                        <div class="flex justify-center my-4">
                            <span class="text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-3 py-1 rounded-full">{{ $date }}</span>
                        </div>
                    @endif
                    
                    @if($isFromMe)
                        <div class="flex justify-end items-end gap-2 group">
                            <div class="flex flex-col items-end max-w-[75%]">
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1 px-2">{{ $displayName }}</div>
                                <div class="rounded-2xl rounded-br-md px-4 py-2.5 text-sm bg-primary-light dark:bg-primary-dark text-white shadow-md hover:shadow-lg transition-shadow">
                                    <div class="whitespace-pre-wrap break-words">{{ $displayContent }}</div>
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1 px-2">{{ $time }}</div>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i data-lucide="user" class="w-4 h-4 text-primary-light dark:text-primary-dark"></i>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-start items-end gap-2 group">
                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-background-dark flex items-center justify-center flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i data-lucide="user" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <div class="flex flex-col items-start max-w-[75%]">
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1 px-2">{{ $displayName }}</div>
                                <div class="rounded-2xl rounded-bl-md px-4 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-md hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700">
                                    <div class="whitespace-pre-wrap break-words">{{ $displayContent }}</div>
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1 px-2">{{ $time }}</div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-background-dark flex items-center justify-center mb-4">
                            <i data-lucide="message-circle" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Henüz mesaj yok</p>
                        <p class="text-gray-500 dark:text-gray-500 text-xs">İlk mesajınızı göndererek sohbete başlayın</p>
                    </div>
                    @endforelse
                @endif
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mx-4 mt-2 p-3 rounded-md bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm animate-fade-in">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-4 mt-2 p-3 rounded-md bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-sm animate-fade-in">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Message Input Form -->
            @if($chatUser || (isset($chatUsers) && $chatUsers->count() > 0 && !$chatUser))
            <div class="border-t border-primary-light/10 dark:border-primary-dark/10 p-4 bg-white dark:bg-surface-dark">
                @if(!$chatUser)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Mesaj göndermek için yukarıdan bir kullanıcı seçin</p>
                @else
                <form method="POST" action="{{ route('staff.inbox.store') }}" id="chat-form" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="browser_language" id="browser_language" value="">
                    <input type="hidden" name="to_user_id" value="{{ $chatUser->id }}">
                    <div class="flex-1 relative">
                        <input 
                            type="text" 
                            name="content" 
                            id="message-input"
                            placeholder="Mesajınızı yazın..." 
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-background-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark transition-all"
                            required 
                            autocomplete="off"
                        />
                    </div>
                    <button 
                        type="submit" 
                        class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-primary-light dark:bg-primary-dark hover:opacity-90 transition-all duration-300 shadow-md hover:shadow-lg hover:scale-105 active:scale-100 text-white font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i data-lucide="send" class="w-5 h-5"></i>
                        <span class="hidden sm:inline">Gönder</span>
                    </button>
                </form>
                @endif
            </div>
            @else
            <div class="border-t border-primary-light/10 dark:border-primary-dark/10 p-4 bg-white dark:bg-surface-dark">
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Henüz mesajlaşma geçmişi yok. İlk mesajınızı göndermek için bir kullanıcı seçin.</p>
            </div>
            @endif
        </x-ui.card-content>
    </x-ui.card>
</div>

<style>
    #chat-messages {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }
    
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    #chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    
    #chat-messages::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }
    
    #chat-messages::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
</style>
@endsection

