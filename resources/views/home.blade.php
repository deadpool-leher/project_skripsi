@extends('layouts.app')

@section('content')

<div class="logo">🍦</div>

<h1 class="hero-title">Ice Cream Treman</h1>
<p class="subtitle">Kelezatan Es Krim Premium</p>
<p class="tagline">✨ Ice Cream Sedap Sekali ✨</p>

<div class="promo-badge">
    Active Promotions <span>4</span>
</div>

<!-- SLIDER -->
<div class="slider">
    <div class="slides">
        <div class="slide active">
            <h2>30% OFF</h2>
            <p>Weekend Special</p>
        </div>
        <div class="slide">
            <h2>Buy 1 Get 1</h2>
            <p>Special Promo</p>
        </div>
        <div class="slide">
            <h2>Gratis Ongkir</h2>
            <p>Untuk area kota</p>
        </div>
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