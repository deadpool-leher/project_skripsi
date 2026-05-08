<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body {
    margin:0;
    font-family:'Poppins', sans-serif;
    background:#f5f7fb;
    display:flex;
}


/* SIDEBAR */
.sidebar {
    width:220px;
    height:100vh;
    background:white;
    padding:20px;

    display:flex;
    flex-direction:column;
    justify-content:space-between; 

}

.sidebar h3 {
    margin-bottom:20px;
}

.menu a {
    display:block;
    padding:10px;
    margin:5px 0;
    text-decoration:none;
    color:#333;
    border-radius:8px;
}

.menu a:hover {
    background:#eef1ff;
}

.menu a.active {
    background:#e0e7ff;
    font-weight:bold;
}

/* MAIN */
.main {
    flex:1;
    padding:30px;
}

.cards {
    display:flex;
    gap:20px;
}

.card {
    background:white;
    padding:20px;
    border-radius:15px;
    flex:1;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.title {
    font-size:14px;
    color:#777;
}

.value {
    font-size:20px;
    font-weight:600;
    margin-top:5px;
}

/* ORDER */
.order-box {
    background:white;
    margin-top:25px;
    padding:20px;
    border-radius:15px;
    border:2px solid #f1d67a;
}

.order-header {
    display:flex;
    justify-content:space-between;
    margin-bottom:10px;
}

.order-detail {
    background:#f9fafc;
    padding:15px;
    border-radius:10px;
    margin-top:10px;
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

.green { background:#16a34a; }
.red { background:#dc2626; }

/* BOTTOM */
.bottom-box {
    margin-top:30px;
    background:linear-gradient(90deg,#5a5cff,#7b2cff);
    color:white;
    padding:25px;
    border-radius:15px;
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
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <!--BAGIAN ATAS-->
    <div>
        <h3>🍦 Es Cream Treman</h3>

        <div class="menu">
            <a href="{{ route('dashboard') }}" class="active">Home</a>
            <a href="{{ route('incoming.orders') }}">Incoming Orders</a>
            <a href="{{ route('admin.discount.index') }}">Discount</a>
            <a href="{{ route('inventory.index') }}">Inventory</a>
            <a href="{{ route('sales.index') }}">Sales</a>
            <a href="{{ url('/admin/promo') }}">Promo Management</a>
        </div>
    </div>

    <!--BAGIAN BAWAH -->
    <div class="profile">
        <strong>{{ session('admin_user') }}</strong>
        <small>{{ session('admin_email') }}</small>

        <a href="{{ route('logout', ['role' => 'admin']) }}">Logout</a>
    </div>

</div>


<!-- MAIN -->
<div class="main">

    <h2>Dashboard Admin</h2>

    <div class="cards">
        <div class="card">
            <div class="title">Total Sales</div>
            <div class="value">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="title">total Pesanan </div>
            <div class="value">{{ $totalPesanan }}</div>
        </div>
        <div class="card">
            <div class="title">Pesanan Aktif</div>
            <div class="value">{{ $pesananAktif }}</div>
        </div>
        <div class="card">
            <div class="title">Total Pelanggan</div>
            <div class="value">{{ $totalPelanggan }}</div>
        </div>
    </div>

    <!-- BOTTOM -->
    <div class="bottom-box">
        <h3>Kelola Toko Anda</h3>
        <p>Akses fitur manajemen dengan mudah</p>
    </div>

    
</div>

</body>
</html>
