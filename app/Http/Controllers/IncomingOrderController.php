<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class IncomingOrderController extends Controller
{
    // tampilkan data ke admin
  public function index($type = null)
{
    // DATA CARD 
    $totalBaru = Order::where('status', 'baru')->count();
    $totalProses = Order::where('status', 'diproses')->count();
    $totalHariIni = Order::where('status', 'selesai')->count();

    // DATA LIST 
    if ($type == 'baru') {
    $orders = Order::where('status', 'baru')
        ->where('status', '!=', 'ditolak')
        ->get();
} 
    elseif ($type == 'aktif') {
    $orders = Order::whereIn('status', ['diproses','siap'])
        ->where('status', '!=', 'ditolak')
        ->get();
}
    else {
    $orders = Order::where('status', '!=', 'ditolak')->get();
}

    return view('incoming-orders', compact(
        'orders',
        'type',
        'totalBaru',
        'totalProses',
        'totalHariIni'
    ));
}

    // 🔥 INI YANG KAMU BELUM PUNYA
public function store(Request $request)
{
    $cart = session('cart') ?? [];

    if (count($cart) == 0) {
        return back();
    }

    $total = 0;
    $produkList = [];

    foreach ($cart as $item) {
        $produkList[] = $item['produk'] . ' x' . $item['qty'];
        $total += $item['harga'] * $item['qty'];
    }

    Order::create([
        'nama' => session('user'),
        'email' => session('email'),
        'produk' => implode(', ', $produkList), // 🔥 INI YANG FIX
        'total' => $total,
        'status' => 'baru',
        'metode' => $request->metode,
        'waktu' => $request->waktu,
        'alamat' => $request->metode == 'ambil'
        ? 'COD'
        : $request->alamat
        
    ]);

    session()->forget('cart');

    return redirect('/tracking');
}
    public function terima($id)
{
    $order = Order::find($id);
    $order->status = 'diproses';
    $order->save();

    return redirect()->route('incoming.orders');
}

    public function siap($id)
{
    $order = Order::find($id);
    $order->status = 'siap';
    $order->save();

    return redirect()->route('incoming.orders');
}

    public function selesai($id)
{
    $order = Order::find($id);
    $order->status = 'selesai';
    $order->save();

    return redirect()->route('incoming.orders');
}
    public function tolak($id)
{
    $order = Order::find($id);

    if ($order) {
        $order->status = 'ditolak';
        $order->save();
    }

    return redirect('/incoming-orders');
}
 
}