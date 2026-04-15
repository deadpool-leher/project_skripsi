<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f5f6fa;
        }

        .container {
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            background: white;
            height: 100vh;
            padding: 20px;
            border-right: 1px solid #eee;
        }

        .sidebar h2 {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .menu a {
            display: block;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            margin-bottom: 10px;
        }

        .menu a.active {
            background: #eef2ff;
            color: #4f46e5;
            font-weight: bold;
        }

        /* CONTENT */
        .content {
            flex: 1;
            padding: 30px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
        }

        .subtitle {
            color: gray;
            margin-bottom: 20px;
        }

        /* CARDS */
        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            flex: 1;
            padding: 20px;
            border-radius: 12px;
            color: white;
            font-weight: bold;
        }

        .blue { background: linear-gradient(45deg, #2563eb, #1d4ed8); }
        .green { background: linear-gradient(45deg, #16a34a, #15803d); }
        .purple { background: linear-gradient(45deg, #9333ea, #7e22ce); }

        .card span {
            display: block;
            font-size: 14px;
            opacity: 0.8;
        }

        .card h2 {
            margin: 5px 0 0;
        }

        /* TABLE */
        .table {
            background: white;
            border-radius: 12px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 14px;
            color: gray;
        }

        td {
            padding: 12px 0;
            border-top: 1px solid #eee;
        }

        .badge {
            background: #eef2ff;
            color: #4f46e5;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 12px;
        }

        .stok {
            background: #dcfce7;
            color: #166534;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 12px;
        }

        .actions button {
            border: none;
            background: none;
            cursor: pointer;
            margin-right: 10px;
        }

        /* BUTTON */
        .btn-add {
            float: right;
            background: #6366f1;
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
        }

        /* MODAL */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .modal-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            width: 350px;
        }

        .modal-box input {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
        }

        .modal-box button {
            width: 100%;
            padding: 10px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 8px;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>🍦 Es Cream Treman</h2>

        <div class="menu">
            <a href="#">Home</a>
            <a href="#">Incoming Orders</a>
            <a href="#">Discount</a>
            <a href="#" class="active">Inventory</a>
            <a href="#">Sales</a>
            <a href="#">Promo Management</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <button class="btn-add">+ Tambah Produk</button>

        <div class="title">Manajemen Produk</div>
        <div class="subtitle">Kelola stok dan informasi produk</div>

        <!-- CARDS -->
        <div class="cards">
            <div class="card blue">
                <span>Total Produk</span>
                <h2>1</h2>
            </div>

            <div class="card green">
                <span>Total Stok</span>
                <h2>50</h2>
            </div>

            <div class="card purple">
                <span>Stok Menipis</span>
                <h2>0</h2>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table">
            <table>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>

                <tr>
                    <td>🍦 Ice Cream</td>
                    <td><span class="badge">Desserts</span></td>
                    <td>Rp 220.000</td>
                    <td><span class="stok">50 unit</span></td>
                    <td class="actions">
                        <button onclick="openModal()">✏️</button>
                        <button>🗑️</button>
                    </td>
                </tr>
            </table>
        </div>

    </div>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
    <div class="modal-box">
        <h3>Edit Produk</h3>

        <input type="text" placeholder="Nama Produk">
        <input type="text" placeholder="Kategori">
        <input type="number" placeholder="Harga">
        <input type="number" placeholder="Stok">

        <button>Simpan</button>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modal').style.display = 'flex';
}
</script>

</body>
</html>