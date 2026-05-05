<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sales Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script src="https://unpkg.com/laravel-echo/dist/echo.iife.js"></script>
<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f5f7fb;
    display: flex;
}

.sidebar {
    width: 220px;
    min-height: 100vh;
    background: white;
    padding: 20px;
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
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
    background: #eef1ff;
}

.profile {
    font-size: 14px;
}

.profile strong {
    display: block;
}

.profile small {
    color: #777;
}

.main {
    flex: 1;
    padding: 30px;
}

.header-title {
    font-size: 28px;
    font-weight: 700;
}

.header-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin-top: 6px;
}

.tabs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 24px;
}

.tab {
    padding: 10px 16px;
    border-radius: 999px;
    background: white;
    border: 1px solid #dbe3f0;
    color: #334155;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}

.tab.active {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
}

.filter-box {
    background: white;
    margin-top: 18px;
    padding: 18px;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

.field label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
}

.field input,
.field select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 14px;
}

.btn-submit {
    margin-top: 14px;
    border: none;
    background: #2563eb;
    color: white;
    padding: 10px 16px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
}

.cards {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-top: 22px;
}

.card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
}

.card-title {
    font-size: 13px;
    color: #6b7280;
}

.card-value {
    font-size: 28px;
    font-weight: 700;
    margin-top: 8px;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 4px;
}

.section-note {
    color: #6b7280;
    font-size: 13px;
    margin-bottom: 16px;
}

.chart-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-top: 22px;
}

.chart-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
}

.chart-wrap {
    position: relative;
    height: 320px;
}

@media (max-width: 980px) {
    body {
        display: block;
    }

    .sidebar {
        width: auto;
        min-height: auto;
    }

    .filter-grid,
    .cards,
    .chart-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>
<div class="sidebar">
    <div>
        <h3>🍦 Es Cream Treman</h3>
        <div class="menu">
            <a href="{{ route('dashboard') }}">Home</a>
            <a href="{{ route('incoming.orders') }}">Incoming Orders</a>
            <a href="{{ route('admin.discount.index') }}">Discount</a>
            <a href="{{ route('inventory.index') }}">Inventory</a>
            <a href="{{ route('sales.index') }}" class="active">Sales</a>
            <a href="{{ url('/admin/promo') }}">Promo Management</a>
        </div>
    </div>

    <div class="profile">
        <strong>{{ session('admin_user') }}</strong>
        <small>{{ session('admin_email') }}</small><br><br>
        <a href="{{ route('logout', ['role' => 'admin']) }}" style="color:red;">Logout</a>
    </div>
</div>

<div class="main">
    <div class="header-title">Sales Dashboard</div>
    <div class="header-subtitle">Analitik penjualan dinamis berdasarkan pesanan dengan status selesai.</div>

    <div class="tabs">
        <a href="{{ route('sales.index', ['filter' => 'today']) }}" class="tab {{ $filter === 'today' ? 'active' : '' }}">Hari Ini</a>
        <a href="{{ route('sales.index', ['filter' => 'yesterday']) }}" class="tab {{ $filter === 'yesterday' ? 'active' : '' }}">Kemarin</a>
        <a href="{{ route('sales.index', ['filter' => 'weekly']) }}" class="tab {{ $filter === 'weekly' ? 'active' : '' }}">Mingguan</a>
        <a href="{{ route('sales.index', ['filter' => 'monthly']) }}" class="tab {{ $filter === 'monthly' ? 'active' : '' }}">Bulanan</a>
        <a href="{{ route('sales.index', ['filter' => 'yearly']) }}" class="tab {{ $filter === 'yearly' ? 'active' : '' }}">Tahunan</a>
    </div>

    @if (in_array($filter, ['monthly', 'yearly']))
        <div class="filter-box">
            <form method="GET" action="{{ route('sales.index') }}">
                <input type="hidden" name="filter" value="{{ $filter }}">

                <div class="filter-grid">
                    @if ($filter === 'monthly')
                        <div class="field">
                            <label for="bulan">Bulan</label>
                            <select id="bulan" name="bulan">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ (int) $bulan === $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="field">
                            <label for="tahun">Tahun</label>
                            <input type="number" id="tahun" name="tahun" value="{{ $tahun }}">
                        </div>

                        <div class="field">
                            <label for="start_day">Tanggal Awal</label>
                            <select id="start_day" name="start_day" data-selected="{{ $startDay }}"></select>
                        </div>

                        <div class="field">
                            <label for="end_day">Tanggal Akhir</label>
                            <select id="end_day" name="end_day" data-selected="{{ $endDay }}"></select>
                        </div>
                    @endif

                    @if ($filter === 'yearly')
                        <div class="field">
                            <label for="tahun">Tahun</label>
                            <input type="number" id="tahun" name="tahun" value="{{ $tahun }}">
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn-submit">Terapkan Filter</button>
            </form>
        </div>
    @endif

    <div class="cards">
        <div class="card">
            <div class="card-title">Total Penjualan {{ $periodLabel }}</div>
            <div class="card-value" id="totalSales">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="card-title">Total Pesanan {{ $periodLabel }}</div>
            <div class="card-value" id="totalOrders">{{ $totalPesanan }}</div>
        </div>
        <div class="card">
            <div class="card-title" id="dailyOrdersLabel">Total Pesanan {{ $selectedDayLabel }}</div>
            <div class="card-value" id="dailyOrdersValue">{{ $totalPesananHarian }}</div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <div class="section-title">Grafik Penjualan</div>
            <div class="section-note">Total pendapatan berdasarkan periode terpilih.</div>
            <div class="chart-wrap">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="section-title">Grafik Pesanan</div>
            <div class="section-note">Jumlah pesanan selesai berdasarkan periode terpilih.</div>
            <div class="chart-wrap">
                <canvas id="orderChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
const salesChartData = @json($salesChart);
const orderChartData = @json($orderChart);
const salesDataUrl = @json(route('sales.data'));
const broadcastDriver = @json(env('BROADCAST_CONNECTION', 'log'));
const pusherKey = @json(env('VITE_PUSHER_APP_KEY', env('PUSHER_APP_KEY')));
const pusherCluster = @json(env('VITE_PUSHER_APP_CLUSTER', env('PUSHER_APP_CLUSTER')));
const pusherHost = @json(env('VITE_PUSHER_HOST', env('PUSHER_HOST', '127.0.0.1')));
const pusherPort = @json((int) env('VITE_PUSHER_PORT', env('PUSHER_PORT', 6001)));
const pusherScheme = @json(env('VITE_PUSHER_SCHEME', env('PUSHER_SCHEME', 'http')));

function rupiah(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
}

function updateDayOptions() {
    const monthSelect = document.getElementById('bulan');
    const yearInput = document.getElementById('tahun');
    const startDaySelect = document.getElementById('start_day');
    const endDaySelect = document.getElementById('end_day');

    if (!monthSelect || !yearInput || !startDaySelect || !endDaySelect) {
        return;
    }

    const month = parseInt(monthSelect.value, 10);
    const year = parseInt(yearInput.value, 10);

    if (!month || !year) {
        return;
    }

    const totalDays = new Date(year, month, 0).getDate();
    const startSelected = parseInt(startDaySelect.dataset.selected || startDaySelect.value || 1, 10);
    const endSelected = parseInt(endDaySelect.dataset.selected || endDaySelect.value || totalDays, 10);

    startDaySelect.innerHTML = '';
    endDaySelect.innerHTML = '';

    for (let day = 1; day <= totalDays; day++) {
        const startOption = document.createElement('option');
        startOption.value = day;
        startOption.textContent = day;
        if (day === Math.min(startSelected, totalDays)) {
            startOption.selected = true;
        }
        startDaySelect.appendChild(startOption);

        const endOption = document.createElement('option');
        endOption.value = day;
        endOption.textContent = day;
        if (day === Math.min(endSelected, totalDays)) {
            endOption.selected = true;
        }
        endDaySelect.appendChild(endOption);
    }

    startDaySelect.dataset.selected = startDaySelect.value;
    endDaySelect.dataset.selected = endDaySelect.value;
}

function buildAnalyticsUrl() {
    const currentParams = new URLSearchParams(window.location.search);

    if (!currentParams.get('filter')) {
        currentParams.set('filter', @json($filter));
    }

    return `${salesDataUrl}?${currentParams.toString()}`;
}

let salesChart;
let orderChart;

const salesCtx = document.getElementById('salesChart');
salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: salesChartData.map(item => item.label),
        datasets: [{
            label: 'Penjualan',
            data: salesChartData.map(item => item.total),
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.18)',
            fill: true,
            tension: 0.35,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ' - ' + rupiah(context.raw);
                    }
                }
            },
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return rupiah(value);
                    }
                }
            }
        }
    }
});

const orderCtx = document.getElementById('orderChart');
orderChart = new Chart(orderCtx, {
    type: 'bar',
    data: {
        labels: orderChartData.map(item => item.label),
        datasets: [{
            label: 'Pesanan',
            data: orderChartData.map(item => item.total),
            backgroundColor: '#16a34a',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ' - ' + context.raw + ' orders';
                    }
                }
            },
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

async function updateDashboard(data) {
    let payload = data;

    try {
        const response = await fetch(buildAnalyticsUrl(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            payload = await response.json();
        }
    } catch (error) {
        console.error('Realtime refresh failed:', error);
    }

    if (!payload) {
        return;
    }

    document.getElementById('totalSales').innerText = rupiah(payload.totalPenjualan ?? 0);
    document.getElementById('totalOrders').innerText = payload.totalPesanan ?? 0;
    document.getElementById('dailyOrdersLabel').innerText = 'Total Pesanan ' + (payload.selectedDayLabel ?? 'Hari Ini');
    document.getElementById('dailyOrdersValue').innerText = payload.totalPesananHarian ?? 0;

    salesChart.data.labels = (payload.salesChart ?? []).map(item => item.label);
    salesChart.data.datasets[0].data = (payload.salesChart ?? []).map(item => item.total);
    salesChart.update();

    orderChart.data.labels = (payload.orderChart ?? []).map(item => item.label);
    orderChart.data.datasets[0].data = (payload.orderChart ?? []).map(item => item.total);
    orderChart.update();
}

updateDayOptions();

const bulanInput = document.getElementById('bulan');
const tahunInput = document.getElementById('tahun');
const startDayInput = document.getElementById('start_day');
const endDayInput = document.getElementById('end_day');

if (bulanInput) {
    bulanInput.addEventListener('change', updateDayOptions);
}

if (tahunInput) {
    tahunInput.addEventListener('input', updateDayOptions);
}

if (startDayInput) {
    startDayInput.addEventListener('change', function () {
        this.dataset.selected = this.value;
    });
}

if (endDayInput) {
    endDayInput.addEventListener('change', function () {
        this.dataset.selected = this.value;
    });
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

    window.Echo.channel('orders')
        .listen('OrderUpdated', (e) => {
            updateDashboard(e);
        });
}
</script>
</body>
</html>
