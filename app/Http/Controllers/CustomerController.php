<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserDiscount;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function index()
    {
        $user = User::where('email', session('customer_email'))->first();

        $products = Product::where('stok', '>', 0)
            ->orderBy('nama')
            ->get();

        $availableDiscounts = collect();

        if ($user) {
            $availableDiscounts = UserDiscount::with('discount')
                ->where('user_id', $user->id)
                ->whereNull('used_at')
                ->whereHas('discount', function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->whereNull('expired_at')
                            ->orWhere('expired_at', '>', now());
                    });
                })
                ->latest()
                ->get();
        }

        return view('customer-products', compact('products', 'availableDiscounts'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $cart = session()->get('cart', []);

        $id = $product->id;

        if (isset($cart[$id])) {
            if ($cart[$id]['qty'] >= $product->stok) {
                return redirect()->back();
            }

            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'produk' => $product->nama,
                'harga' => $product->harga,
                'qty' => 1
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back();
    }

    public function plus($id)
    {
        $cart = session('cart');
        $product = Product::find($id);

        if ($product && isset($cart[$id]) && $cart[$id]['qty'] < $product->stok) {
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

        $validated = $request->validate([
            'metode' => 'required|string',
            'tipe_pengiriman' => 'required|string|in:pickup,delivery',
            'waktu' => 'required|string',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'discount_code' => 'nullable|string|max:50',
        ]);

        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['harga'] * $item['qty'];
        }

        $discountAmount = 0;
        $discountCode = null;
        $userDiscount = null;
        $user = User::where('email', session('customer_email'))->first();

        if (!empty($validated['discount_code'])) {
            if (!$user) {
                throw ValidationException::withMessages([
                    'discount_code' => 'User tidak ditemukan untuk penggunaan diskon.',
                ]);
            }

            $userDiscount = UserDiscount::with('discount')
                ->where('user_id', $user->id)
                ->whereNull('used_at')
                ->whereHas('discount', function ($query) use ($validated) {
                    $query->where('code', $validated['discount_code'])
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('expired_at')
                                ->orWhere('expired_at', '>', now());
                        });
                })
                ->first();

            if (!$userDiscount || !$userDiscount->discount) {
                throw ValidationException::withMessages([
                    'discount_code' => 'Kode kupon tidak valid atau belum diklaim.',
                ]);
            }

            $discountCode = $userDiscount->discount->code;
            $discountAmount = (int) floor($subtotal * ($userDiscount->discount->value / 100));
        }

        $total = max($subtotal - $discountAmount, 0);

        Order::create([
            'nama' => session('customer_user'),
            'email' => session('customer_email'),
            'produk' => $cart,
            'subtotal' => $subtotal,
            'total' => $total,
            'discount_code' => $discountCode,
            'discount_amount' => $discountAmount,
            'metode' => $validated['metode'],
            'tipe_pengiriman' => $validated['tipe_pengiriman'],
            'waktu' => $validated['waktu'],
            'alamat' => $validated['alamat'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'status' => 'baru',
        ]);

        if ($userDiscount) {
            $userDiscount->update([
                'used_at' => now(),
            ]);
        }

        session()->forget('cart');

        return redirect('/tracking');
    }

    public function tracking()
{
    $order = Order::where('email', session('customer_email'))
                    ->orderBy('id', 'desc')
                    ->first();

    return view('tracking', compact('order'));
}

    public function trackingData($id)
{
    $order = Order::where('email', session('customer_email'))
                    ->where('id', $id)
                    ->firstOrFail();

    return response()->json([
        'id' => $order->id,
        'status' => $order->status,
        'status_label' => $order->status_label,
        'total' => (int) $order->total,
        'subtotal' => (int) ($order->subtotal ?? $order->total),
        'discount_code' => $order->discount_code,
        'discount_amount' => (int) ($order->discount_amount ?? 0),
        'metode' => $order->metode ?? '-',
        'waktu' => $order->waktu ?? '-',
        'alamat' => $order->alamat,
        'payment_proof' => $order->payment_proof,
        'payment_proof_url' => $order->payment_proof ? asset($order->payment_proof) : null,
    ]);
}

    public function uploadPaymentProof(Request $request, $id)
{
    $order = Order::where('email', session('customer_email'))
                    ->where('id', $id)
                    ->firstOrFail();

    if (($order->metode ?? '') !== 'qris') {
        return back()->with('error', 'Bukti pembayaran hanya untuk pesanan QRIS.');
    }

    $request->validate([
        'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    ], [
        'payment_proof.required' => 'Pilih gambar bukti pembayaran terlebih dahulu.',
        'payment_proof.image' => 'File harus berupa gambar.',
        'payment_proof.mimes' => 'Format gambar harus JPG, PNG, atau WEBP.',
        'payment_proof.max' => 'Ukuran gambar maksimal 2 MB.',
    ]);

    $uploadPath = public_path('uploads/payment-proofs');
    File::ensureDirectoryExists($uploadPath);

    if ($order->payment_proof && File::exists(public_path($order->payment_proof))) {
        File::delete(public_path($order->payment_proof));
    }

    $file = $request->file('payment_proof');
    $filename = 'order-' . $order->id . '-' . time() . '.' . $file->getClientOriginalExtension();
    $file->move($uploadPath, $filename);

    $order->payment_proof = 'uploads/payment-proofs/' . $filename;
    $order->save();

    return redirect('/tracking')->with('success', 'Bukti pembayaran berhasil dikirim.');
}

    public function myOrders()
{
    $orders = Order::where('email', session('customer_email'))
                    ->orderBy('id', 'desc')
                    ->get();

    return view('my-orders', compact('orders'));
}
}
