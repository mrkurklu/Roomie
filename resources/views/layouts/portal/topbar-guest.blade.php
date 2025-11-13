@php
    $user = auth()->user();
    $userInitials = strtoupper(substr($user->name ?? 'U', 0, 2));
    $hotel = $user->hotel ?? null;
@endphp
<header class="flex items-center justify-between whitespace-nowrap border-b border-primary-light/10 dark:border-primary-dark/10 px-3 sm:px-4 md:px-6 lg:px-10 py-3 sm:py-4 bg-white dark:bg-background-dark font-display">
    <div class="flex items-center gap-2 sm:gap-3 md:gap-4 text-secondary-accent-light dark:text-secondary-accent-dark min-w-0 flex-1">
        <div class="text-primary-light dark:text-primary-dark flex-shrink-0">
            <span class="material-symbols-outlined text-2xl sm:text-3xl md:text-4xl">star</span>
        </div>
        <h2 class="text-base sm:text-lg md:text-xl font-bold leading-tight tracking-[-0.015em] font-display truncate">{{ $hotel->name ?? 'Roomie Otel' }}</h2>
    </div>
    <div class="flex items-center gap-2 sm:gap-3 md:gap-4 flex-shrink-0">
        <button class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 sm:h-9 md:h-10 bg-white dark:bg-surface-dark border border-primary-light/10 dark:border-primary-dark/10 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 gap-1 sm:gap-2 text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2 sm:px-2.5 transition-all">
            <span class="material-symbols-outlined text-lg sm:text-xl">language</span>
        </button>
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
                class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 sm:h-9 md:h-10 bg-white dark:bg-surface-dark border border-primary-light/10 dark:border-primary-dark/10 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 gap-1 sm:gap-2 text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2 sm:px-2.5 transition-all"
                :title="dark ? 'Açık Tema' : 'Karanlık Tema'"
            >
                <span class="material-symbols-outlined text-lg sm:text-xl">dark_mode</span>
            </button>
        </div>
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="open = !open" 
                class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-8 sm:size-9 md:size-10 border-2 border-primary-light dark:border-primary-dark hover:border-primary-light/80 dark:hover:border-primary-dark/80 transition-all cursor-pointer flex items-center justify-center bg-white dark:bg-surface-dark text-text-light dark:text-text-dark font-semibold text-xs sm:text-sm"
                style="background-image: url('{{ $user->avatar_url ?? '' }}');"
            >
                @if(!$user->avatar_url)
                    {{ $userInitials }}
                @endif
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
                class="absolute right-0 mt-2 w-56 sm:w-64 rounded-lg shadow-xl bg-white dark:bg-surface-dark border border-primary-light/10 dark:border-primary-dark/10 z-50"
            >
                <div class="p-3 sm:p-4 border-b border-primary-light/10 dark:border-primary-dark/10 bg-primary-light/5 dark:bg-primary-dark/10">
                    <div class="font-semibold text-sm sm:text-base text-text-light dark:text-text-dark truncate">{{ $user->name ?? 'Kullanıcı' }}</div>
                    <div class="text-xs text-text-light/70 dark:text-text-dark/70 mt-0.5 truncate">{{ $user->email ?? '' }}</div>
                </div>
                <div class="py-1.5">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 sm:px-4 py-2 sm:py-2.5 text-sm text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors duration-150">
                        <span class="material-symbols-outlined text-base">settings</span>
                        <span>Ayarlar</span>
                    </a>
                    <hr class="my-1 border-primary-light/10 dark:border-primary-dark/10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm text-primary-light dark:text-primary-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors duration-150">
                            <span class="material-symbols-outlined text-base">logout</span>
                            <span>Çıkış Yap</span>
                        </button>
                    </form>
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

