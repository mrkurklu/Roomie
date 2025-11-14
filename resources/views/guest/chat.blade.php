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
<div class="relative flex h-[calc(100vh-120px)] sm:h-[calc(100vh-100px)] min-h-[600px] w-full flex-col items-center justify-center p-3 sm:p-4 md:p-6 lg:p-8 bg-white dark:bg-background-dark font-display">
    <!-- Chat Window Container -->
    <div class="flex h-full w-full max-w-4xl flex-col overflow-hidden rounded-lg bg-white shadow-xl dark:bg-surface-dark">
        <!-- Header Bar -->
        <div class="flex items-center justify-between border-b border-gray-200/50 p-4 dark:border-gray-700/50">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                @php
                    $hotel = auth()->user()->hotel;
                    $hotelLogo = $hotel->logo ?? null;
                    $activeStay = \App\Models\GuestStay::where('user_id', auth()->id())
                        ->where('status', 'checked_in')
                        ->with('room.assignedStaff')
                        ->first();
                    $assignedStaff = $activeStay && $activeStay->room ? $activeStay->room->assignedStaff : null;
                @endphp
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 h-10 sm:w-12 sm:h-12 shrink-0 bg-primary-dark/20 flex items-center justify-center">
                    @if($assignedStaff && $assignedStaff->avatar_url)
                        <img src="{{ asset($assignedStaff->avatar_url) }}" alt="Personel" class="w-full h-full rounded-full object-cover">
                    @elseif($hotelLogo)
                        <img src="{{ asset($hotelLogo) }}" alt="Hotel logo" class="w-full h-full rounded-full object-cover">
                    @else
                        <span class="material-symbols-outlined text-primary-dark text-xl sm:text-2xl">hotel</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-base sm:text-lg font-bold text-gray-800 dark:text-text-dark truncate">
                        @if($assignedStaff)
                            {{ $assignedStaff->name }}
                        @else
                            Resepsiyon
                        @endif
                    </h1>
                    <div class="flex items-center gap-1.5">
                        @if($assignedStaff)
                            <div class="h-2 w-2 rounded-full bg-green-500"></div>
                            <p class="text-xs sm:text-sm font-normal leading-normal text-gray-500 dark:text-gray-400">Odanıza Atanmış Personel</p>
                        @else
                            <div class="h-2 w-2 rounded-full bg-yellow-500"></div>
                            <p class="text-xs sm:text-sm font-normal leading-normal text-gray-500 dark:text-gray-400">Odanıza personel atanmamış</p>
                        @endif
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
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50/50 dark:bg-gray-900/20">
            <div class="flex flex-col gap-1 min-h-full">
                @php
                    $prevDate = null;
                    $prevSenderId = null;
                    $messagesArray = $messages ?? [];
                @endphp
                @forelse($messagesArray as $index => $message)
                    @php
                        // sender_id veya from_user_id kullan (model'de map ediliyor)
                        $senderId = $message->sender_id ?? $message->from_user_id ?? ($message->attributes['sender_id'] ?? null);
                        $isFromMe = $senderId == auth()->id();
                        $displayName = $isFromMe ? 'Siz' : ($message->fromUser->name ?? 'Resepsiyon');
                        $displayContent = $message->display_content ?? $message->content;
                        $time = $message->created_at->format('H:i');
                        $date = $message->created_at->format('d.m.Y');
                        $currentDate = $message->created_at->format('Y-m-d');
                        $showDate = $prevDate === null || $currentDate !== $prevDate;
                        $prevDate = $currentDate;
                        
                        // Mesaj gruplaması: Aynı kişiden ardışık mesajlar
                        $isGrouped = $prevSenderId !== null && $prevSenderId === $senderId;
                        
                        // Sonraki mesajı kontrol et
                        $nextMessage = $messagesArray[$index + 1] ?? null;
                        $nextSenderId = $nextMessage ? ($nextMessage->sender_id ?? $nextMessage->from_user_id ?? ($nextMessage->attributes['sender_id'] ?? null)) : null;
                        $isLastInGroup = $nextSenderId !== $senderId;
                        
                        // Önceki sender ID'yi güncelle
                        $prevSenderId = $senderId;
                    @endphp
                    
                    @if($showDate)
                        <div class="flex justify-center my-4">
                            <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium rounded-full bg-white dark:bg-gray-800 px-4 py-1.5 shadow-sm border border-gray-200 dark:border-gray-700">
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
                        <!-- Guest Message (Right) - WhatsApp/Telegram Style -->
                        <div class="flex items-end gap-2 self-end max-w-[75%] md:max-w-[65%] mb-1 group message-item" style="animation: slideInRight 0.3s ease-out;">
                            <div class="flex flex-col items-end gap-0.5">
                                <div class="relative group/message">
                                    <div class="rounded-2xl rounded-br-md px-4 py-2.5 bg-primary-light text-white dark:bg-primary-dark shadow-sm hover:shadow-md transition-shadow">
                                        <p class="text-[15px] leading-relaxed whitespace-pre-wrap break-words">{{ $displayContent }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 mt-1 px-1 opacity-0 group-hover/message:opacity-100 transition-opacity">
                                        <span class="text-[11px] text-gray-400 dark:text-gray-500">{{ $time }}</span>
                                        @if($isLastInGroup)
                                            <svg class="w-3.5 h-3.5 text-primary-light dark:text-primary-dark" fill="currentColor" viewBox="0 0 16 15">
                                                <path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.175a.366.366 0 0 0-.063-.51zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.175a.365.365 0 0 0-.063-.51z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Reception Message (Left) - WhatsApp/Telegram Style -->
                        <div class="flex items-end gap-2 self-start max-w-[75%] md:max-w-[65%] mb-1 group message-item" style="animation: slideInLeft 0.3s ease-out;">
                            @php
                                $receptionAvatar = $message->fromUser->avatar_url ?? null;
                            @endphp
                            @if($isLastInGroup || !$isGrouped)
                                <div class="w-8 h-8 rounded-full bg-primary-light/20 dark:bg-primary-dark/20 flex items-center justify-center overflow-hidden flex-shrink-0 mb-0.5">
                                    @if($receptionAvatar)
                                        <img src="{{ asset($receptionAvatar) }}" alt="{{ $displayName }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined text-primary-dark text-sm">support_agent</span>
                                    @endif
                                </div>
                            @else
                                <div class="w-8 flex-shrink-0"></div>
                            @endif
                            <div class="flex flex-col items-start gap-0.5 flex-1">
                                @if(!$isGrouped)
                                    <p class="text-[12px] text-gray-500 dark:text-gray-400 font-medium px-1 mb-0.5">{{ $displayName }}</p>
                                @endif
                                <div class="relative group/message">
                                    <div class="rounded-2xl rounded-bl-md px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm hover:shadow-md transition-shadow border border-gray-100 dark:border-gray-700">
                                        <p class="text-[15px] leading-relaxed whitespace-pre-wrap break-words">{{ $displayContent }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 mt-1 px-1 opacity-0 group-hover/message:opacity-100 transition-opacity">
                                        <span class="text-[11px] text-gray-400 dark:text-gray-500">{{ $time }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-background-dark flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-3xl text-gray-400 dark:text-gray-500">chat_bubble_outline</span>
                        </div>
                        @php
                            $activeStay = \App\Models\GuestStay::where('user_id', auth()->id())
                                ->where('status', 'checked_in')
                                ->with('room.assignedStaff')
                                ->first();
                            $assignedStaff = $activeStay && $activeStay->room ? $activeStay->room->assignedStaff : null;
                        @endphp
                        @if($assignedStaff)
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Henüz mesaj yok</p>
                            <p class="text-gray-500 dark:text-gray-500 text-xs">İlk mesajınızı göndererek {{ $assignedStaff->name }} ile sohbete başlayın</p>
                        @else
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Odanıza personel atanmamış</p>
                            <p class="text-gray-500 dark:text-gray-500 text-xs">Mesaj göndermek için önce odanıza personel atanması gerekmektedir</p>
                        @endif
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
        <div class="mt-auto border-t border-gray-200/50 p-3 sm:p-4 dark:border-gray-700/50 bg-white dark:bg-surface-dark">
            <form method="POST" action="{{ route('guest.chat.store') }}" id="chat-form">
                @csrf
                <input type="hidden" name="browser_language" id="browser_language" value="">
                <div class="flex items-end gap-2 rounded-2xl bg-gray-100 dark:bg-gray-800 p-2 border border-gray-200 dark:border-gray-700 focus-within:ring-2 focus-within:ring-primary-light/50 dark:focus-within:ring-primary-dark/50 transition-all">
                    <button type="button" class="p-2 text-gray-500 hover:text-primary-light dark:hover:text-primary-dark rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors flex-shrink-0">
                        <span class="material-symbols-outlined text-xl">add_circle</span>
                    </button>
                    <textarea 
                        name="content" 
                        id="message-input"
                        rows="1"
                        class="flex-1 resize-none border-none bg-transparent p-2.5 text-gray-800 placeholder-gray-400 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-500 text-[15px] leading-relaxed"
                        placeholder="Mesajınızı yazın..."
                        required
                        autocomplete="off"
                        oninput="this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 120) + 'px';"
                    ></textarea>
                    <button 
                        type="submit" 
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-light text-white dark:bg-primary-dark hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg hover:scale-105 active:scale-95"
                    >
                        <span class="material-symbols-outlined text-xl">send</span>
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
        background-image: 
            repeating-linear-gradient(
                0deg,
                transparent,
                transparent 20px,
                rgba(0, 0, 0, 0.02) 20px,
                rgba(0, 0, 0, 0.02) 21px
            );
    }
    
    .dark #chat-messages {
        background-image: 
            repeating-linear-gradient(
                0deg,
                transparent,
                transparent 20px,
                rgba(255, 255, 255, 0.02) 20px,
                rgba(255, 255, 255, 0.02) 21px
            );
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
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
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
    
    .message-item {
        animation-fill-mode: both;
    }
    
    /* Textarea auto-resize */
    #message-input {
        max-height: 120px;
        overflow-y: auto;
    }
    
    /* Message bubble hover effects */
    .group\/message:hover .shadow-sm {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .dark .group\/message:hover .shadow-sm {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection
