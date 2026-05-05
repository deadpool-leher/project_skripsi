<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan</title>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script src="https://unpkg.com/laravel-echo/dist/echo.iife.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .card {
            width: min(420px, calc(100vw - 32px));
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
            margin-bottom: 16px;
        }

        .price-box h3 {
            margin: 0;
        }

        .price-box p,
        .price-box small {
            display: block;
            margin: 6px 0 0;
        }

        .info-panel {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px;
            margin: 10px 0 16px;
            color: #334155;
            font-size: 14px;
        }

        .info-panel div + div {
            margin-top: 6px;
        }

        .qris-box {
            margin: 14px 0;
            padding: 14px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .qris-box h4 {
            margin: 0 0 6px;
        }

        .qris-box p {
            margin: 0 0 12px;
            color: #475569;
            font-size: 14px;
        }

        .qris-image {
            width: 100%;
            border-radius: 12px;
            display: block;
            background: white;
        }

        .alert {
            padding: 10px 12px;
            border-radius: 10px;
            margin: 10px 0 14px;
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

        .proof-form {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
        }

        .proof-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        .proof-form input[type="file"] {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            background: white;
        }

        .proof-form button {
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            border: none;
            border-radius: 10px;
            background: #16a34a;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .proof-preview {
            margin-top: 12px;
            font-size: 14px;
            color: #334155;
        }

        .proof-preview img {
            width: 100%;
            max-height: 260px;
            object-fit: contain;
            display: block;
            margin-top: 8px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: white;
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
            $isPickup = $order->alamat == 'ambil ditempat';
        @endphp

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->has('payment_proof'))
            <div class="alert alert-error">{{ $errors->first('payment_proof') }}</div>
        @endif

        <p>Status: <b id="trackingStatusText">{{ $order->status_label }}</b></p>

        <div class="price-box" id="priceBox">
            <h3 id="trackingTotal">Rp {{ number_format($order->total, 0, ',', '.') }}</h3>
            <p id="trackingMeta">{{ $order->metode ?? '-' }} • {{ $order->waktu ?? '-' }}</p>
            <small id="trackingPaymentLabel">Pembayaran: {{ strtoupper($order->metode ?? '-') }}</small>
            @if(($order->discount_amount ?? 0) > 0)
                <small id="trackingDiscountInfo">{{ $order->discount_code ? $order->discount_code . ' dipakai, ' : '' }}hemat Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</small>
            @else
                <small id="trackingDiscountInfo" style="display:none;"></small>
            @endif
        </div>

        <div class="info-panel">
            <div>Subtotal: <span id="trackingSubtotal">Rp {{ number_format($order->subtotal ?? $order->total, 0, ',', '.') }}</span></div>
            <div id="trackingDiscountRow" style="{{ ($order->discount_amount ?? 0) > 0 ? '' : 'display:none;' }}">Diskon: <span id="trackingDiscountAmount">- Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}</span></div>
        </div>

        <div class="qris-box" id="qrisBox" style="{{ ($order->metode ?? '') === 'qris' ? '' : 'display:none;' }}">
            <h4>Bayar dengan QRIS</h4>
            <p>Scan kode berikut untuk menyelesaikan pembayaran.</p>
            <img src="{{ asset('gambar/Kode_QRIS_Escreamtreman.png') }}" alt="Kode QRIS Es Cream Treman" class="qris-image">

            <form class="proof-form" method="POST" action="{{ route('tracking.payment-proof', $order->id) }}" enctype="multipart/form-data">
                @csrf
                <label for="payment_proof">Upload bukti pembayaran</label>
                <input type="file" id="payment_proof" name="payment_proof" accept="image/*" required>
                <button type="submit">{{ $order->payment_proof ? 'Ganti Bukti Pembayaran' : 'Kirim Bukti Pembayaran' }}</button>
            </form>

            @if($order->payment_proof)
                <div class="proof-preview" id="proofPreview">
                    Bukti pembayaran sudah dikirim.
                    <a href="{{ asset($order->payment_proof) }}" target="_blank">Lihat ukuran penuh</a>
                    <img src="{{ asset($order->payment_proof) }}" alt="Bukti pembayaran">
                </div>
            @else
                <div class="proof-preview" id="proofPreview" style="display:none;"></div>
            @endif
        </div>

        <div id="pickupBox" style="margin:10px 0; {{ $order->alamat == 'ambil ditempat' ? '' : 'display:none;' }}">
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
                Lihat Lokasi Pickup
            </a>
        </div>

        <div id="rejectedBox" style="
            background:#fee2e2;
            color:#991b1b;
            padding:12px;
            border-radius:10px;
            margin:10px 0;
            font-weight:500;
            {{ $order->status == 'ditolak' ? '' : 'display:none;' }}
        ">
            Pesanan Anda Ditolak
            <br>
            Silakan lakukan pemesanan ulang.
        </div>

        <div id="statusStepsWrapper" style="{{ $order->status == 'ditolak' ? 'display:none;' : '' }}">
            <div class="status-title">Status Pesanan</div>
            <div style="display:flex; flex-direction:column; gap:10px;">
                <div class="step {{ $status == 'baru' ? 'active' : '' }}" id="stepBaru">
                    Menunggu Konfirmasi
                </div>

                <div class="step {{ in_array($status, ['diproses','siap','selesai']) ? 'active' : '' }}" id="stepKonfirmasi">
                    Order Dikonfirmasi
                </div>

                <div class="step {{ in_array($status, ['diproses','siap','selesai']) ? 'active' : '' }}" id="stepDiproses">
                    Sedang Diproses
                </div>

                <div class="step {{ in_array($status, ['siap','selesai']) ? 'active' : '' }}" id="stepSiap">
                    {{ $isPickup ? 'Siap Diambil' : 'Sedang Diantar' }}
                </div>

                <div class="step {{ $status == 'selesai' ? 'active' : '' }}" id="stepSelesai">
                    Selesai
                </div>
            </div>
        </div>

        <button class="btn-close" onclick="window.location.href='/customer'">
            Tutup
        </button>
    @else
        <p>Tidak ada pesanan</p>
    @endif
</div>

@if($order)
<script>
const orderId = {{ $order->id }};
const broadcastDriver = @json(env('BROADCAST_CONNECTION', 'log'));
const pusherKey = @json(env('VITE_PUSHER_APP_KEY', env('PUSHER_APP_KEY')));
const pusherCluster = @json(env('VITE_PUSHER_APP_CLUSTER', env('PUSHER_APP_CLUSTER')));
const pusherHost = @json(env('VITE_PUSHER_HOST', env('PUSHER_HOST', '127.0.0.1')));
const pusherPort = @json((int) env('VITE_PUSHER_PORT', env('PUSHER_PORT', 6001)));
const pusherScheme = @json(env('VITE_PUSHER_SCHEME', env('PUSHER_SCHEME', 'http')));
const trackingDataUrl = @json(url('/tracking/data')) + '/' + orderId;

function formatRupiah(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value || 0);
}

function formatPaymentLabel(method) {
    return method ? String(method).toUpperCase() : '-';
}

function setStepState(id, active) {
    const element = document.getElementById(id);
    if (element) {
        element.classList.toggle('active', active);
    }
}

function updateTracking(order) {
    document.getElementById('trackingStatusText').innerText = order.status_label || '-';
    document.getElementById('trackingTotal').innerText = formatRupiah(order.total);
    document.getElementById('trackingMeta').innerText = `${order.metode || '-'} • ${order.waktu || '-'}`;
    document.getElementById('trackingPaymentLabel').innerText = `Pembayaran: ${formatPaymentLabel(order.metode)}`;
    document.getElementById('trackingSubtotal').innerText = formatRupiah(order.subtotal || order.total);

    const discountAmount = order.discount_amount || 0;
    const discountCode = order.discount_code ? `${order.discount_code} dipakai, ` : '';
    document.getElementById('trackingDiscountAmount').innerText = '- ' + formatRupiah(discountAmount);
    document.getElementById('trackingDiscountRow').style.display = discountAmount > 0 ? 'block' : 'none';

    const discountInfo = document.getElementById('trackingDiscountInfo');
    discountInfo.style.display = discountAmount > 0 ? 'block' : 'none';
    discountInfo.innerText = discountAmount > 0 ? `${discountCode}hemat ${formatRupiah(discountAmount)}` : '';

    const isRejected = order.status === 'ditolak';
    document.getElementById('rejectedBox').style.display = isRejected ? 'block' : 'none';
    document.getElementById('statusStepsWrapper').style.display = isRejected ? 'none' : 'block';
    document.getElementById('pickupBox').style.display = order.alamat === 'ambil ditempat' ? 'block' : 'none';
    document.getElementById('qrisBox').style.display = order.metode === 'qris' ? 'block' : 'none';
    document.getElementById('stepSiap').innerText = order.alamat === 'ambil ditempat' ? 'Siap Diambil' : 'Sedang Diantar';

    setStepState('stepBaru', order.status === 'baru');
    setStepState('stepKonfirmasi', ['diproses', 'siap', 'selesai'].includes(order.status));
    setStepState('stepDiproses', ['diproses', 'siap', 'selesai'].includes(order.status));
    setStepState('stepSiap', ['siap', 'selesai'].includes(order.status));
    setStepState('stepSelesai', order.status === 'selesai');
}

async function refreshTracking() {
    try {
        const response = await fetch(trackingDataUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            return;
        }

        const order = await response.json();
        updateTracking(order);
    } catch (error) {
        console.error('Tracking refresh failed:', error);
    }
}

const EchoFactory = window.Echo?.default || window.Echo || window.LaravelEcho;

if (broadcastDriver === 'pusher' && EchoFactory && window.Pusher && pusherKey) {
    window.Pusher = window.Pusher || Pusher;
    window.Echo = new EchoFactory({
        broadcaster: 'pusher',
        key: pusherKey,
        cluster: pusherCluster,
        wsHost: pusherHost,
        wsPort: pusherPort,
        wssPort: pusherPort,
        forceTLS: pusherScheme === 'https',
        enabledTransports: ['ws', 'wss'],
        disableStats: true,
        namespace: ''
    });

    window.Echo.channel(`orders.${orderId}`)
        .listen('CustomerOrderUpdated', (e) => {
            updateTracking(e);
        });
}

setInterval(refreshTracking, 3000);
</script>
@endif

</body>
</html>
