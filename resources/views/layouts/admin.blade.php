<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Paneli') - Roomie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { display: flex; }
        .sidebar { width: 250px; height: 100vh; background: #343a40; color: white; position: fixed; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 15px; }
        .sidebar a:hover, .sidebar a.active { color: white; background-color: #495057; }
        .content { margin-left: 250px; padding: 20px; width: calc(100% - 250px); }
    </style>
</head>
<body>
<div class="sidebar">
    <h4 class="text-center p-3">Roomie Admin</h4>
    <a href="{{ route('admin.dashboard') }}" class="{{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
    <!-- Gelecekteki menü linkleri buraya eklenecek -->
</div>

<div class="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Çıkış Yap</a>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    @yield('content')
</div>

</body>
</html>
