<!DOCTYPE html>
<html>
<head>
    <title>Lacak Pesanan</title>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script src="https://unpkg.com/laravel-echo/dist/echo.iife.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .card {
            width: 400px;
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
            margin-bottom: 20px;
        }

        .price-box h3 {
            margin: 0;
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
        @endphp

        <p>Status: <b id="trackingStatusText">{{ $order->status_label }}</b></p>

        <div class="price-box" id="priceBox">
            <h3 id="trackingTotal">Rp {{ number_format($order->total) }}</h3>
            <p id="trackingMeta">{{ $order->metode ?? '-' }} • {{ $order->waktu ?? '-' }}</p>
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
                    Siap Diambil
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

    const isRejected = order.status === 'ditolak';
    document.getElementById('rejectedBox').style.display = isRejected ? 'block' : 'none';
    document.getElementById('statusStepsWrapper').style.display = isRejected ? 'none' : 'block';
    document.getElementById('pickupBox').style.display = order.alamat === 'ambil ditempat' ? 'block' : 'none';

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
