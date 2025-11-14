<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': dark }" x-init="if (dark) $el.classList.add('dark')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giriş Yap - {{ config('app.name', 'Roomie') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-white dark:bg-background-dark text-text-light dark:text-text-dark transition-colors duration-300">
    <div class="min-h-screen flex flex-col">
        <!-- Topbar -->
        <div class="sticky top-0 z-30 backdrop-blur supports-[backdrop-filter]:bg-surface-light/70 dark:supports-[backdrop-filter]:bg-surface-dark/70 border-b border-primary-light/10 dark:border-primary-dark/10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3 text-text-light dark:text-text-dark hover:text-primary-light dark:hover:text-primary-dark transition-colors">
                    <i data-lucide="concierge-bell" class="w-6 h-6"></i>
                    <span class="font-semibold text-lg">Roomie</span>
                </a>
                <div class="flex items-center gap-3">
                    <button @click="dark = !dark; localStorage.setItem('darkMode', dark)" class="p-2 rounded-lg hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors">
                        <i data-lucide="moon" x-show="!dark" class="w-5 h-5 text-text-light dark:text-text-dark"></i>
                        <i data-lucide="sun" x-show="dark" class="w-5 h-5 text-text-light dark:text-text-dark"></i>
                    </button>
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-text-light dark:text-text-dark">
                        Ana Sayfa
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-md text-sm font-medium border border-primary-light/20 dark:border-primary-dark/20 bg-surface-light dark:bg-surface-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-colors text-text-light dark:text-text-dark">
                            Kayıt Ol
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-primary-light/5 dark:from-primary-dark/5 via-background-light dark:via-background-dark to-secondary-light/5 dark:to-secondary-dark/5">
            <div class="w-full max-w-md space-y-8">
                <div class="text-center space-y-2">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-primary-light/10 dark:bg-primary-dark/10 flex items-center justify-center">
                            <i data-lucide="log-in" class="w-6 h-6 text-primary-light dark:text-primary-dark"></i>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight text-text-light dark:text-text-dark">Giriş Yap</h2>
                    <p class="text-text-light/60 dark:text-text-dark/60">Hesabınıza erişim sağlayın</p>
                </div>

                <x-ui.card class="border border-primary-light/10 dark:border-primary-dark/10 shadow-lg bg-surface-light dark:bg-surface-dark">
                    <x-ui.card-content class="p-6 space-y-6">
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="p-3 rounded-md bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                                <p class="text-sm text-green-800 dark:text-green-200">{{ session('status') }}</p>
                            </div>
                        @endif

                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="p-3 rounded-md bg-destructive/10 border border-destructive/20">
                                <ul class="text-sm text-destructive space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-4">
                            @csrf

                            <!-- Browser Language (Hidden) -->
                            <input type="hidden" name="browser_language" id="browser_language" value="">

                            <!-- Email Address -->
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium text-text-light dark:text-text-dark">E-posta veya TC Kimlik No</label>
                                <div class="relative">
                                    <i data-lucide="mail" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-text-light/50 dark:text-text-dark/50"></i>
                                    <x-ui.input 
                                        id="email" 
                                        type="text" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autofocus 
                                        autocomplete="username"
                                        class="pl-9 bg-white dark:bg-background-dark border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark"
                                        placeholder="ornek@email.com veya TC Kimlik No"
                                    />
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <label for="password" class="text-sm font-medium text-text-light dark:text-text-dark">Şifre</label>
                                <div class="relative">
                                    <i data-lucide="lock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-text-light/50 dark:text-text-dark/50"></i>
                                    <x-ui.input 
                                        id="password" 
                                        type="password" 
                                        name="password" 
                                        required 
                                        autocomplete="current-password"
                                        class="pl-9 bg-white dark:bg-background-dark border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark"
                                        placeholder="Şifrenizi girin"
                                    />
                                </div>
                                <p class="text-xs text-text-light/60 dark:text-text-dark/60">
                                    <i data-lucide="info" class="w-3 h-3 inline-block mr-1"></i>
                                    Misafirler için şifre TC Kimlik Numaranızdır.
                                </p>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <input 
                                        id="remember_me" 
                                        type="checkbox" 
                                        name="remember" 
                                        class="h-4 w-4 rounded border-primary-light/20 dark:border-primary-dark/20 text-primary-light dark:text-primary-dark focus:ring-primary-light dark:focus:ring-primary-dark"
                                    >
                                    <label for="remember_me" class="text-sm text-text-light/60 dark:text-text-dark/60">Beni Hatırla</label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-primary-light dark:text-primary-dark hover:underline">
                                        Şifrenizi mi unuttunuz?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <x-ui.button type="submit" class="w-full gap-2 bg-primary-light dark:bg-primary-dark text-white hover:opacity-90">
                                <i data-lucide="log-in" class="w-4 h-4"></i>
                                Giriş Yap
                            </x-ui.button>
                        </form>

                        <!-- Register Link -->
                        @if (Route::has('register'))
                            <div class="text-center pt-4 border-t border-primary-light/10 dark:border-primary-dark/10">
                                <p class="text-sm text-text-light/60 dark:text-text-dark/60">
                                    Hesabınız yok mu? 
                                    <a href="{{ route('register') }}" class="text-primary-light dark:text-primary-dark hover:underline font-medium">
                                        Kayıt Olun
                                    </a>
                                </p>
                            </div>
                        @endif
                    </x-ui.card-content>
                </x-ui.card>

                <!-- Additional Info -->
                <div class="text-center">
                    <p class="text-xs text-text-light/50 dark:text-text-dark/50">
                        Giriş yaparak otel iletişim portalına erişebilirsiniz
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Dark mode initialization
            const darkMode = localStorage.getItem('darkMode') === 'true';
            if (darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
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
        });
    </script>
</body>
</html>
