@extends('layouts.main')

@section('title', 'Tüm Odalar - Roomie')

@section('content')

    <!-- Sayfa Başlığı Alanı -->
    <section class="page-title" style="background-image: url({{ asset('images/background/page-title.jpeg') }});">
        <div class="container">
            <h1>Tüm Odalarımız</h1>
            <p>Konfor ve lüksün birleştiği yer.</p>
        </div>
    </section>

    <!-- Odalar Bölümü -->
    <section class="our-room" style="padding: 80px 0;">
        <div class="container">
            <div class="row">

                @forelse ($rooms as $room)
                    <div class="col-md-4 col-sm-6">
                        <div class="room-item">
                            <div class="room-image"><img src="{{ asset($room->image_path) }}" alt="{{ $room->roomType->name }}"></div>
                            <div class="room-content">
                                <div class="room-title">
                                    <h4>{{ $room->roomType->name }}</h4>
                                    <div class="price">${{ number_format($room->roomType->price_per_night, 0) }} <span>/ Night</span></div>
                                </div>
                                <div class="room-meta">
                                    <ul>
                                        <li><i class="fa fa-users"></i> Capacity: <span>{{ $room->roomType->capacity }} Person</span></li>
                                        <li><i class="fa fa-bed"></i> Bed: <span>Single</span></li>
                                    </ul>
                                </div>
                                <a href="{{ route('rooms.show', $room->id) }}" class="btn-room">View Details <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <p class="text-center">Gösterilecek oda bulunamadı.</p>
                    </div>
                @endforelse

            </div>

            <!-- SAYFALAMA LİNKLERİ -->
            <div class="row">
                <div class="col-md-12 text-center">
                    {{ $rooms->links() }}
                </div>
            </div>

        </div>
    </section>

@endsection
