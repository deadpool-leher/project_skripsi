<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Incoming Orders</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body {
    margin:0;
    font-family:'Poppins', sans-serif;
    display:flex;
    background:#f5f7fb;
}

.sidebar {
    width:230px;
    height:100vh;
    background:white;
    padding:20px;

    display:flex;
    flex-direction:column;
    justify-content:space-between; /* 🔥 INI KUNCI */
}

.menu a {
    display:block;
    padding:10px;
    margin:5px 0;
    text-decoration:none;
    color:#333;
    border-radius:8px;
}

.menu a.active {
    background:#e0e7ff;
    font-weight:bold;
}
.menu a:hover {
    background:#f3f4f6;
}

/* MAIN */
.main {
    flex:1;
    padding:25px;
}

/* HEADER */
.header {
    display:flex;
    align-items:center;
    gap:10px;
}

.badge {
    background:#ff4d4f;
    color:white;
    font-size:12px;
    padding:3px 8px;
    border-radius:10px;
}

/* CARDS */
.cards {
    display:flex;
    gap:15px;
    margin-top:20px;
}

.card {
    flex:1;
    padding:15px;
    border-radius:10px;
    font-weight:600;
}

.yellow { background:#fff7cc; }
.blue { background:#e6f0ff; }
.green { background:#e6ffe6; }

/* TAB */
.tabs {
    display:flex;
    gap:10px;
    margin-top:20px;
}

.tab {
    flex:1;
    text-align:center;
    padding:10px;
    border-radius:10px;
    background:#eee;
}

.tab.active {
    background:#f4b400;
    color:white;
}

/* ORDER BOX */
.order-box {
    background:white;
    margin-top:20px;
    padding:20px;
    border-radius:15px;
    border:2px solid #f4b400;
}

.order-header {
    display:flex;
    justify-content:space-between;
}

.order-detail {
    margin-top:10px;
    font-size:14px;
    color:#555;
}

/* BUTTON */
.actions {
    display:flex;
    gap:10px;
    margin-top:15px;
}

.btn {
    flex:1;
    padding:12px;
    border:none;
    border-radius:10px;
    color:white;
    font-weight:600;
    cursor:pointer;
}
.profile {
    font-size:14px;
}
.profile strong {
    display:block;
}

.profile small {
    color:#777;
}

.green-btn { background:#16a34a; }
.red-btn { background:#dc2626; }

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <!-- ATAS -->
    <div>
        <h3>🍦 Es Cream Treman</h3>

        <div class="menu">
            <a href="{{ route('dashboard') }}">Home</a>
            <a href="{{ route('incoming.orders') }}" class="active">Incoming Orders</a>
            <a href="#">Discount</a>
            <a href="#">Inventory</a>
            <a href="#">Sales</a>
            <a href="/admin/promo">Promo Management</a>
        </div>
    </div>

    <!-- BAWAH (PROFILE) -->
    <div class="profile">
        <strong>{{ session('user') }}</strong>
        <small>{{ session('email') }}</small>

        <a href="{{ route('login') }}" style="color:red;">Logout</a>
    </div>

</div>

<!-- MAIN -->
<div class="main">

    <!-- HEADER -->
    <div class="header">
        <h2>Pesanan Masuk</h2>
    </div>

    <p style="color:#777;">Kelola dan proses pesanan dari pelanggan</p>

    <!-- CARDS -->
    <div class="cards">
        <div class="card yellow">
        Pesanan Baru <br>
        <strong>{{ $totalBaru }}</strong>
        </div>

        <div class="card blue">
        Sedang Diproses <br>
        <strong>{{ $totalProses }}</strong>
        </div>

        <div class="card green">
        Total pesanan <br>
        <strong>{{ $totalHariIni }}</strong>
        </div>
    </div>

    <!-- TAB -->
    <div class="tabs">
        <a href="/incoming-orders/baru" class="tab {{ request()->is('incoming-orders/baru') ? 'active' : '' }}">Pesanan Baru</a>
        <a href="/incoming-orders/aktif" class="tab {{ request()->is('incoming-orders/aktif') ? 'active' : '' }}">Aktif</a>
        <a href="/incoming-orders/semua" class="tab {{ request()->is('incoming-orders/semua') ? 'active' : '' }}">Semua</a>
    </div>

   <!-- ORDER LIST -->
@foreach($orders as $order)
<div class="order-box">

    <div class="order-header">
        <strong>#ES-00{{ $order->id }}</strong>
        <strong>Rp {{ $order->total }}</strong>
    </div>

    <div class="order-detail">
        {{ $order->nama }} <br>
        Delivery: {{ $order->waktu }} <br>
        Alamat: {{ $order->alamat ?? 'COD'}} <br>
    </div>

    <div class="order-detail">
        {{ $order->produk }}
    </div>

    <div class="actions">

@if($order->status == 'baru')
    <a href="/order/terima/{{ $order->id }}" class="btn green-btn">Terima</a>
    <a href="/order/tolak/{{ $order->id }}" class="btn red-btn">Tolak</a>

@elseif($order->status == 'diproses')
    <a href="/order/siap/{{ $order->id }}" class="btn green-btn">Tandai Siap</a>

@elseif($order->status == 'siap')
    <a href="/order/selesai/{{ $order->id }}" class="btn green-btn">Tandai Selesai</a>

@endif

</div>

</div>
@endforeach

</div>

</body>
</html>