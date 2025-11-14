<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': dark }" x-init="if (dark) $el.classList.add('dark')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Otel İletişim Portalı') - {{ config('app.name', 'Roomie') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('scripts')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-white dark:bg-background-dark text-foreground pb-24 md:pb-6">
        @if(($role ?? 'Yönetim') === 'Misafir')
            @include('layouts.portal.topbar-guest')
        @else
            @include('layouts.portal.topbar', ['role' => $role ?? 'Yönetim'])
        @endif
        
        @if(($role ?? 'Yönetim') === 'Misafir')
            <main class="flex-1 min-w-0 w-full">
                @yield('content')
            </main>
        @else
            <div class="w-full px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6">
                <div class="mx-auto max-w-[1920px] flex flex-col md:flex-row gap-4 md:gap-6">
                    <aside class="hidden md:block w-full md:w-64 lg:w-72 flex-shrink-0 space-y-4">
                        @yield('sidebar')
                    </aside>
                    <main class="flex-1 min-w-0 w-full">
                        <div class="w-full max-w-7xl mx-auto">
                            @yield('content')
                        </div>
                    </main>
                </div>
            </div>
        @endif
        
        @if(($role ?? 'Yönetim') === 'Misafir')
            @include('layouts.portal.mobile-bottom-nav', ['role' => $role ?? 'Yönetim', 'activeTab' => $activeTab ?? 'dashboard'])
        @endif
    </div>
    
    <script>
        // Dark mode initialization
        document.addEventListener('DOMContentLoaded', function() {
            const darkMode = localStorage.getItem('darkMode') === 'true';
            if (darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Lucide icons initialization
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>

