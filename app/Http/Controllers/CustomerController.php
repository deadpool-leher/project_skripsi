<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer');
    }

    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $id = $request->id;

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'produk' => $request->produk,
                'harga' => $request->harga,
                'qty' => 1
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back();
    }

    public function plus($id)
    {
        $cart = session('cart');

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        }

        session(['cart' => $cart]);

        return back();
    }

    public function min($id)
    {
        $cart = session('cart');

        if (isset($cart[$id])) {
            $cart[$id]['qty']--;

            if ($cart[$id]['qty'] <= 0) {
                unset($cart[$id]);
            }
        }

        session(['cart' => $cart]);

        return back();
    }

    public function remove($id)
    {
        $cart = session('cart');

        unset($cart[$id]);

        session(['cart' => $cart]);

        return back();
    }

    public function checkout(Request $request)
{
    $cart = session('cart', []);

    if (empty($cart)) {
        return back();
    }

    $total = 0;

    foreach ($cart as $item) {
        $total += $item['harga'] * $item['qty'];
    }

    // SIMPAN KE DATABASE 
    Order::create([
    'nama' => session('user'),
    'email' => session('email'),

    'produk' => json_encode(session('cart')),
    'total' => $request->total,

    'metode' => $request->metode,
    'tipe_pengiriman' => $request->tipe_pengiriman, 

    'waktu' => $request->waktu,

    'alamat' => $request->alamat, 
    'latitude' => $request->latitude,
    'longitude' => $request->longitude,

    'status' => 'baru'
]);

    session()->forget('cart');

    return redirect('/tracking');
}

    public function tracking()
{
    $order = Order::where('email', session('email'))
                    ->orderBy('id', 'desc')
                    ->first();

    return view('tracking', compact('order'));
}

    public function myOrders()
{
    $orders = Order::where('email', session('email'))
                    ->orderBy('id', 'desc')
                    ->get();

    return view('my-orders', compact('orders'));
}
}