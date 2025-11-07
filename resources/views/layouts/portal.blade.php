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
        
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid grid-cols-12 gap-6 py-6">
            <aside class="hidden md:block col-span-12 md:col-span-3 lg:col-span-3 xl:col-span-2 space-y-4">
                @yield('sidebar')
            </aside>
            <main class="col-span-12 md:col-span-9 lg:col-span-9 xl:col-span-10 space-y-6">
                @yield('content')
            </main>
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

