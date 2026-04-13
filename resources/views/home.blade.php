@extends('layouts.app')

@section('content')

<div class="logo">🍦</div>

<h1 class="hero-title">Ice Cream Treman</h1>
<p class="subtitle">Kelezatan Es Krim Premium</p>
<p class="tagline">✨ Ice Cream Sedap Sekali ✨</p>

<div class="promo-badge">
    beli sekarang <span>dapatkan promo</span>
</div>

<!-- SLIDER -->
<div class="slider">
    <div class="slides">
        @foreach($promos as $key => $promo)
        <div class="slide {{ $key == 0 ? 'active' : '' }}"
        style="background-image: url('{{ asset('storage/'.$promo->gambar) }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;">

        <h2>{{ $promo->diskon }}</h2>
        <p style="font-weight:600;">
        {{ $promo->judul }}
        </p>
        <small style="
            display:block;
            margin-top:5px;
            font-size:12px;
            opacity:0.9;
        ">
            {{ $promo->deskripsi }}
        </small>
        </div>
        @endforeach
    </div>
        

    <button class="prev">❮</button>
    <button class="next">❯</button>
</div>

<a href="{{ route('login') }}" class="btn">Mulai Berbelanja Sekarang →</a>

<div class="cards">
    <div class="card">
        <h3>100% Fresh</h3>
        <p>Tersedia setiap hari</p>
    </div>

    <div class="card">
        <h3>3 Rasa</h3>
        <p>Vanilla • Chocolate • Strawberry</p>
    </div>

    <div class="card">
        <h3>Delivery Cepat</h3>
        <p>Antar ke rumah Anda</p>
    </div>
</div>

@endsection