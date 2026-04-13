<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
{
    if (!session('is_admin')) {
        return redirect('/login');
    }

    $totalSales = Order::where('status', 'selesai')->sum('total');
    $totalPesanan = Order::where('status', 'selesai')->count();
    $pesananAktif = Order::whereIn('status', ['baru','diproses','siap'])->count();
    $totalPelanggan = User::count();

    return view('dashboard', compact(
        'totalSales',
        'totalPesanan',
        'pesananAktif',
        'totalPelanggan'
    ));
}
}