@php
    $user = auth()->user();
    $userInitials = strtoupper(substr($user->name ?? 'U', 0, 2));
    
    // Role göre bildirimler route'unu belirle
    $notificationsRoute = 'admin.notifications';
    if ($user->hasRole('misafir') || $user->hasRole('guest')) {
        $notificationsRoute = 'guest.notifications';
    } elseif ($user->hasRole('personel') || $user->hasRole('staff')) {
        $notificationsRoute = 'admin.notifications'; // Staff için de admin.notifications kullanılabilir veya ayrı route oluşturulabilir
    }
@endphp
<header class="sticky top-0 z-50 w-full bg-primary-light dark:bg-primary-dark border-b border-primary-light/20 dark:border-primary-dark/20 shadow-lg">
    <div class="mx-auto max-w-7xl px-3 sm:px-4 lg:px-6 xl:px-8">
        <div class="flex h-14 sm:h-16 items-center justify-between">
            <!-- Logo & Brand -->
            <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <i data-lucide="concierge-bell" class="w-5 h-5 sm:w-6 sm:h-6 text-white flex-shrink-0"></i>
                    <div class="min-w-0">
                        <span class="font-semibold text-base sm:text-lg lg:text-xl text-white whitespace-nowrap">{{ __('welcome') }}</span>
                        <span class="hidden sm:inline-block ml-2 px-2 py-0.5 text-xs font-medium rounded bg-primary-dark text-text-dark">Beta</span>
                    </div>
                </div>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                <!-- Dark Mode Toggle -->
                <div x-data="{ 
                    dark: localStorage.getItem('darkMode') === 'true',
                    init() {
                        if (this.dark) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    },
                    toggle() {
                        this.dark = !this.dark;
                        localStorage.setItem('darkMode', this.dark);
                        if (this.dark) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                        setTimeout(() => { 
                            if (typeof lucide !== 'undefined') lucide.createIcons(); 
                        }, 100);
                    }
                }">
                    <button 
                        type="button" 
                        @click="toggle()"
                        class="h-8 w-8 sm:h-9 sm:w-9 rounded-md bg-white/10 dark:bg-white/10 hover:bg-white/20 dark:hover:bg-white/20 border border-white/20 dark:border-white/20 transition-all duration-200 flex items-center justify-center text-white focus:outline-none focus:ring-2 focus:ring-white/50"
                        :title="dark ? 'Açık Tema' : 'Karanlık Tema'"
                    >
                        <i data-lucide="sun" class="w-3.5 h-3.5 sm:w-4 sm:h-4" x-show="!dark" x-cloak></i>
                        <i data-lucide="moon" class="w-3.5 h-3.5 sm:w-4 sm:h-4" x-show="dark" x-cloak></i>
                    </button>
                </div>
                
                <!-- Notifications -->
                <a href="{{ route($notificationsRoute) }}" class="relative h-8 w-8 sm:h-9 sm:w-9 rounded-md bg-white/10 hover:bg-white/20 border border-white/20 transition-all duration-200 flex items-center justify-center text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                    <i data-lucide="bell" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                    <span class="absolute top-0.5 right-0.5 sm:top-1 sm:right-1 h-1.5 w-1.5 sm:h-2 sm:w-2 bg-primary-dark rounded-full border border-white"></span>
                </a>
                
                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button 
                        @click="open = !open" 
                        class="h-8 w-8 sm:h-9 sm:w-9 rounded-md bg-white/10 hover:bg-white/20 border border-white/20 transition-all duration-200 flex items-center justify-center text-white font-semibold text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-white/50"
                    >
                        {{ $userInitials }}
                    </button>
                    <div 
                        x-show="open" 
                        @click.away="open = false" 
                        x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                        class="absolute right-0 mt-2 w-56 sm:w-64 rounded-lg shadow-xl dark:shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 z-50"
                    >
                        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-slate-700 bg-primary-light/5 dark:bg-primary-dark/10">
                            <div class="font-semibold text-sm sm:text-base text-gray-900 dark:text-slate-100 truncate">{{ $user->name ?? 'Kullanıcı' }}</div>
                            <div class="text-xs text-gray-500 dark:text-slate-400 mt-0.5 truncate">{{ $user->email ?? '' }}</div>
                        </div>
                        <div class="py-1.5">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 sm:px-4 py-2 sm:py-2.5 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors duration-150">
                                <i data-lucide="settings" class="w-4 h-4 text-gray-500 dark:text-slate-400"></i>
                                <span>{{ __('settings') }}</span>
                            </a>
                            <hr class="my-1 border-gray-200 dark:border-slate-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm text-primary-dark hover:bg-primary-dark/20 transition-colors duration-150">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    <span>{{ __('logout') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

