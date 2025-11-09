@php
    $user = auth()->user();
    $userInitials = strtoupper(substr($user->name ?? 'U', 0, 2));
@endphp
<div class="sticky top-0 z-30 backdrop-blur supports-[backdrop-filter]:bg-background/70 border-b">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i data-lucide="concierge-bell" class="w-6 h-6"></i>
            <span class="font-semibold text-lg">{{ __('welcome') }}</span>
            <span class="hidden sm:inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-secondary text-secondary-foreground">Beta</span>
        </div>
        <div class="flex items-center gap-3">
            <!-- Dark Mode Toggle Switch -->
            <div class="relative flex flex-col items-center gap-1" x-data="{ 
                dark: localStorage.getItem('darkMode') === 'true',
                isAnimating: false,
                init() {
                    if (this.dark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },
                toggle() {
                    this.isAnimating = true;
                    this.dark = !this.dark;
                    localStorage.setItem('darkMode', this.dark);
                    if (this.dark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    setTimeout(() => { 
                        if (typeof lucide !== 'undefined') lucide.createIcons(); 
                        this.isAnimating = false;
                    }, 300);
                }
            }">
                <!-- Toggle Switch Container -->
                <button 
                    type="button" 
                    @click="toggle()"
                    class="relative w-20 h-10 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 overflow-hidden group"
                    :class="dark ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600'"
                    :title="dark ? 'Açık Tema' : 'Karanlık Tema'"
                >
                    <!-- Background Icons in Button -->
                    <div class="absolute inset-0 flex items-center justify-between px-2.5">
                        <!-- Sun Icon (Left - Light Mode) -->
                        <div 
                            class="transition-all duration-300"
                            :class="dark ? 'opacity-0 scale-0' : 'opacity-100 scale-100'"
                        >
                            <i data-lucide="sun" class="w-5 h-5 text-yellow-500"></i>
                        </div>
                        
                        <!-- Moon Icon (Right - Dark Mode) -->
                        <div 
                            class="transition-all duration-300"
                            :class="dark ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"
                        >
                            <i data-lucide="moon" class="w-5 h-5 text-blue-100"></i>
                        </div>
                    </div>
                    
                    <!-- Ripple Effect on Click -->
                    <span 
                        class="absolute inset-0 rounded-full bg-primary/30 scale-0 transition-all duration-300 opacity-0"
                        :class="isAnimating ? 'scale-150 opacity-100' : ''"
                    ></span>
                </button>
            </div>
            
            <!-- Bildirimler -->
            <a href="{{ route('admin.notifications') }}" class="p-2 hover:bg-accent rounded-md relative">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="absolute top-1 right-1 h-2 w-2 bg-destructive rounded-full"></span>
            </a>
            
            <!-- Kullanıcı Menüsü -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="h-9 w-9 rounded-full bg-secondary flex items-center justify-center hover:bg-accent transition-colors">
                    <span class="text-sm font-medium">{{ $userInitials }}</span>
                </button>
                <div 
                    x-show="open" 
                    @click.away="open = false" 
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-popover border border-border z-50"
                >
                    <div class="py-1">
                        <div class="px-4 py-2 text-sm border-b border-border">
                            <div class="font-medium">{{ $user->name ?? 'Kullanıcı' }}</div>
                            <div class="text-xs text-muted-foreground">{{ $user->email ?? '' }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-accent">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            {{ __('settings') }}
                        </a>
                        <hr class="my-1 border-border">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-destructive hover:bg-accent">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                {{ __('logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

