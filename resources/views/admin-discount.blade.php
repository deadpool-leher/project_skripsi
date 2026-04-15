<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Discount Management</title>
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
    background: #f5f7fb;
    color: #1f2937;
}

.sidebar {
    width: 220px;
    background: #fff;
    padding: 20px;
    min-height: 100vh;
    border-right: 1px solid #eee;
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
    padding: 10px;
    margin: 5px 0;
    text-decoration: none;
    color: #333;
    border-radius: 8px;
}

.menu a.active {
    background: #e0e7ff;
    font-weight: 700;
}

.menu a:hover {
    background: #f3f4f6;
}

.profile {
    font-size: 14px;
}

.main {
    flex: 1;
    padding: 30px;
}

.title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 20px;
}

.alert {
    padding: 12px 14px;
    border-radius: 12px;
    margin-bottom: 16px;
    font-size: 14px;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
}

.stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    padding: 20px;
    border-radius: 16px;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.blue { background: linear-gradient(90deg, #3b82f6, #2563eb); }
.green { background: linear-gradient(90deg, #22c55e, #16a34a); }
.purple { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }

.layout {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 24px;
    align-items: start;
}

.panel {
    background: #fff;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
}

.panel h2 {
    margin-top: 0;
    font-size: 20px;
}

.input,
.textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    margin-bottom: 12px;
}

.textarea {
    min-height: 90px;
    resize: vertical;
}

.input:focus,
.textarea:focus {
    outline: none;
    border-color: #6366f1;
}

.btn {
    border: none;
    border-radius: 10px;
    padding: 10px 14px;
    font-weight: 700;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(90deg, #6366f1, #7c3aed);
    color: #fff;
    width: 100%;
}

.btn-secondary {
    background: #eef2ff;
    color: #4338ca;
}

.btn-danger {
    background: #fff;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.discount-list {
    display: grid;
    gap: 18px;
}

.discount-card {
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 18px;
}

.discount-head {
    display: flex;
    justify-content: space-between;
    align-items: start;
    gap: 12px;
    margin-bottom: 10px;
}

.discount-code {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 999px;
    background: #eef2ff;
    color: #4338ca;
    font-size: 13px;
    font-weight: 700;
}

.discount-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

.meta {
    color: #6b7280;
    font-size: 13px;
    margin: 4px 0;
}

.actions {
    display: flex;
    gap: 10px;
    margin-top: 14px;
    flex-wrap: wrap;
}

.edit-form {
    display: none;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
}

.edit-form.active {
    display: block;
}

@media (max-width: 980px) {
    body {
        display: block;
    }

    .sidebar {
        width: auto;
        min-height: auto;
    }

    .stats,
    .layout {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>
<div class="sidebar">
    <div>
        <div class="logo">🍦 Es Cream Treman</div>
        <div class="menu">
            <a href="/dashboard">Home</a>
            <a href="{{ route('incoming.orders') }}">Incoming Orders</a>
            <a href="{{ route('admin.discount.index') }}" class="active">Discount</a>
            <a href="{{ route('inventory.index') }}">Inventory</a>
            <a href="{{ route('sales.index') }}">Sales</a>
            <a href="/admin/promo">Promo Management</a>
        </div>
    </div>

    <div class="profile">
        <strong>{{ session('user') }}</strong><br>
        <small>{{ session('email') }}</small><br><br>
        <a href="{{ route('login') }}" style="color:red;">Logout</a>
    </div>
</div>

<div class="main">
    <div class="title">Discount Management</div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="stats">
        <div class="stat-card blue">{{ $totalDiscount }}<br>Total Discounts</div>
        <div class="stat-card green">{{ $activeDiscount }}<br>Active Discounts</div>
        <div class="stat-card purple">{{ $discounts->sum('total_used') }}<br>Used Discounts</div>
    </div>

    <div class="layout">
        <div class="panel">
            <h2>Tambah Diskon</h2>
            <form method="POST" action="{{ route('admin.discount.store') }}">
                @csrf
                <input type="text" name="code" class="input" placeholder="Kode diskon" value="{{ old('code') }}" required>
                <input type="text" name="name" class="input" placeholder="Nama diskon" value="{{ old('name') }}" required>
                <textarea name="description" class="textarea" placeholder="Deskripsi diskon">{{ old('description') }}</textarea>
                <input type="hidden" name="type" value="percent">
                <input type="number" name="value" class="input" placeholder="Nilai diskon (%)" min="1" max="100" value="{{ old('value') }}" required>
                <input type="datetime-local" name="expired_at" class="input" value="{{ old('expired_at') }}">
                <input type="number" name="usage_limit" class="input" placeholder="Batas penggunaan" min="1" value="{{ old('usage_limit') }}">
                <button type="submit" class="btn btn-primary">Simpan Diskon</button>
            </form>
        </div>

        <div class="panel">
            <h2>Daftar Diskon</h2>

            <div class="discount-list">
                @forelse($discounts as $discount)
                    <div class="discount-card">
                        <div class="discount-head">
                            <div>
                                <div class="discount-code">{{ $discount->code }}</div>
                                <h3>{{ $discount->name }}</h3>
                            </div>
                            <div class="discount-value">{{ $discount->value }}%</div>
                        </div>

                        <p>{{ $discount->description ?: 'Tidak ada deskripsi.' }}</p>
                        <div class="meta">Diklaim: {{ $discount->total_claimed }}</div>
                        <div class="meta">Digunakan: {{ $discount->total_used }}</div>
                        <div class="meta">Kadaluarsa: {{ $discount->expired_at ? $discount->expired_at->format('d M Y H:i') : 'Tidak ada batas waktu' }}</div>
                        <div class="meta">Batas penggunaan: {{ $discount->usage_limit ?? 'Tidak dibatasi' }}</div>

                        <div class="actions">
                            <button type="button" class="btn btn-secondary" onclick="toggleEdit({{ $discount->id }})">Edit</button>
                            <form method="POST" action="{{ route('admin.discount.destroy', $discount->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus diskon ini?')">Hapus</button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('admin.discount.update', $discount->id) }}" class="edit-form" id="edit-form-{{ $discount->id }}">
                            @csrf
                            @method('PUT')
                            <input type="text" name="code" class="input" value="{{ $discount->code }}" required>
                            <input type="text" name="name" class="input" value="{{ $discount->name }}" required>
                            <textarea name="description" class="textarea">{{ $discount->description }}</textarea>
                            <input type="hidden" name="type" value="percent">
                            <input type="number" name="value" class="input" min="1" max="100" value="{{ $discount->value }}" required>
                            <input type="datetime-local" name="expired_at" class="input" value="{{ $discount->expired_at ? $discount->expired_at->format('Y-m-d\\TH:i') : '' }}">
                            <input type="number" name="usage_limit" class="input" min="1" value="{{ $discount->usage_limit }}">
                            <button type="submit" class="btn btn-primary">Update Diskon</button>
                        </form>
                    </div>
                @empty
                    <div class="meta">Belum ada diskon yang dibuat.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function toggleEdit(id) {
    const form = document.getElementById('edit-form-' + id);
    form.classList.toggle('active');
}
</script>
</body>
</html>
