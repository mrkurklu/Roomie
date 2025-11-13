<!-- Mobile Menu Button (Hamburger) - Only visible on mobile -->
<div x-data="{ menuOpen: false }" style="position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999; display: block;" class="md:hidden">
    <!-- Hamburger Menu Button -->
    <button 
        @click="menuOpen = !menuOpen"
        type="button"
        style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; background-color: #0092ca; color: #eeeeee; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); display: flex; align-items: center; justify-content: center; transition: all 0.3s; border: none; cursor: pointer; padding: 0; position: relative;"
        onmouseover="this.style.opacity='0.9'"
        onmouseout="this.style.opacity='1'"
        onmousedown="this.style.transform='scale(0.95)'"
        onmouseup="this.style.transform='scale(1)'"
    >
        <!-- Hamburger Icon (SVG) -->
        <svg 
            width="24" 
            height="24" 
            viewBox="0 0 24 24" 
            fill="none" 
            stroke="currentColor" 
            stroke-width="2.5" 
            stroke-linecap="round" 
            stroke-linejoin="round"
            style="width: 24px; height: 24px; color: #eeeeee; display: block;"
            :style="menuOpen ? 'transform: rotate(45deg);' : 'transform: rotate(0deg);'"
            style="transition: transform 0.3s;"
        >
            <line x1="4" y1="6" x2="20" y2="6" :style="menuOpen ? 'opacity: 0;' : 'opacity: 1;'" style="transition: opacity 0.3s;"></line>
            <line x1="4" y1="12" x2="20" y2="12" :style="menuOpen ? 'opacity: 0;' : 'opacity: 1;'" style="transition: opacity 0.3s;"></line>
            <line x1="4" y1="18" x2="20" y2="18" :style="menuOpen ? 'opacity: 0;' : 'opacity: 1;'" style="transition: opacity 0.3s;"></line>
            <line x1="4" y1="6" x2="20" y2="18" :style="menuOpen ? 'opacity: 1;' : 'opacity: 0;'" style="transition: opacity 0.3s; transform-origin: center;"></line>
            <line x1="20" y1="6" x2="4" y2="18" :style="menuOpen ? 'opacity: 1;' : 'opacity: 0;'" style="transition: opacity 0.3s; transform-origin: center;"></line>
        </svg>
    </button>
    
    <!-- Backdrop -->
    <div 
        x-show="menuOpen"
        @click="menuOpen = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); z-index: 9998;"
        x-cloak
    ></div>
    
    <!-- Menu Panel (Slides up from bottom with bounce effect) -->
    <div 
        x-show="menuOpen"
        @click.away="menuOpen = false"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="translate-y-full opacity-0 scale-95"
        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
        x-transition:leave-end="translate-y-full opacity-0 scale-95"
        style="position: fixed; bottom: 0; left: 0; right: 0; background-color: hsl(var(--background) / 0.95); backdrop-filter: blur(12px); border-top: 1px solid hsl(var(--border)); z-index: 9999; border-radius: 1.5rem 1.5rem 0 0; box-shadow: 0 -20px 25px -5px rgba(0, 0, 0, 0.1), 0 -8px 10px -6px rgba(0, 0, 0, 0.1); max-height: 70vh; overflow-y: auto; transform-origin: bottom center;"
        x-cloak
    >
        <div class="px-4 pt-4 pb-6" style="padding-bottom: 2rem;" x-data="{ itemsVisible: false }" x-init="setTimeout(() => itemsVisible = true, 100)">
            <!-- Drag Handle -->
            <div 
                style="height: 0.25rem; width: 3rem; background-color: hsl(var(--muted)); border-radius: 9999px; margin: 0 auto 1rem auto;"
                x-show="itemsVisible"
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-50"
                x-transition:enter-end="opacity-100 scale-100"
            ></div>
            
            <!-- Menu Items -->
            <div 
                class="grid grid-cols-5 gap-2"
                x-show="itemsVisible"
                x-transition:enter="transition ease-out duration-300 delay-200"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                @if(($role ?? 'Yönetim') === 'Yönetim')
                    <a href="{{ route('admin.dashboard') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-accent hover:bg-[hsl(var(--button-hover))] transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'dashboard') === 'dashboard' ? 'bg-primary text-primary-foreground' : '' }}" style="animation: fadeInUp 0.3s ease-out 0.1s both;">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Özet</span>
                    </a>
                    <a href="{{ route('admin.tasks') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-accent hover:bg-[hsl(var(--button-hover))] transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'dashboard') === 'tasks' ? 'bg-primary text-primary-foreground' : '' }}" style="animation: fadeInUp 0.3s ease-out 0.15s both;">
                        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Görev</span>
                    </a>
                    <a href="{{ route('admin.messages') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-accent hover:bg-[hsl(var(--button-hover))] transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'dashboard') === 'messages' ? 'bg-primary text-primary-foreground' : '' }}" style="animation: fadeInUp 0.3s ease-out 0.2s both;">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Mesaj</span>
                    </a>
                    <a href="{{ route('admin.events') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-accent hover:bg-[hsl(var(--button-hover))] transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'dashboard') === 'events' ? 'bg-destructive text-destructive-foreground' : '' }}" style="animation: fadeInUp 0.3s ease-out 0.25s both;">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Etkinlik</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-accent hover:bg-[hsl(var(--button-hover))] transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'dashboard') === 'analytics' ? 'bg-primary text-primary-foreground' : '' }}" style="animation: fadeInUp 0.3s ease-out 0.3s both;">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Analiz</span>
                    </a>
                @elseif(($role ?? 'Yönetim') === 'Personel')
                    <a href="{{ route('staff.tasks') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-accent hover:bg-[hsl(var(--button-hover))] transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'mytasks') === 'mytasks' ? 'bg-primary text-primary-foreground' : '' }}" style="animation: fadeInUp 0.3s ease-out 0.1s both;">
                        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Görev</span>
                    </a>
                    <a href="{{ route('staff.schedule') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-accent hover:bg-[hsl(var(--button-hover))] transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'mytasks') === 'schedule' ? 'bg-primary text-primary-foreground' : '' }}" style="animation: fadeInUp 0.3s ease-out 0.15s both;">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Vardiya</span>
                    </a>
                    <a href="{{ route('staff.tickets') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-accent hover:bg-[hsl(var(--button-hover))] transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'mytasks') === 'tickets' ? 'bg-primary text-primary-foreground' : '' }}" style="animation: fadeInUp 0.3s ease-out 0.2s both;">
                        <i data-lucide="wrench" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Arıza</span>
                    </a>
                    <a href="{{ route('staff.inbox') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-accent hover:bg-[hsl(var(--button-hover))] transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'mytasks') === 'inbox' ? 'bg-primary text-primary-foreground' : '' }}" style="animation: fadeInUp 0.3s ease-out 0.25s both;">
                        <i data-lucide="inbox" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Mesaj</span>
                    </a>
                    <a href="{{ route('staff.resources') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-accent hover:bg-[hsl(var(--button-hover))] transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'mytasks') === 'resources' ? 'bg-primary text-primary-foreground' : '' }}" style="animation: fadeInUp 0.3s ease-out 0.3s both;">
                        <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Kaynak</span>
                    </a>
                @else
                    <a href="{{ route('guest.welcome') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-dark hover:bg-surface-dark/80 transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'welcome') === 'welcome' ? 'bg-primary-dark text-text-dark' : 'text-text-dark' }}" style="animation: fadeInUp 0.3s ease-out 0.1s both;">
                        <i data-lucide="hotel" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Ana</span>
                    </a>
                    <a href="{{ route('guest.chat') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-dark hover:bg-surface-dark/80 transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'welcome') === 'chat' ? 'bg-primary-dark text-text-dark' : 'text-text-dark' }}" style="animation: fadeInUp 0.3s ease-out 0.15s both;">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Sohbet</span>
                    </a>
                    <a href="{{ route('guest.requests') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-dark hover:bg-surface-dark/80 transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'welcome') === 'requests' ? 'bg-primary-dark text-text-dark' : 'text-text-dark' }}" style="animation: fadeInUp 0.3s ease-out 0.2s both;">
                        <i data-lucide="concierge-bell" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Talep</span>
                    </a>
                    <a href="{{ route('guest.services') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-dark hover:bg-surface-dark/80 transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'welcome') === 'services' ? 'bg-primary-dark text-text-dark' : 'text-text-dark' }}" style="animation: fadeInUp 0.3s ease-out 0.25s both;">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Hizmetlerimiz</span>
                    </a>
                    <a href="{{ route('guest.feedback') }}" @click="menuOpen = false" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-dark hover:bg-surface-dark/80 transition-all duration-200 hover:scale-105 {{ ($activeTab ?? 'welcome') === 'feedback' ? 'bg-primary-dark text-text-dark' : 'text-text-dark' }}" style="animation: fadeInUp 0.3s ease-out 0.3s both;">
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <span class="text-[11px] font-medium text-center">Puan</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lucide icons initialization
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Icon'ları yeniden oluştur (Alpine.js state değişikliklerinden sonra)
        setTimeout(() => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }, 500);
        
        // Alpine.js state değişikliklerini dinle
        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => {
                // Menü açıldığında/kapandığında icon'ları yeniden oluştur
                setTimeout(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }, 100);
            });
        });
    });
</script>

<style>
    /* Mobile menu button - ensure visibility */
    @media (max-width: 767px) {
        .mobile-menu-button {
            display: block !important;
        }
    }
    
    @media (min-width: 768px) {
        .mobile-menu-button {
            display: none !important;
        }
    }
    
    /* Menu opening animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideUpBounce {
        0% {
            transform: translateY(100%) scale(0.95);
            opacity: 0;
        }
        60% {
            transform: translateY(-5%) scale(1.02);
            opacity: 0.9;
        }
        100% {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }
    
    /* Smooth menu item animations */
    .menu-item {
        animation-fill-mode: both;
    }
</style>
