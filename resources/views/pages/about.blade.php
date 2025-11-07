@extends('layouts.main')

@section('title', 'Hakkımızda - Roomie')

@section('content')

    <!-- Sayfa Başlığı Alanı -->
    <section class="page-title" style="background-image: url({{ asset('images/background/about.jpeg') }});">
        <div class="container">
            <h1>Hakkımızda</h1>
            <p>Hikayemizi Keşfedin</p>
        </div>
    </section>

    <!-- Hakkımızda İçerik Bölümü -->
    <section class="about-us-content" style="padding: 80px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('images/about-us-image.jpeg') }}" alt="Otelimiz" class="img-responsive" style="border-radius: 8px;">
                </div>
                <div class="col-md-6">
                    <h2>Roomie'ye Hoş Geldiniz</h2>
                    <p>Misafirlerimize unutulmaz bir konaklama deneyimi sunma vizyonuyla 2020 yılında kapılarımızı açtık. Konfor, zarafet ve kişiye özel hizmet anlayışını bir araya getirerek, şehrin kalbinde huzurlu bir sığınak yaratmayı hedefledik.</p>
                    <p>Deneyimli ekibimiz, konaklamanızın her anını mükemmelleştirmek için buradadır. Modern olanaklarla donatılmış odalarımız, lezzetli yemekler sunan restoranımız ve rahatlatıcı atmosferimizle kendinizi evinizde hissedeceksiniz.</p>
                </div>
            </div>
        </div>
    </section>

@endsection
