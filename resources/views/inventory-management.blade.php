<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>

    <style>
        :root {
            --bg: #f4f6fb;
            --panel: #ffffff;
            --line: #e6ebf2;
            --text: #1f2937;
            --muted: #6b7280;
            --primary: #2563eb;
            --primary-soft: #dbeafe;
            --success: #15803d;
            --success-soft: #dcfce7;
            --warning: #b45309;
            --warning-soft: #fef3c7;
            --danger: #dc2626;
            --shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background: var(--panel);
            padding: 20px;
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .logo {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .menu a {
            display: block;
            padding: 10px 12px;
            margin: 5px 0;
            text-decoration: none;
            color: #374151;
            border-radius: 10px;
            transition: 0.2s ease;
        }

        .menu a:hover {
            background: #f3f4f6;
        }

        .menu a.active {
            background: #e0e7ff;
            font-weight: 700;
        }

        .profile {
            font-size: 14px;
            color: var(--muted);
        }

        .profile strong {
            display: block;
            color: var(--text);
            margin-bottom: 4px;
        }

        .profile a {
            display: inline-block;
            margin-top: 12px;
            color: var(--danger);
            text-decoration: none;
        }

        .main {
            flex: 1;
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .page-subtitle {
            color: var(--muted);
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .card {
            background: var(--panel);
            border-radius: 18px;
            padding: 22px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.75);
        }

        .card-label {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .card-value {
            font-size: 30px;
            font-weight: 700;
        }

        .card.blue {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
        }

        .card.green {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        }

        .card.orange {
            background: linear-gradient(135deg, #fff7ed, #ffedd5);
        }

        .panel {
            background: var(--panel);
            border-radius: 18px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 14px;
        }

        .panel-title {
            font-size: 18px;
            font-weight: 700;
        }

        .panel-note {
            font-size: 13px;
            color: var(--muted);
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 16px 12px;
            text-align: left;
            border-top: 1px solid var(--line);
            vertical-align: middle;
        }

        th {
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
            border-top: none;
        }

        .product-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 220px;
        }

        .product-thumb {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
            background: #eff6ff;
            border: 1px solid var(--line);
        }

        .product-placeholder {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: #eff6ff;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .product-name {
            font-weight: 600;
        }

        .product-meta {
            font-size: 12px;
            color: var(--muted);
            margin-top: 3px;
        }

        .badge {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 999px;
            background: var(--primary-soft);
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 700;
        }

        .stock-badge {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .stock-safe {
            background: var(--success-soft);
            color: var(--success);
        }

        .stock-low {
            background: var(--warning-soft);
            color: var(--warning);
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-add {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.18);
        }

        .btn-edit {
            background: #e0e7ff;
            color: #3730a3;
        }

        .btn-delete {
            background: #fee2e2;
            color: #b91c1c;
        }

        .empty-state {
            padding: 24px 8px 8px;
            color: var(--muted);
            text-align: center;
        }

        .modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal.show {
            display: flex;
        }

        .modal-box {
            width: 100%;
            max-width: 420px;
            background: var(--panel);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .modal-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 18px;
        }

        .field {
            margin-bottom: 14px;
        }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .field input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
        }

        .field input:focus {
            outline: none;
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(147, 197, 253, 0.25);
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 18px;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .btn-primary {
            flex: 1;
            background: var(--primary);
            color: white;
        }

        .btn-secondary {
            flex: 1;
            background: #e5e7eb;
            color: #111827;
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid var(--line);
                gap: 20px;
            }

            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="layout">
        <div class="sidebar">
            <div>
                <div class="logo">🍦 Es Cream Treman</div>

                <div class="menu">
                    <a href="{{ route('dashboard') }}">Home</a>
                    <a href="{{ route('incoming.orders') }}">Incoming Orders</a>
                    <a href="{{ route('admin.discount.index') }}">Discount</a>
                    <a href="{{ route('inventory.index') }}" class="active">Inventory</a>
                    <a href="{{ route('sales.index') }}">Sales</a>
                    <a href="{{ url('/admin/promo') }}">Promo Management</a>
                </div>
            </div>

            <div class="profile">
                <strong>{{ session('admin_user') }}</strong>
                <span>{{ session('admin_email') }}</span>
                <a href="{{ route('logout', ['role' => 'admin']) }}">Logout</a>
            </div>
        </div>

        <div class="main">
            @if ($errors->any())
                <div class="error-box">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="page-header">
                <div>
                    <div class="page-title">Inventory Management</div>
                    <div class="page-subtitle">Kelola data produk, kategori, harga, dan stok</div>
                </div>

                <div class="header-actions">
                    <button type="button" class="btn btn-add" onclick="openCreate()">+ Tambah Produk</button>
                </div>
            </div>

            <div class="cards">
                <div class="card blue">
                    <div class="card-label">Total Produk</div>
                    <div class="card-value">{{ $totalProduk }}</div>
                </div>

                <div class="card green">
                    <div class="card-label">Total Stok</div>
                    <div class="card-value">{{ $totalStok }}</div>
                </div>

            </div>

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Daftar Produk</div>
                        <div class="panel-note">Klik edit untuk memperbarui data produk secara langsung.</div>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>
                                        <div class="product-cell">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama }}" class="product-thumb">
                                            @else
                                                <div class="product-placeholder">IMG</div>
                                            @endif
                                            <div>
                                                <div class="product-name">{{ $product->nama }}</div>
                                                <div class="product-meta">ID Produk #{{ $product->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge">{{ $product->kategori }}</span>
                                    </td>
                                    <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="stock-badge {{ $product->stok <= 5 ? 'stock-low' : 'stock-safe' }}">
                                            {{ $product->stok }} unit
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button
                                                type="button"
                                                class="btn btn-edit"
                                                onclick="openEdit(
                                                    {{ $product->id }},
                                                    @js($product->nama),
                                                    @js($product->kategori),
                                                    {{ $product->harga }},
                                                    {{ $product->stok }}
                                                )"
                                            >
                                                Edit
                                            </button>
                                            <form method="POST" action="{{ route('inventory.destroy', $product->id) }}" onsubmit="return confirm('Hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">Belum ada produk pada inventory.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="createModal">
        <div class="modal-box">
            <div class="modal-title">Tambah Produk</div>
            <div class="modal-subtitle">Masukkan data produk baru ke inventory.</div>

            <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="field">
                    <label for="create_nama">Nama Produk</label>
                    <input type="text" id="create_nama" name="nama" required>
                </div>

                <div class="field">
                    <label for="create_kategori">Kategori</label>
                    <input type="text" id="create_kategori" name="kategori" required>
                </div>

                <div class="field">
                    <label for="create_harga">Harga</label>
                    <input type="number" id="create_harga" name="harga" min="0" required>
                </div>

                <div class="field">
                    <label for="create_stok">Stok</label>
                    <input type="number" id="create_stok" name="stok" min="0" required>
                </div>

                <div class="field">
                    <label for="create_image">Gambar Produk</label>
                    <input type="file" id="create_image" name="image" accept=".jpg,.jpeg,.png,.webp">
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeCreate()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="editModal">
        <div class="modal-box">
            <div class="modal-title">Edit Produk</div>
            <div class="modal-subtitle">Perbarui informasi produk yang dipilih.</div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="nama">Nama Produk</label>
                    <input type="text" id="nama" name="nama" required>
                </div>

                <div class="field">
                    <label for="kategori">Kategori</label>
                    <input type="text" id="kategori" name="kategori" required>
                </div>

                <div class="field">
                    <label for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" min="0" required>
                </div>

                <div class="field">
                    <label for="stok">Stok</label>
                    <input type="number" id="stok" name="stok" min="0" required>
                </div>

                <div class="field">
                    <label for="image">Ganti Gambar</label>
                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEdit()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreate() {
            document.getElementById('createModal').classList.add('show');
        }

        function closeCreate() {
            document.getElementById('createModal').classList.remove('show');
        }

        function openEdit(id, nama, kategori, harga, stok) {
            document.getElementById('editForm').action = '/inventory/update/' + id;
            document.getElementById('nama').value = nama;
            document.getElementById('kategori').value = kategori;
            document.getElementById('harga').value = harga;
            document.getElementById('stok').value = stok;
            document.getElementById('editModal').classList.add('show');
        }

        function closeEdit() {
            document.getElementById('editModal').classList.remove('show');
        }

        document.getElementById('createModal').addEventListener('click', function (event) {
            if (event.target.id === 'createModal') {
                closeCreate();
            }
        });

        document.getElementById('editModal').addEventListener('click', function (event) {
            if (event.target.id === 'editModal') {
                closeEdit();
            }
        });
    </script>
</body>
</html>
