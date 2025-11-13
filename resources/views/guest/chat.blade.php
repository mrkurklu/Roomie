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
                submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span>';

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
                        // Sayfayı yenile
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Sayfayı yenile
                    window.location.reload();
                });
            });
        }

        // Auto-scroll when new messages arrive
        const observer = new MutationObserver(function() {
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        });
        
        if (chatContainer) {
            observer.observe(chatContainer, { childList: true, subtree: true });
        }
    });
</script>
@endpush

@section('sidebar')
    @include('guest.partials.sidebar', ['active' => 'chat'])
@endsection

@section('content')
<div class="relative flex h-[calc(100vh-120px)] sm:h-[calc(100vh-100px)] min-h-[600px] w-full flex-col items-center justify-center p-3 sm:p-4 md:p-6 lg:p-8 bg-background-light dark:bg-background-dark font-display">
    <!-- Chat Window Container -->
    <div class="flex h-full w-full max-w-4xl flex-col overflow-hidden rounded-lg bg-white shadow-xl dark:bg-surface-dark">
        <!-- Header Bar -->
        <div class="flex items-center justify-between border-b border-gray-200/50 p-4 dark:border-gray-700/50">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                @php
                    $hotel = auth()->user()->hotel;
                    $hotelLogo = $hotel->logo ?? null;
                @endphp
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 h-10 sm:w-12 sm:h-12 shrink-0 bg-primary-dark/20 flex items-center justify-center">
                    @if($hotelLogo)
                        <img src="{{ asset($hotelLogo) }}" alt="Hotel logo" class="w-full h-full rounded-full object-cover">
                    @else
                        <span class="material-symbols-outlined text-primary-dark text-xl sm:text-2xl">hotel</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-base sm:text-lg font-bold text-gray-800 dark:text-text-dark truncate">Resepsiyon</h1>
                    <div class="flex items-center gap-1.5">
                        <div class="h-2 w-2 rounded-full bg-green-500"></div>
                        <p class="text-xs sm:text-sm font-normal leading-normal text-gray-500 dark:text-gray-400">Çevrimiçi</p>
                    </div>
                </div>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <button class="p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-surface-dark/50 transition-colors">
                    <span class="material-symbols-outlined text-xl">more_vert</span>
                </button>
            </div>
        </div>

        <!-- Message Area -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 md:p-6">
            <div class="flex flex-col gap-4 min-h-full">
                @php
                    $prevDate = null;
                @endphp
                @forelse($messages ?? [] as $message)
                    @php
                        $senderId = $message->attributes['sender_id'] ?? $message->from_user_id;
                        $isFromMe = $senderId == auth()->id();
                        $displayName = $isFromMe ? 'Siz' : ($message->fromUser->name ?? 'Resepsiyon');
                        $displayContent = $message->display_content ?? $message->content;
                        $time = $message->created_at->format('H:i');
                        $date = $message->created_at->format('d.m.Y');
                        $currentDate = $message->created_at->format('Y-m-d');
                        $showDate = $prevDate === null || $currentDate !== $prevDate;
                        $prevDate = $currentDate;
                    @endphp
                    
                    @if($showDate)
                        <div class="flex justify-center">
                            <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-normal leading-normal rounded-full bg-gray-200 px-3 py-1 dark:bg-background-dark">
                                @php
                                    $today = \Carbon\Carbon::today()->format('Y-m-d');
                                    $yesterday = \Carbon\Carbon::yesterday()->format('Y-m-d');
                                @endphp
                                @if($currentDate === $today)
                                    Bugün
                                @elseif($currentDate === $yesterday)
                                    Dün
                                @else
                                    {{ $date }}
                                @endif
                            </p>
                        </div>
                    @endif
                    
                    @if($isFromMe)
                        <!-- Guest Message (Right) -->
                        <div class="flex items-end gap-3 self-end max-w-[80%] md:max-w-[60%]">
                            <div class="flex flex-1 flex-col gap-1 items-end">
                                <div class="relative">
                                    <p class="text-base font-normal leading-normal flex rounded-t-lg rounded-bl-lg px-4 py-3 bg-primary-light text-white dark:bg-primary-dark dark:text-secondary-accent-dark whitespace-pre-wrap break-words">
                                        {{ $displayContent }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 absolute -bottom-5 left-0 whitespace-nowrap">{{ $time }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Reception Message (Left) -->
                        <div class="flex items-end gap-3 self-start max-w-[80%] md:max-w-[60%]">
                            @php
                                $receptionAvatar = $message->fromUser->avatar_url ?? null;
                            @endphp
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-8 h-8 shrink-0 flex items-center justify-center overflow-hidden" style="background-image: url('{{ $receptionAvatar ? asset($receptionAvatar) : '' }}'); background-color: {{ $receptionAvatar ? 'transparent' : 'rgba(0, 146, 202, 0.2)' }};">
                                @if(!$receptionAvatar)
                                    <span class="material-symbols-outlined text-primary-dark text-sm">support_agent</span>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col gap-1 items-start">
                                <div class="flex w-full items-baseline justify-between">
                                    <p class="text-secondary-accent-light dark:text-secondary-accent-dark text-[13px] font-medium leading-normal">{{ $displayName }}</p>
                                </div>
                                <div class="relative">
                                    <p class="text-base font-normal leading-normal flex rounded-t-lg rounded-br-lg bg-surface-light px-4 py-3 text-secondary-accent-light dark:bg-background-dark dark:text-secondary-accent-dark whitespace-pre-wrap break-words">
                                        {{ $displayContent }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 absolute -bottom-5 right-0 whitespace-nowrap">{{ $time }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-background-dark flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-3xl text-gray-400 dark:text-gray-500">chat_bubble_outline</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Henüz mesaj yok</p>
                        <p class="text-gray-500 dark:text-gray-500 text-xs">İlk mesajınızı göndererek sohbete başlayın</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mx-4 mt-2 p-3 rounded-md bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-xs sm:text-sm animate-fade-in">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 mt-2 p-3 rounded-md bg-primary-dark/20 text-primary-dark text-xs sm:text-sm animate-fade-in">
                {{ session('error') }}
            </div>
        @endif

        <!-- Message Input Area -->
        <div class="mt-auto border-t border-gray-200/50 p-3 sm:p-4 dark:border-gray-700/50">
            <form method="POST" action="{{ route('guest.chat.store') }}" id="chat-form">
                @csrf
                <input type="hidden" name="browser_language" id="browser_language" value="">
                <div class="flex items-end gap-2 rounded-lg bg-gray-100 p-2 dark:bg-background-dark">
                    <button type="button" class="p-1.5 sm:p-2 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white rounded-lg hover:bg-gray-200 dark:hover:bg-surface-dark/50 transition-colors flex-shrink-0">
                        <span class="material-symbols-outlined text-lg sm:text-xl">add_circle</span>
                    </button>
                    <textarea 
                        name="content" 
                        id="message-input"
                        rows="1"
                        class="flex-1 resize-none border-none bg-transparent p-2 text-gray-800 placeholder-gray-500 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 text-sm sm:text-base"
                        placeholder="Mesajınızı yazın..."
                        required
                        autocomplete="off"
                        oninput="this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 120) + 'px';"
                    ></textarea>
                    <button 
                        type="submit" 
                        class="flex h-9 w-9 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-full bg-primary-light text-white dark:bg-primary-dark dark:text-secondary-accent-dark hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg"
                    >
                        <span class="material-symbols-outlined text-lg sm:text-xl">send</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #chat-messages {
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
    }
    
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    #chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    
    #chat-messages::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }
    
    .dark #chat-messages::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
    }
    
    #chat-messages::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }
    
    .dark #chat-messages::-webkit-scrollbar-thumb:hover {
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

    /* Textarea auto-resize */
    #message-input {
        max-height: 120px;
        overflow-y: auto;
    }
</style>
@endsection
