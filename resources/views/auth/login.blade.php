<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giriş Yap - {{ config('app.name', 'Roomie') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen flex flex-col">
        <!-- Topbar -->
        <div class="sticky top-0 z-30 backdrop-blur supports-[backdrop-filter]:bg-background/70 border-b">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <i data-lucide="concierge-bell" class="w-6 h-6"></i>
                    <span class="font-semibold text-lg">Otel İletişim Portalı</span>
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-md text-sm font-medium hover:bg-accent transition-colors">
                        Ana Sayfa
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-md text-sm font-medium border border-input bg-background hover:bg-accent transition-colors">
                            Kayıt Ol
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-primary/5 via-background to-secondary/5">
            <div class="w-full max-w-md space-y-8">
                <div class="text-center space-y-2">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                            <i data-lucide="log-in" class="w-6 h-6 text-primary"></i>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight">Giriş Yap</h2>
                    <p class="text-muted-foreground">Hesabınıza erişim sağlayın</p>
                </div>

                <x-ui.card class="border-none shadow-lg">
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

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            <!-- Email Address -->
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium">E-posta Adresi</label>
                                <div class="relative">
                                    <i data-lucide="mail" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"></i>
                                    <x-ui.input 
                                        id="email" 
                                        type="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autofocus 
                                        autocomplete="username"
                                        class="pl-9"
                                        placeholder="ornek@email.com"
                                    />
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <label for="password" class="text-sm font-medium">Şifre</label>
                                <div class="relative">
                                    <i data-lucide="lock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"></i>
                                    <x-ui.input 
                                        id="password" 
                                        type="password" 
                                        name="password" 
                                        required 
                                        autocomplete="current-password"
                                        class="pl-9"
                                        placeholder="••••••••"
                                    />
                                </div>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <input 
                                        id="remember_me" 
                                        type="checkbox" 
                                        name="remember" 
                                        class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                    >
                                    <label for="remember_me" class="text-sm text-muted-foreground">Beni Hatırla</label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline">
                                        Şifrenizi mi unuttunuz?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <x-ui.button type="submit" class="w-full gap-2">
                                <i data-lucide="log-in" class="w-4 h-4"></i>
                                Giriş Yap
                            </x-ui.button>
                        </form>

                        <!-- Register Link -->
                        @if (Route::has('register'))
                            <div class="text-center pt-4 border-t">
                                <p class="text-sm text-muted-foreground">
                                    Hesabınız yok mu? 
                                    <a href="{{ route('register') }}" class="text-primary hover:underline font-medium">
                                        Kayıt Olun
                                    </a>
                                </p>
                            </div>
                        @endif
                    </x-ui.card-content>
                </x-ui.card>

                <!-- Additional Info -->
                <div class="text-center">
                    <p class="text-xs text-muted-foreground">
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
        });
    </script>
</body>
</html>
