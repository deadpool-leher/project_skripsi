<!DOCTYPE html>
<html>
<head>
    <title>Lacak Pesanan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .card {
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .price-box {
            background: linear-gradient(to right, #6d28d9, #9333ea);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .price-box h3 {
            margin: 0;
        }

        .status-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .step {
            padding: 10px;
            border-radius: 8px;
            background: #eee;
        }

        .active {
            background: #d1fae5;
            color: #065f46;
            font-weight: bold;
        }

        .btn-close {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: #ddd;
            cursor: pointer;
        }

    </style>
</head>
<body>

<div class="card">

    <div class="header">Lacak Pesanan</div>

    @if($order)

@php
$status = $order->status ?? 'baru';
@endphp

<p>Status: <b>{{ $order->status_label }}</b></p>

<div class="price-box">
    <h3>Rp {{ number_format($order->total) }}</h3>
    <p>{{ $order->metode ?? '-' }} • {{ $order->waktu ?? '-' }}</p>
</div>

{{-- PICKUP MAP --}}
@if($order->alamat == 'ambil ditempat')
    <div style="margin:10px 0;">
        <a href="https://maps.app.goo.gl/WqHZTi85pbS2GqaBA" target="_blank"
           style="
            display:block;
            padding:10px;
            border-radius:10px;
            background:#eef1ff;
            color:#4f46e5;
            text-decoration:none;
            font-weight:500;
        ">
            📍 Lihat Lokasi Pickup
        </a>
    </div>
@endif

{{-- STATUS --}}
@if($order->status == 'ditolak')

    <div style="
        background:#fee2e2;
        color:#991b1b;
        padding:12px;
        border-radius:10px;
        margin:10px 0;
        font-weight:500;
    ">
        ❌ Pesanan Anda Ditolak
        <br>
        Silakan lakukan pemesanan ulang.
    </div>

@else

    <div class="status-title">Status Pesanan</div>
    <div style="display:flex; flex-direction:column; gap:10px;">

        <div class="step {{ $status == 'baru' ? 'active' : '' }}">
            Menunggu Konfirmasi
        </div>

        <div class="step {{ in_array($status, ['diproses','siap','selesai']) ? 'active' : '' }}">
            Order Dikonfirmasi
        </div>

        <div class="step {{ in_array($status, ['diproses','siap','selesai']) ? 'active' : '' }}">
            Sedang Diproses
        </div>

        <div class="step {{ in_array($status, ['siap','selesai']) ? 'active' : '' }}">
            Siap Diambil
        </div>

        <div class="step {{ $status == 'selesai' ? 'active' : '' }}">
            Selesai
        </div>

    </div>

@endif

<button class="btn-close" onclick="window.location.href='/customer'">
    Tutup
</button>

@else

<p>Tidak ada pesanan</p>

@endif

</div>

</body>
</html>