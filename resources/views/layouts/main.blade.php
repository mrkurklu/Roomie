<!DOCTYPE html>
<html lang="tr" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Roomie - Otel Rezervasyon')</title>

    <meta name="description" content="Otel Projesi">
    <meta name="keywords" content="Otel, Rezervasyon, Laravel">

    <!-- CSS Global Compulsory -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    @stack('styles')
</head>
<body>
<header class="header">
    <nav class="navbar navbar-default" id="main_navbar">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="logo"></a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li class="{{ Request::routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                    <li class="{{ Request::routeIs('rooms.*') ? 'active' : '' }}"><a href="{{ route('rooms.index') }}">Rooms</a></li>
                    <li class="{{ Request::routeIs('about') ? 'active' : '' }}"><a href="{{ route('about') }}">About Us</a></li>
                    <li class="{{ Request::routeIs('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}">Contact</a></li>

                    @auth
                        <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li><form method="POST" action="{{ route('logout') }}">@csrf<a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a></form></li>
                    @else
                        <li><a href="{{ route('login') }}">Log in</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>

<main>
    @yield('content')
</main>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <p>&copy; 2025 Roomie. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- JS -->
<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>

@stack('scripts')
</body>
</html>
