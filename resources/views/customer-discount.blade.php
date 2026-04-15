<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Discount</title>
<style>
body { margin:0; font-family:Arial, sans-serif; display:flex; background:#f5f7fb; }
.sidebar { width:220px; background:#fff; padding:20px; min-height:100vh; border-right:1px solid #eee; display:flex; flex-direction:column; justify-content:space-between; }
.menu a { display:block; padding:10px; margin:5px 0; text-decoration:none; color:#333; border-radius:8px; }
.menu a.active { border:2px solid #6366f1; }
.menu a:hover { background:#f3f4f6; }
.main { flex:1; padding:30px; }
.title { font-size:24px; font-weight:700; }
.cards { display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:20px; margin:20px 0; }
.card { padding:20px; border-radius:15px; color:#fff; font-weight:700; }
.blue { background:linear-gradient(90deg,#3b82f6,#6366f1); }
.green { background:linear-gradient(90deg,#22c55e,#16a34a); }
.purple { background:linear-gradient(90deg,#a855f7,#7c3aed); }
.discount-grid { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:20px; }
.discount-card { background:#fff; border-radius:15px; padding:20px; box-shadow:0 5px 15px rgba(0,0,0,.05); }
.badge { display:inline-block; background:#e0f2fe; color:#0284c7; padding:5px 10px; border-radius:10px; font-size:12px; }
.percent { float:right; font-weight:700; }
.code-box { background:#f3f4f6; padding:10px; border-radius:8px; margin:10px 0; font-weight:700; }
.btn { width:100%; padding:10px; border:none; border-radius:10px; cursor:pointer; font-weight:700; }
.btn-claim { background:linear-gradient(90deg,#6366f1,#7c3aed); color:white; }
.btn-claimed { background:#dcfce7; color:#166534; cursor:default; }
.meta { color:#6b7280; font-size:13px; margin-bottom:6px; }
.alert { padding:12px 14px; border-radius:12px; margin-bottom:16px; font-size:14px; }
.alert-success { background:#dcfce7; color:#166534; }
.alert-error { background:#fee2e2; color:#991b1b; }
@media (max-width: 900px) { body { display:block; } .sidebar { width:auto; min-height:auto; } .cards, .discount-grid { grid-template-columns:1fr; } }
</style>
</head>
<body>
<div class="sidebar">
    <div>
        <h2>Es Cream Treman</h2>
        <div class="menu">
            <a href="/customer">Order</a>
            <a href="/discount" class="active">Discount</a>
        </div>
    </div>
    <div class="profile">
        <strong>{{ session('user') }}</strong><br>
        <small>{{ session('email') }}</small><br><br>
        <a href="{{ route('login') }}" style="color:red;">Logout</a>
    </div>
</div>

<div class="main">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="title">Discounts & Promotions</div>

    <div class="cards">
        <div class="card blue">{{ $activeDiscount }}<br>Active Discounts</div>
        <div class="card green">{{ $claimedDiscount }}<br>Claimed Discounts</div>
        <div class="card purple">{{ $discounts->sum('total_used') }}<br>Used Discounts</div>
    </div>

    <div class="discount-grid">
        @forelse($discounts as $discount)
            @php
                $claimed = $discount->userDiscounts->first();
            @endphp
            <div class="discount-card">
                <span class="badge">ACTIVE</span>
                <span class="percent">{{ $discount->value }}%</span>

                <h3>{{ $discount->name }}</h3>
                <p>{{ $discount->description }}</p>
                <div class="code-box">{{ $discount->code }}</div>
                <div class="meta">Valid until: {{ $discount->expired_at ? $discount->expired_at->format('d M Y H:i') : 'No expiry' }}</div>
                <div class="meta">Usage limit: {{ $discount->usage_limit ?? 'Unlimited' }}</div>

                @if($claimed)
                    <button class="btn btn-claimed" disabled>
                        {{ $claimed->used_at ? 'Used' : 'Claimed' }}
                    </button>
                @else
                    <form method="POST" action="{{ route('discount.claim', $discount->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-claim">Claim Discount</button>
                    </form>
                @endif
            </div>
        @empty
            <p>Belum ada diskon aktif.</p>
        @endforelse
    </div>
</div>
</body>
</html>
