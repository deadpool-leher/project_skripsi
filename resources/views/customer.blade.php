<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Customer Dashboard</title>

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
    width:230px;
    height:100vh;
    background:white;
    padding:20px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

.logo {
    font-weight:600;
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

.menu a.active {
    border:2px solid #6366f1;
}

.menu a:hover {
    background:#f3f4f6;
}

/* MAIN */
.main {
    flex:1;
    padding:30px;
}

.title {
    font-size:22px;
    font-weight:600;
}

.subtitle {
    color:#777;
    font-size:14px;
    margin-bottom:20px;
}

/* PRODUCT */
.product-card {
    width:200px;
    background:white;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    overflow:hidden;
}

.product-top {
    background:#dcdcf6;
    text-align:center;
    padding:20px;
    font-size:40px;
}

.product-body {
    padding:15px;
}

.product-title {
    font-weight:600;
}

.price {
    margin-top:5px;
}

.add-btn {
    float:right;
    background:#6c5cff;
    color:white;
    border:none;
    border-radius:50%;
    width:30px;
    height:30px;
    cursor:pointer;
}

/* CART */
.cart {
    width:250px;
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.cart-title {
    font-weight:600;
}

.empty {
    text-align:center;
    margin-top:40px;
    color:#aaa;
}

/* PROFILE */
.profile {
    font-size:14px;
}

.content {
    display: flex;
    justify-content: space-between; /* 🔥 dorong kiri & kanan */
    align-items: flex-start;
    padding-right: 80px; /* 🔥 kasih napas kanan */
}

/* PRODUK */
.products {
    margin-left: 40px; /* 🔥 geser dari sidebar */
}

/* CARD */
.product-card {
    width: 220px;
}

/* BOX CART */
.cart-box {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

/* cart pay */
.modal {
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    justify-content:center;
    align-items:center;
}

.modal-content {
    background:white;
    padding:20px;
    border-radius:15px;
    width:350px;
}

.option-group button,
.time-grid button {
    padding:10px;
    margin:5px;
    border-radius:8px;
    border:1px solid #ccc;
    cursor:pointer;
}

.active {
    border:2px solid blue;
}

#continueBtn:disabled {
    background:#ccc;
}

.option-btn {
    flex:1;
    padding:12px;
    border:1px solid #ddd;
    border-radius:10px;
    background:#f5f5f5;
    cursor:pointer;
    transition:0.2s;
}

.option-btn.active {
    border:2px solid #4f46e5;
    background:#eef1ff;
    color:#4f46e5;
}

.time-btn {
    padding:10px;
    border-radius:8px;
    border:1px solid #ddd;
    background:#f5f5f5;
    cursor:pointer;
}

.time-btn.active {
    border:2px solid #4f46e5;
    background:#eef1ff;
    color:#4f46e5;
}

#continueBtn {
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    font-weight:600;
    background:#ccc;
    color:white;
    cursor:not-allowed;
}

#continueBtn.active {
    background:linear-gradient(90deg,#6366f1,#7c3aed);
    cursor:pointer;
}

.action-group {
    display:flex;
    gap:10px;
    margin-top:20px;
}

.btn-back {
    flex:1;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#e5e7eb; /* abu soft */
    color:#333;
    font-weight:500;
    cursor:pointer;
    transition:0.2s;
}

.btn-back:hover {
    background:#d1d5db;
}

.payment-box {
    background:#eef1ff;
    padding:15px;
    border-radius:10px;
    margin:10px 0;
}

.info-box {
    margin:10px 0;
    font-size:14px;
}

.pay-option {
    border:1px solid #ddd;
    padding:12px;
    border-radius:10px;
    margin:10px 0;
    cursor:pointer;
}

.pay-option.active {
    border:2px solid #4f46e5;
    background:#eef1ff;
}

#confirmBtn {
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#ccc;
    color:white;
}

#confirmBtn.active {
    background:linear-gradient(90deg,#6366f1,#7c3aed);
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div>
        <div class="logo">🍦 Es Cream Treman</div>

        <div class="menu">
            <a href="#" class="active">Order</a>
            <a href="{{ route('discount') }}">Discount</a>
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
    <div class="main">

    <div class="title">Order Products</div>
    <div class="subtitle">Browse and add items to your cart</div>

    <div class="content">

    <!-- PRODUK -->
    <div class="products">
        <div class="product-card">
            <div class="product-top">🍦</div>

            <div class="product-body">
                <div style="color:#6c5cff; font-size:12px;">Desserts</div>
                <div class="product-title">Ice Cream</div>
                <div class="price">Rp 220.000</div>

                <form method="POST" action="/cart/add">
                @csrf
                <input type="hidden" name="id" value="1"> 
                <input type="hidden" name="produk" value="Ice Cream">
                <input type="hidden" name="harga" value="220000">

                <button type="submit" class="add-btn">+</button>
                </form>
            </div>
        </div>
    </div>

    <!-- CART -->
    <div class="cart">
        @php
        $cart = session('cart') ?? [];
        $total = 0;
        @endphp
        <div class="cart-title">🛒 Your Cart ({{ count($cart ?? []) }})</div>

        @if(count($cart ?? []) > 0)

    @foreach($cart as $id => $item)
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">

    <div>
        <span>{{ $item['produk'] }}</span>
        Rp {{ number_format($item['harga']) }}
    </div>

    <div style="display:flex; align-items:center; gap:5px;">
        <a href="/cart/min/{{ $id }}">-</a>

        <span>{{ $item['qty'] }}</span>

        <a href="/cart/plus/{{ $id }}">+</a>

        <a href="/cart/remove/{{ $id }}" style="color:red;">🗑️</a>
    </div>

</div>

@php
$total += $item['harga'] * $item['qty'];
@endphp

@endforeach

    <hr>

    <div>Subtotal: Rp {{ number_format($total) }}</div>
    <div><strong>Total: Rp {{ number_format($total) }}</strong></div>

    <button onclick="openModal()" class="checkout-btn">
    Proceed to Checkout
    </button>

@else

    <div class="empty">
        🛒<br><br>
        <p>Your cart is empty</p>
    </div>

@endif

<div id="checkoutModal" class="modal">
    <div class="modal-content">

        <h3>Delivery Options</h3>
        <p>Select Fulfillment Option</p>

        <div class="option-group">
            <button class="option-btn" onclick="selectMethod('pickup')" id="pickupBtn">Pickup</button>
            <button class="option-btn" onclick="selectMethod('delivery')" id="deliveryBtn">Delivery</button>
        </div>

        <div id="timeSection" style="display:none;">
            <p>Select Time Slot</p>

            <div class="time-grid">
                <button class="time-btn" onclick="selectTime('09:00 - 10:00', this)">09:00 - 10:00</button>
                <button class="time-btn" onclick="selectTime('10:00 - 11:00', this)">10:00 - 11:00</button>
                <button class="time-btn" onclick="selectTime('11:00 - 12:00', this)">11:00 - 12:00</button>
                <button class="time-btn" onclick="selectTime('12:00 - 13:00', this)">12:00 - 13:00</button>
            </div>
        </div>

        <div class="action-group">
            <button class="btn-back" onclick="closeModal()">Back</button>
            <button type="button" onclick="goToPayment(); return false;" id="continueBtn">
            Continue to Payment
            </button>
        </div>

    </div>
</div>
<div id="paymentModal" class="modal">
    <div class="modal-content">

        <div style="display:flex; justify-content:space-between;">
            <h3>Select Payment Method</h3>
            <span onclick="closePayment()" style="cursor:pointer;">✖</span>
        </div>

        <!-- TOTAL -->
        <div class="payment-box">
            <small>Total Amount</small>
            <h2 id="totalAmount">Rp 0</h2>
        </div>

        <!-- INFO -->
        <div class="info-box">
            <div>Type: <span id="payMethod"></span></div>
            <div>Time: <span id="payTime"></span></div>
        </div>

        <!-- OPTIONS -->
        <div class="pay-option" onclick="selectPayment('cash', this)">
            <strong>Pay on Spot</strong>

            <small>Cash payment at the counter</small>
        </div>

        <div class="pay-option">
            <strong>Pay with QRIS</strong>
            <small>Scan QR code to pay</small>
        </div>

        <!-- BUTTON -->
    <form method="POST" action="/order/store">
    @csrf

    <input type="hidden" name="metode" id="metode">
    <input type="hidden" name="waktu" id="waktu">
    <input type="hidden" name="total" value="{{ $total }}">
    

    <button type="submit">Confirm Payment</button>
    </form>

    </div>
</div>


<script>
let selectedMethod = null;
let selectedTime = null;

function openModal() {
    document.getElementById('checkoutModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('checkoutModal').style.display = 'none';
}

function selectMethod(method) {
    selectedMethod = method;

    document.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(method + 'Btn').classList.add('active');

    if (method === 'pickup') {
        document.getElementById('timeSection').style.display = 'block';
    } else {
        document.getElementById('timeSection').style.display = 'none';
        selectedTime = null;
    }

    checkReady();
}

function selectTime(time, el) {
    selectedTime = time;

    document.querySelectorAll('.time-btn').forEach(btn => btn.classList.remove('active'));
    el.classList.add('active');

    checkReady();
}

function checkReady() {
    const btn = document.getElementById('continueBtn');

    if (
        (selectedMethod === 'pickup' && selectedTime) ||
        (selectedMethod === 'delivery')
    ) {
        btn.disabled = false;
        btn.classList.add('active');
    } else {
        btn.disabled = true;
        btn.classList.remove('active');
    }
}

let selectedPayment = null;

function openPaymentModal() {
    
    let total = {{ session('cart') ? array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], session('cart'))) : 0 }};

    document.getElementById('totalAmount').innerText = "Rp " + total.toLocaleString();

    document.getElementById('payMethod').innerText = selectedMethod;
    document.getElementById('payTime').innerText = selectedTime ?? '-';

    document.getElementById('paymentModal').style.display = 'flex';
}

function closePayment() {
    document.getElementById('paymentModal').style.display = 'none';
}

function selectPayment(type, el) {
    selectedPayment = type;

    document.querySelectorAll('.pay-option').forEach(e => e.classList.remove('active'));
    el.classList.add('active');

    document.getElementById('confirmBtn').disabled = false;
    document.getElementById('confirmBtn').classList.add('active');
}

function goToPayment() {
    closeModal(); 
    openPaymentModal(); 
}

function selectPayment(type, el) {
    selectedPayment = type;

    document.getElementById('metode').value = type;
    document.getElementById('waktu').value = selectedTime;

    document.querySelectorAll('.pay-option').forEach(e => e.classList.remove('active'));
    el.classList.add('active');

    document.getElementById('confirmBtn').disabled = false;
    document.getElementById('confirmBtn').classList.add('active');
}
</script>

</body>
</html>