@extends('layouts.main')

@section('title', $room->roomType->name . ' - Roomie')

@section('content')

    <!-- Sayfa Başlığı Alanı -->
    <section class="page-title" style="background-image: url({{ asset($room->image_path) }});">
        <div class="container">
            <h1>{{ $room->roomType->name }}</h1>
        </div>
    </section>

    <!-- Oda Detay Bölümü -->
    <section class="room-detail" style="padding: 80px 0;">
        <div class="container">
            <div class="row">
                <!-- Oda Resimleri/Galerisi -->
                <div class="col-md-8">
                    <h2>Oda Detayları</h2>
                    <div class="room-gallery">
                        <img src="{{ asset($room->image_path) }}" alt="{{ $room->roomType->name }}" class="img-responsive" style="margin-bottom: 20px;">
                    </div>
                    <div class="room-description">
                        <h4>Açıklama</h4>
                        <p>{{ $room->roomType->description ?? 'Bu oda için bir açıklama mevcut değil.' }}</p>
                    </div>
                </div>

                <!-- Oda Bilgi Kutusu -->
                <div class="col-md-4">
                    <div class="sidebar">
                        <div class="booking-box">
                            <h3>Oda Bilgileri</h3>
                            <div class="price-tag">
                                <span>${{ number_format($room->roomType->price_per_night, 0) }}</span> / Gece
                            </div>
                        </div>
                        <div class="room-features">
                            <h4>Oda Özellikleri</h4>
                            <ul>
                                <li><i class="fa fa-users"></i> Kapasite: {{ $room->roomType->capacity }} Kişi</li>
                                <li><i class="fa fa-bed"></i> Yatak Tipi: Single</li>
                                <li><i class="fa fa-wifi"></i> Ücretsiz Wi-Fi</li>
                                <li><i class="fa fa-bath"></i> Özel Banyo</li>
                                <li><i class="fa fa-tv"></i> Televizyon</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
