<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f5f7fb;
    display: flex;
}

.sidebar {
    width: 230px;
    min-height: 100vh;
    background: white;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-right: 1px solid #e5e7eb;
}

.logo {
    font-weight: 600;
    margin-bottom: 20px;
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
    border: 2px solid #6366f1;
}

.menu a:hover {
    background: #f3f4f6;
}

.main {
    flex: 1;
    padding: 30px;
}

.title {
    font-size: 22px;
    font-weight: 600;
}

.subtitle {
    color: #777;
    font-size: 14px;
    margin-bottom: 20px;
}

.alert {
    padding: 12px 14px;
    border-radius: 12px;
    margin-bottom: 16px;
    font-size: 14px;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
}

.layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 320px;
    gap: 24px;
    align-items: start;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
}

.product-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    overflow: hidden;
}

.product-top {
    background: linear-gradient(135deg, #e0e7ff, #dbeafe);
    text-align: center;
    padding: 0;
    height: 170px;
}

.product-top img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.product-top span {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 600;
    color: #4338ca;
}

.product-body {
    padding: 16px;
}

.product-category {
    color: #6c5cff;
    font-size: 12px;
    margin-bottom: 6px;
}

.product-title {
    font-weight: 600;
    margin-bottom: 6px;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-top: 12px;
}

.price {
    font-weight: 600;
}

.add-btn {
    background: #6c5cff;
    color: white;
    border: none;
    border-radius: 999px;
    width: 34px;
    height: 34px;
    cursor: pointer;
    font-size: 18px;
}

.add-btn:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
}

.empty-products,
.cart {
    background: white;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.cart-title {
    font-weight: 600;
    margin-bottom: 18px;
}

.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 14px;
}

.cart-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.cart-actions a {
    text-decoration: none;
}

.coupon-box {
    margin: 16px 0;
    padding: 14px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
}

.coupon-label {
    display: block;
    font-size: 13px;
    margin-bottom: 8px;
    color: #475569;
}

.coupon-row {
    display: flex;
    gap: 8px;
}

.coupon-input,
#alamat {
    width: 100%;
    box-sizing: border-box;
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    font-family: inherit;
}

.coupon-button {
    border: none;
    border-radius: 10px;
    background: #4338ca;
    color: white;
    padding: 10px 14px;
    cursor: pointer;
    font-weight: 600;
}

.coupon-help {
    color: #64748b;
    font-size: 12px;
    margin-top: 8px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-top: 8px;
}

.discount-row {
    color: #16a34a;
    font-weight: 600;
}

.empty {
    text-align: center;
    margin-top: 24px;
    color: #aaa;
}

.profile {
    font-size: 14px;
}

.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    justify-content: center;
    align-items: center;
    padding: 16px;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 15px;
    width: 100%;
    max-width: 360px;
}

.option-group button,
.time-grid button {
    padding: 10px;
    margin: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
    cursor: pointer;
}

.option-btn {
    flex: 1;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background: #f5f5f5;
    cursor: pointer;
    transition: 0.2s;
}

.option-btn.active {
    border: 2px solid #4f46e5;
    background: #eef1ff;
    color: #4f46e5;
}

.time-btn {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
    background: #f5f5f5;
    cursor: pointer;
}

.time-btn.active {
    border: 2px solid #4f46e5;
    background: #eef1ff;
    color: #4f46e5;
}

#continueBtn,
#confirmBtn,
.checkout-btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    color: white;
}

#continueBtn,
#confirmBtn {
    background: #ccc;
}

#continueBtn.active,
#confirmBtn.active,
.checkout-btn {
    background: linear-gradient(90deg,#6366f1,#7c3aed);
}

.checkout-btn {
    margin-top: 14px;
    cursor: pointer;
}

.action-group {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn-back {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: #e5e7eb;
    color: #333;
    font-weight: 500;
    cursor: pointer;
}

.payment-box {
    background: #eef1ff;
    padding: 15px;
    border-radius: 10px;
    margin: 10px 0;
}

.info-box {
    margin: 10px 0;
    font-size: 14px;
}

.pay-option {
    border: 1px solid #ddd;
    padding: 12px;
    border-radius: 10px;
    margin: 10px 0;
    cursor: pointer;
}

.pay-option.active {
    border: 2px solid #4f46e5;
    background: #eef1ff;
}

.pay-option strong,
.pay-option small {
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

    .layout {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>
@php
    $claimedCoupons = $availableDiscounts->mapWithKeys(function ($userDiscount) {
        return [
            strtoupper($userDiscount->discount->code) => [
                'code' => strtoupper($userDiscount->discount->code),
                'name' => $userDiscount->discount->name,
                'value' => (int) $userDiscount->discount->value,
            ],
        ];
    });
@endphp

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

<div class="main">
    <div class="title">Order Products</div>
    <div class="subtitle">Browse and add items to your cart</div>

    @if($errors->has('discount_code'))
        <div class="alert alert-error">{{ $errors->first('discount_code') }}</div>
    @endif

    <div class="layout">
        <div>
            <div class="products-grid">
                @forelse ($products as $product)
                    <div class="product-card">
                        <div class="product-top">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama }}">
                            @else
                                <span>{{ strtoupper(substr($product->nama, 0, 1)) }}</span>
                            @endif
                        </div>

                        <div class="product-body">
                            <div class="product-title">{{ $product->nama }}</div>
                            <div class="price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>

                            <div class="product-meta">
                                <form method="POST" action="/cart/add">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                    <button type="submit" class="add-btn" {{ $product->stok < 1 ? 'disabled' : '' }}>+</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-products">
                        Belum ada produk tersedia.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="cart">
            @php
                $cart = session('cart') ?? [];
                $subtotal = 0;
            @endphp

            <div class="cart-title">Your Cart ({{ count($cart) }})</div>

            @if(count($cart) > 0)
                @foreach($cart as $id => $item)
                    <div class="cart-item">
                        <div>
                            <div>{{ $item['produk'] }}</div>
                            <small>Rp {{ number_format($item['harga'], 0, ',', '.') }}</small>
                        </div>

                        <div class="cart-actions">
                            <a href="/cart/min/{{ $id }}">-</a>
                            <span>{{ $item['qty'] }}</span>
                            <a href="/cart/plus/{{ $id }}">+</a>
                            <a href="/cart/remove/{{ $id }}" style="color:red;">Hapus</a>
                        </div>
                    </div>

                    @php
                        $subtotal += $item['harga'] * $item['qty'];
                    @endphp
                @endforeach

                <hr>

                <div class="coupon-box">
                    <label class="coupon-label" for="couponCodeInput">Masukkan kupon diskon yang sudah diklaim</label>
                    <div class="coupon-row">
                        <input
                            type="text"
                            id="couponCodeInput"
                            class="coupon-input"
                            list="claimedCouponList"
                            placeholder="Contoh: HEMAT10"
                            autocomplete="off"
                        >
                        <button type="button" class="coupon-button" onclick="applyCoupon()">Pakai</button>
                    </div>
                    <datalist id="claimedCouponList">
                        @foreach($availableDiscounts as $userDiscount)
                            <option value="{{ strtoupper($userDiscount->discount->code) }}">
                                {{ $userDiscount->discount->name }} ({{ $userDiscount->discount->value }}%)
                            </option>
                        @endforeach
                    </datalist>
                    <div class="coupon-help" id="couponMessage">
                        @if($availableDiscounts->isNotEmpty())
                            Kupon tersedia: {{ $availableDiscounts->pluck('discount.code')->map(fn ($code) => strtoupper($code))->join(', ') }}
                        @else
                            Belum ada kupon yang diklaim.
                        @endif
                    </div>
                </div>

                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="cartSubtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row discount-row" id="cartDiscountRow" style="display:none;">
                    <span>Diskon:</span>
                    <span id="cartDiscountAmount">- Rp 0</span>
                </div>
                <div class="summary-row">
                    <strong>Total:</strong>
                    <strong id="cartFinalTotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                </div>

                <button onclick="openModal()" class="checkout-btn">Proceed to Checkout</button>
            @else
                <div class="empty">
                    <p>Your cart is empty</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div id="checkoutModal" class="modal">
    <div class="modal-content">
        <h3>Delivery Options</h3>
        <p>Select Fulfillment Option</p>

        <div class="option-group">
            <button class="option-btn" onclick="selectMethod('pickup')" id="pickupBtn" type="button">Pickup</button>
            <button class="option-btn" onclick="selectMethod('delivery')" id="deliveryBtn" type="button">Delivery</button>
        </div>

        <div id="timeSection" style="display:none;">
            <p>Select Time Slot</p>

            <div class="time-grid">
                <button class="time-btn" onclick="selectTime('09:00 - 10:00', this)" type="button">09:00 - 10:00</button>
                <button class="time-btn" onclick="selectTime('10:00 - 11:00', this)" type="button">10:00 - 11:00</button>
                <button class="time-btn" onclick="selectTime('11:00 - 12:00', this)" type="button">11:00 - 12:00</button>
                <button class="time-btn" onclick="selectTime('12:00 - 13:00', this)" type="button">12:00 - 13:00</button>
            </div>
        </div>

        <div id="alamatSection" style="display:none; margin-top:10px;">
            <button onclick="useLocation()" type="button" style="width:100%; padding:10px; border:none; border-radius:10px; background:linear-gradient(90deg,#6366f1,#7c3aed); color:white; margin-bottom:10px;">
                Gunakan Lokasi Saya
            </button>

            <textarea name="alamat" id="alamat" placeholder="Masukkan alamat lengkap dan nomor telfon" style="width:100%; padding:10px; border-radius:10px;"></textarea>
        </div>

        <div class="action-group">
            <button class="btn-back" onclick="closeModal()" type="button">Back</button>
            <button type="button" onclick="goToPayment(); return false;" id="continueBtn">Continue to Payment</button>
        </div>
    </div>
</div>

<div id="paymentModal" class="modal">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between;">
            <h3>Select Payment Method</h3>
            <span onclick="closePayment()" style="cursor:pointer;">x</span>
        </div>

        <div class="payment-box">
            <small>Total Amount</small>
            <h2 id="totalAmount">Rp 0</h2>
            <small id="discountInfo">Masukkan kupon di cart jika ingin digunakan</small>
        </div>

        <div class="info-box">
            <div>Type: <span id="payMethod"></span></div>
            <div>Time: <span id="payTime"></span></div>
            <div>Payment: <span id="payPayment">-</span></div>
        </div>

        <div class="pay-option" onclick="selectPayment('cash', this)">
            <strong>Pay on Spot</strong>
            <small>Cash payment at the counter</small>
        </div>

        <div class="pay-option" onclick="selectPayment('qris', this)">
            <strong>Pay with QRIS</strong>
            <small>Scan QR code to pay</small>
        </div>

        <form method="POST" action="/order/store">
            @csrf
            <input type="hidden" name="metode" id="metode">
            <input type="hidden" name="tipe_pengiriman" id="tipe_pengiriman">
            <input type="hidden" name="waktu" id="waktu">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="alamat" id="alamatHidden">
            <input type="hidden" name="discount_code" id="discountCodeHidden">

            <button type="submit" id="confirmBtn">Confirm Payment</button>
        </form>
    </div>
</div>

<script>
let selectedMethod = null;
let selectedTime = null;
let selectedPayment = null;
let activeCoupon = null;
let baseTotal = {{ $subtotal }};
const claimedCoupons = @json($claimedCoupons);

function openModal() {
    document.getElementById('checkoutModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('checkoutModal').style.display = 'none';
}

function selectMethod(method) {
    selectedMethod = method;
    document.getElementById('tipe_pengiriman').value = method;
    document.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(method + 'Btn').classList.add('active');
    selectedTime = null;
    document.querySelectorAll('.time-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('timeSection').style.display = 'block';
    document.getElementById('alamatSection').style.display = method === 'delivery' ? 'block' : 'none';
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

    if (selectedMethod && selectedTime) {
        btn.disabled = false;
        btn.classList.add('active');
    } else {
        btn.disabled = true;
        btn.classList.remove('active');
    }
}

function openPaymentModal() {
    updateDiscountPreview();
    document.getElementById('payMethod').innerText = selectedMethod === 'pickup' ? 'pickup' : 'delivery';
    document.getElementById('payTime').innerText = selectedTime || '-';
    document.getElementById('payPayment').innerText = selectedPayment ? formatPaymentLabel(selectedPayment) : '-';
    document.getElementById('paymentModal').style.display = 'flex';
}

function closePayment() {
    document.getElementById('paymentModal').style.display = 'none';
}

function goToPayment() {
    closeModal();
    openPaymentModal();

    let alamat = document.getElementById('alamat').value;
    if (selectedMethod === 'pickup') {
        alamat = 'ambil ditempat';
    }

    document.getElementById('alamatHidden').value = alamat;
}

function selectPayment(type, el) {
    selectedPayment = type;
    document.getElementById('metode').value = type;
    document.getElementById('waktu').value = selectedTime;
    document.querySelectorAll('.pay-option').forEach(e => e.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('payPayment').innerText = formatPaymentLabel(type);
    document.getElementById('confirmBtn').disabled = false;
    document.getElementById('confirmBtn').classList.add('active');
}

function formatPaymentLabel(type) {
    if (type === 'qris') {
        return 'QRIS';
    }

    if (type === 'cash') {
        return 'Cash';
    }

    return type || '-';
}

function applyCoupon() {
    const input = document.getElementById('couponCodeInput');
    const couponMessage = document.getElementById('couponMessage');
    const code = input.value.trim().toUpperCase();

    if (!code) {
        activeCoupon = null;
        couponMessage.innerText = 'Masukkan kode kupon yang sudah Anda klaim.';
        updateDiscountPreview();
        return;
    }

    if (!claimedCoupons[code]) {
        activeCoupon = null;
        couponMessage.innerText = 'Kupon tidak ditemukan atau belum diklaim.';
        updateDiscountPreview();
        return;
    }

    activeCoupon = claimedCoupons[code];
    input.value = activeCoupon.code;
    couponMessage.innerText = `${activeCoupon.code} aktif. Diskon ${activeCoupon.value}% siap dipakai.`;
    updateDiscountPreview();
}

function updateDiscountPreview() {
    const discountValue = activeCoupon ? parseInt(activeCoupon.value || '0', 10) : 0;
    const discountCode = activeCoupon ? activeCoupon.code : '';
    const discountAmount = Math.floor(baseTotal * discountValue / 100);
    const finalTotal = Math.max(baseTotal - discountAmount, 0);

    const cartSubtotal = document.getElementById('cartSubtotal');
    const cartFinalTotal = document.getElementById('cartFinalTotal');
    const cartDiscountAmount = document.getElementById('cartDiscountAmount');
    const cartDiscountRow = document.getElementById('cartDiscountRow');

    if (cartSubtotal && cartFinalTotal && cartDiscountAmount && cartDiscountRow) {
        cartSubtotal.innerText = 'Rp ' + baseTotal.toLocaleString();
        cartFinalTotal.innerText = 'Rp ' + finalTotal.toLocaleString();
        cartDiscountAmount.innerText = '- Rp ' + discountAmount.toLocaleString();
        cartDiscountRow.style.display = discountAmount > 0 ? 'flex' : 'none';
    }

    document.getElementById('totalAmount').innerText = 'Rp ' + finalTotal.toLocaleString();
    document.getElementById('discountInfo').innerText = discountCode
        ? `${discountCode} dipakai, hemat Rp ${discountAmount.toLocaleString()}`
        : 'Masukkan kupon di cart jika ingin digunakan';
    document.getElementById('discountCodeHidden').value = discountCode;
}

function useLocation() {
    navigator.geolocation.getCurrentPosition(async (pos) => {
        let lat = pos.coords.latitude;
        let lon = pos.coords.longitude;
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lon;

        try {
            let res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
            let data = await res.json();
            document.getElementById('alamat').value = data.display_name;
        } catch {
            alert('Gagal ambil alamat');
        }
    });
}

document.getElementById('continueBtn').disabled = true;
document.getElementById('confirmBtn').disabled = true;
updateDiscountPreview();
</script>

</body>
</html>
