<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': dark }" x-init="if (dark) $el.classList.add('dark')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Otel İletişim Portalı') - {{ config('app.name', 'Roomie') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('scripts')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-background text-foreground pb-24 md:pb-6">
        @include('layouts.portal.topbar', ['role' => $role ?? 'Yönetim'])
        
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
            <div class="mx-auto max-w-[1920px] flex gap-6">
                <aside class="hidden md:block w-64 flex-shrink-0 space-y-4">
                    @yield('sidebar')
                </aside>
                <main class="flex-1 min-w-0 flex justify-center">
                    <div class="w-full max-w-6xl">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
        
        @include('layouts.portal.mobile-bottom-nav', ['role' => $role ?? 'Yönetim', 'activeTab' => $activeTab ?? 'dashboard'])
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

