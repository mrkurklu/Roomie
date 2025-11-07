@extends('layouts.main')

@section('title', 'İletişim - Roomie')

@section('content')

    <!-- Sayfa Başlığı Alanı -->
    <section class="page-title" style="background-image: url({{ asset('images/background/contact.jpeg') }});">
        <div class="container">
            <h1>İletişim</h1>
            <p>Bize Ulaşın</p>
        </div>
    </section>

    <!-- İletişim İçerik Bölümü -->
    <section class="contact-content" style="padding: 80px 0;">
        <div class="container">
            <div class="row">
                <!-- İletişim Formu -->
                <div class="col-md-8">
                    <h2>Mesaj Gönderin</h2>
                    <p>Soru ve önerileriniz için aşağıdaki formu doldurabilirsiniz.</p>
                    <form class="contact-form" method="POST" action="#">
                        @csrf
                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><input type="text" name="name" class="form-control" placeholder="Adınız Soyadınız" required></div></div>
                            <div class="col-md-6"><div class="form-group"><input type="email" name="email" class="form-control" placeholder="E-posta Adresiniz" required></div></div>
                        </div>
                        <div class="form-group"><input type="text" name="subject" class="form-control" placeholder="Konu" required></div>
                        <div class="form-group"><textarea name="message" class="form-control" rows="5" placeholder="Mesajınız" required></textarea></div>
                        <button type="submit" class="btn btn-primary">Gönder</button>
                    </form>
                </div>

                <!-- İletişim Bilgileri -->
                <div class="col-md-4">
                    <h2>İletişim Bilgileri</h2>
                    <div class="contact-info">
                        <ul class="info-list" style="list-style: none; padding-left: 0;">
                            <li><i class="fa fa-map-marker"></i> <strong>Adres:</strong> Istanbul, Turkey</li>
                            <li><i class="fa fa-phone"></i> <strong>Telefon:</strong> +90 123 456 78 90</li>
                            <li><i class="fa fa-envelope"></i> <strong>E-posta:</strong> contact@roomieistanbul.com</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
