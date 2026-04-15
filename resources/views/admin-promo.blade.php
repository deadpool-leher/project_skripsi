<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Promo Management</title>

<style>
body {
    margin:0;
    font-family: Arial;
    display:flex;
    background:#f5f7fb;
}

/* SIDEBAR */
.sidebar {
    width:220px;
    background:white;
    padding:20px;
    height:100vh;
    border-right:1px solid #eee;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

.sidebar h2 {
    font-size:18px;
}

.logo {
    font-size:18px;
    font-weight:700;
    margin-bottom:30px;
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

.profile {
    font-size:14px;
}

/* MAIN */
.main {
    flex:1;
    padding:30px;
}

.title {
    font-size:22px;
    font-weight:bold;
}

.grid {
    display:grid;
    grid-template-columns: repeat(2, 1fr);
    gap:20px;
    margin-top:20px;
}

.card {
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.image {
    height:150px;
    background-size:cover;
    background-position:center;
    position:relative;
}

.badge {
    position:absolute;
    top:10px;
    left:10px;
    background:#6366f1;
    color:white;
    padding:5px 10px;
    border-radius:8px;
    font-size:12px;
}

.discount {
    position:absolute;
    bottom:10px;
    left:10px;
    background:white;
    padding:5px 10px;
    border-radius:8px;
    font-weight:bold;
}

.content {
    padding:15px;
}

.btn {
    width:100%;
    padding:10px;
    border:none;
    border-radius:10px;
    margin-top:10px;
    cursor:pointer;
}

.edit {
    background:linear-gradient(90deg,#6366f1,#7c3aed);
    color:white;
}

.delete {
    border:1px solid red;
    color:red;
    background:white;
}

.input {
    width:100%;
    padding:10px;
    border-radius:8px;
    border:1px solid #ddd;
    margin-bottom:10px;
    font-size:14px;
}

.input:focus {
    outline:none;
    border-color:#6366f1;
}

.input-file {
    margin:10px 0;
}

.save {
    background:#16a34a;
    color:white;
}

.cancel {
    background:#e5e7eb;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div>
        <div class="logo">🍦 Es Cream Treman</div>

        <div class="menu">
            <a href="/dashboard">Home</a>
            <a href="{{ route('incoming.orders') }}">Incoming Orders</a>
            <a href="{{ route('admin.discount.index') }}">Discount</a>
            <a href="{{ route('inventory.index') }}">Inventory</a>
            <a href="{{ route('sales.index') }}">Sales</a>
            <a href="/admin/promo" class="active">Promo Management</a>
        </div>
    </div>

    <div class="profile">
        <strong>{{ session('user') }}</strong><br>
        <small>{{ session('email') }}</small><br><br>
        <a href="{{ route('login') }}" style="color:red;">Logout</a>
    </div>

</div>

<!-- MAIN -->
<div class="main">

    <div class="title">Kelola Promo Landing Page</div>

    <div class="grid">

        <!-- PROMO 1 -->
    @foreach($promos as $index => $promo)
<div class="card">

    <div class="image" style="background-image:url('{{ asset('storage/'.$promo->gambar) }}')">
        <span class="badge">PROMO {{ $index+1 }}</span>
        <span class="discount">{{ $promo->diskon }}</span>
    </div>

    <div class="content">

        <!-- VIEW MODE -->
        <div class="view-mode">
            <b class="title">{{ $promo->judul }}</b><br>
            <small class="desc">{{ $promo->deskripsi }}</small>

            <button class="btn edit" onclick="toggleEdit(this)">Edit Promo</button>
        </div>

        <!-- EDIT MODE -->
        <div class="edit-mode" style="display:none; margin-top:10px;">

        <form action="{{ route('promo.update', $promo->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Judul Promo</label>
            <input type="text" name="judul" class="input" value="{{ $promo->judul }}">

            <label>Nilai Diskon</label>
            <input type="text" name="diskon" class="input" value="{{ $promo->diskon }}">

            <label>Deskripsi</label>
            <textarea name="deskripsi" class="input">{{ $promo->deskripsi }}</textarea>

            <label>Upload Gambar</label>
            <input type="file" name="gambar" class="input-file">

            <button type="submit" class="btn save">✔ Simpan</button>
        </form>

        <button class="btn cancel" onclick="cancelEdit(this)">✖ Batal</button>

        </div>
    </div>

</div>
@endforeach

        <!-- PROMO 2 -->
<script>
function toggleEdit(btn) {
    const card = btn.closest('.card');
    card.querySelector('.view-mode').style.display = 'none';
    card.querySelector('.edit-mode').style.display = 'block';
}

function cancelEdit(btn) {
    const card = btn.closest('.card');
    card.querySelector('.edit-mode').style.display = 'none';
    card.querySelector('.view-mode').style.display = 'block';
}

function saveEdit(btn) {
    const card = btn.closest('.card');

    const title = card.querySelector('.input-title').value;
    const diskon = card.querySelector('.input-diskon').value;
    const desc = card.querySelector('.input-desc').value;

    card.querySelector('.title').innerText = title;
    card.querySelector('.desc').innerText = desc;
    card.querySelector('.discount').innerText = diskon;

    card.querySelector('.edit-mode').style.display = 'none';
    card.querySelector('.view-mode').style.display = 'block';
}
</script>
</body>
</html>
