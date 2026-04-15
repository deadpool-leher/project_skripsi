<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SalesAnalyticsService
{
    public function getAnalytics(array $filters = []): array
    {
        $filter = $filters['filter'] ?? 'today';
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $bulan = (int) ($filters['bulan'] ?? $today->month);
        $tahun = (int) ($filters['tahun'] ?? $today->year);
        $baseQuery = Order::query()->where('status', 'selesai');
        $chartMode = 'day';
        $periodLabel = 'Hari Ini';
        $yearForChart = $tahun;
        $selectedDayLabel = 'Hari Ini';
        $totalPesananHarian = 0;
        $startDay = (int) ($filters['start_day'] ?? 1);
        $endDay = (int) ($filters['end_day'] ?? Carbon::create($tahun, $bulan, 1)->endOfMonth()->day);

        switch ($filter) {
            case 'yesterday':
                $start = $yesterday->copy()->startOfDay();
                $end = $yesterday->copy()->endOfDay();
                $chartMode = 'hour';
                $periodLabel = 'Kemarin';
                $selectedDayLabel = 'Kemarin';
                break;

            case 'weekly':
                $start = $today->copy()->subDays(6)->startOfDay();
                $end = $today->copy()->endOfDay();
                $chartMode = 'day';
                $periodLabel = '7 Hari Terakhir';
                $selectedDayLabel = 'Hari Terpilih';
                break;

            case 'monthly':
                $daysInMonth = Carbon::create($tahun, $bulan, 1)->daysInMonth;
                $startDay = max(1, min($startDay, $daysInMonth));
                $endDay = max(1, min($endDay, $daysInMonth));

                if ($startDay > $endDay) {
                    [$startDay, $endDay] = [$endDay, $startDay];
                }

                $start = Carbon::create($tahun, $bulan, $startDay)->startOfDay();
                $end = Carbon::create($tahun, $bulan, $endDay)->endOfDay();
                $chartMode = 'day';
                $periodLabel = 'Bulanan';
                $selectedDayLabel = 'Tanggal ' . $start->translatedFormat('d M Y');
                break;

            case 'yearly':
                $start = Carbon::create($tahun, 1, 1)->startOfYear();
                $end = $start->copy()->endOfYear();
                $chartMode = 'month';
                $periodLabel = 'Tahunan';
                $selectedDayLabel = 'Hari Terpilih';
                break;

            case 'today':
            default:
                $filter = 'today';
                $start = $today->copy()->startOfDay();
                $end = $today->copy()->endOfDay();
                $chartMode = 'hour';
                $periodLabel = 'Hari Ini';
                $selectedDayLabel = 'Hari Ini';
                break;
        }

        $orders = (clone $baseQuery)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        $totalPenjualan = (int) $orders->sum('total');
        $totalPesanan = $orders->count();
        $rataRata = $totalPesanan > 0 ? (int) round($totalPenjualan / $totalPesanan) : 0;

        if (in_array($filter, ['today', 'yesterday'], true)) {
            $totalPesananHarian = $totalPesanan;
        } elseif ($filter === 'monthly') {
            $totalPesananHarian = Order::where('status', 'selesai')
                ->whereDate('created_at', $start->copy()->toDateString())
                ->count();
        }

        $salesChart = $this->buildChartData($orders, $chartMode, $start, $end, $yearForChart, 'sales');
        $orderChart = $this->buildChartData($orders, $chartMode, $start, $end, $yearForChart, 'orders');

        return [
            'filter' => $filter,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'startDay' => $startDay,
            'endDay' => $endDay,
            'periodLabel' => $periodLabel,
            'selectedDayLabel' => $selectedDayLabel,
            'totalPenjualan' => $totalPenjualan,
            'totalPesanan' => $totalPesanan,
            'rataRata' => $rataRata,
            'totalPesananHarian' => $totalPesananHarian,
            'salesChart' => $salesChart,
            'orderChart' => $orderChart,
        ];
    }

    private function buildChartData($orders, string $mode, Carbon $start, Carbon $end, int $year, string $type): array
    {
        $grouped = [];

        foreach ($orders as $order) {
            $date = Carbon::parse($order->created_at);

            if ($mode === 'hour') {
                $key = $date->format('H:00');
            } elseif ($mode === 'month') {
                $key = $date->format('m');
            } else {
                $key = $date->format('Y-m-d');
            }

            if (!isset($grouped[$key])) {
                $grouped[$key] = ['total' => 0, 'count' => 0];
            }

            $grouped[$key]['total'] += (int) $order->total;
            $grouped[$key]['count']++;
        }

        $chart = [];

        if ($mode === 'hour') {
            for ($hour = 0; $hour < 24; $hour++) {
                $label = str_pad((string) $hour, 2, '0', STR_PAD_LEFT) . ':00';
                $chart[] = [
                    'label' => $label,
                    'total' => $type === 'sales' ? ($grouped[$label]['total'] ?? 0) : ($grouped[$label]['count'] ?? 0),
                ];
            }

            return $chart;
        }

        if ($mode === 'month') {
            for ($month = 1; $month <= 12; $month++) {
                $key = str_pad((string) $month, 2, '0', STR_PAD_LEFT);
                $label = Carbon::create($year, $month, 1)->translatedFormat('M');
                $chart[] = [
                    'label' => $label,
                    'total' => $type === 'sales' ? ($grouped[$key]['total'] ?? 0) : ($grouped[$key]['count'] ?? 0),
                ];
            }

            return $chart;
        }

        foreach (CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay()) as $date) {
            $key = $date->format('Y-m-d');
            $chart[] = [
                'label' => $date->format('d M'),
                'total' => $type === 'sales' ? ($grouped[$key]['total'] ?? 0) : ($grouped[$key]['count'] ?? 0),
            ];
        }

        return $chart;
    }
}
