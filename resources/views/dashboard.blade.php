@extends('layouts.main')

@section('title', 'Panelim - Roomie')

@section('content')
    <section class="page-title" style="background-image: url({{ asset('images/background/dashboard.jpeg') }});">
        <div class="container">
            <h1>Panelim</h1>
            <p>Hoş Geldiniz, {{ Auth::user()->name }}!</p>
        </div>
    </section>

    <section class="dashboard-content" style="padding: 80px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>Burası sizin kişisel paneliniz. Buradan rezervasyonlarınızı görüntüleyebilir ve profil bilgilerinizi düzenleyebilirsiniz.</p>

                    <!-- Admin ise Admin Paneline gitme linki göster -->
                    @if(Auth::user()->hasRole('superadmin'))
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-success">Yönetim Paneline Git</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
