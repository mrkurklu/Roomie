<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kayıt Ol - {{ config('app.name', 'Roomie') }}</title>
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
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-sm font-medium border border-input bg-background hover:bg-accent transition-colors">
                        Giriş Yap
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-primary/5 via-background to-secondary/5">
            <div class="w-full max-w-md space-y-8">
                <div class="text-center space-y-2">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                            <i data-lucide="user-plus" class="w-6 h-6 text-primary"></i>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight">Kayıt Ol</h2>
                    <p class="text-muted-foreground">Yeni bir hesap oluşturun</p>
                </div>

                <x-ui.card class="border-none shadow-lg">
                    <x-ui.card-content class="p-6 space-y-6">
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

                        <form method="POST" action="{{ route('register') }}" class="space-y-4">
                            @csrf

                            <!-- Name -->
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-medium">Ad Soyad</label>
                                <div class="relative">
                                    <i data-lucide="user" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"></i>
                                    <x-ui.input 
                                        id="name" 
                                        type="text" 
                                        name="name" 
                                        value="{{ old('name') }}" 
                                        required 
                                        autofocus 
                                        autocomplete="name"
                                        class="pl-9"
                                        placeholder="Adınız Soyadınız"
                                    />
                                </div>
                            </div>

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
                                        autocomplete="new-password"
                                        class="pl-9"
                                        placeholder="••••••••"
                                    />
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label for="password_confirmation" class="text-sm font-medium">Şifre Tekrar</label>
                                <div class="relative">
                                    <i data-lucide="lock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"></i>
                                    <x-ui.input 
                                        id="password_confirmation" 
                                        type="password" 
                                        name="password_confirmation" 
                                        required 
                                        autocomplete="new-password"
                                        class="pl-9"
                                        placeholder="••••••••"
                                    />
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <x-ui.button type="submit" class="w-full gap-2">
                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                Kayıt Ol
                            </x-ui.button>
                        </form>

                        <!-- Login Link -->
                        <div class="text-center pt-4 border-t">
                            <p class="text-sm text-muted-foreground">
                                Zaten bir hesabınız var mı? 
                                <a href="{{ route('login') }}" class="text-primary hover:underline font-medium">
                                    Giriş Yapın
                                </a>
                            </p>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>

                <!-- Additional Info -->
                <div class="text-center">
                    <p class="text-xs text-muted-foreground">
                        Kayıt olarak otel iletişim portalına erişebilirsiniz
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
