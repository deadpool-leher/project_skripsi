<?php

namespace App\Http\Controllers;

use App\Events\CustomerOrderUpdated;
use App\Events\OrderUpdated;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserDiscount;
use App\Services\SalesAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomingOrderController extends Controller
{
    public function __construct(private SalesAnalyticsService $salesAnalytics)
    {
    }

    public function index($type = null)
    {
        $totalBaru = Order::where('status', 'baru')->count();
        $totalProses = Order::where('status', 'diproses')->count();
        $totalHariIni = Order::where('status', 'selesai')->count();

        if ($type === 'baru') {
            $orders = Order::where('status', 'baru')
                ->where('status', '!=', 'ditolak')
                ->get();
        } elseif ($type === 'aktif') {
            $orders = Order::whereIn('status', ['diproses', 'siap'])
                ->where('status', '!=', 'ditolak')
                ->get();
        } else {
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

    public function store(Request $request)
    {
        $request->validate([
            'metode' => 'required|string|max:50',
            'waktu' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'user_discount_id' => 'nullable|integer',
        ]);

        $cart = session('cart') ?? [];

        if (count($cart) === 0) {
            return back();
        }

        $total = 0;
        $produkList = [];

        foreach ($cart as $productId => $item) {
            $qty = max((int) ($item['qty'] ?? 0), 0);
            $harga = max((int) ($item['harga'] ?? 0), 0);

            if ($qty < 1) {
                continue;
            }

            $produkList[] = [
                'product_id' => (int) $productId,
                'nama' => $item['produk'] ?? '',
                'qty' => $qty,
            ];

            $total += $harga * $qty;
        }

        if (count($produkList) === 0) {
            return back();
        }

        $user = User::where('email', session('email'))->firstOrFail();
        $discountAmount = 0;
        $discountCode = null;
        $userDiscount = null;

        if ($request->filled('user_discount_id')) {
            $userDiscount = UserDiscount::with('discount')
                ->where('id', $request->user_discount_id)
                ->where('user_id', $user->id)
                ->first();

            if (!$userDiscount || !$userDiscount->discount) {
                return back()->with('error', 'Diskon tidak valid.');
            }

            if ($userDiscount->used_at) {
                return back()->with('error', 'Diskon sudah pernah digunakan.');
            }

            $discount = $userDiscount->discount;

            if ($discount->expired_at && $discount->expired_at->isPast()) {
                return back()->with('error', 'Diskon sudah kedaluwarsa.');
            }

            if ($discount->usage_limit && $discount->userDiscounts()->count() > $discount->usage_limit) {
                return back()->with('error', 'Kuota diskon sudah habis.');
            }

            $discountAmount = (int) floor($total * ($discount->value / 100));
            $discountCode = $discount->code;
        }

        $finalTotal = max($total - $discountAmount, 0);

        $order = Order::create([
            'nama' => session('user'),
            'email' => session('email'),
            'produk' => $produkList,
            'subtotal' => $total,
            'discount_code' => $discountCode,
            'discount_amount' => $discountAmount,
            'total' => $finalTotal,
            'status' => 'baru',
            'metode' => $request->metode,
            'waktu' => $request->waktu,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'created_at' => now(),
        ]);

        if ($userDiscount) {
            $userDiscount->used_at = now();
            $userDiscount->save();
        }

        broadcast(new OrderUpdated($this->salesAnalytics->getAnalytics(['filter' => 'today'])))->toOthers();
        broadcast(new CustomerOrderUpdated($this->transformCustomerOrder($order), $order->id))->toOthers();

        session()->forget('cart');

        return redirect('/tracking');
    }

    public function terima($id)
    {
        DB::transaction(function () use ($id) {
            $order = Order::lockForUpdate()->findOrFail($id);

            if ($order->status !== 'baru') {
                return;
            }

            $items = is_array($order->produk) ? $order->produk : json_decode($order->produk, true);

            foreach ($items ?? [] as $item) {
                $qty = max((int) ($item['qty'] ?? 0), 0);
                $productId = $item['product_id'] ?? null;
                $productName = $item['nama'] ?? null;

                if ($qty < 1) {
                    continue;
                }

                $product = null;

                if ($productId) {
                    $product = Product::lockForUpdate()->find($productId);
                }

                if (!$product && $productName) {
                    $product = Product::lockForUpdate()->where('nama', $productName)->first();
                }

                if (!$product || $product->stok < 1) {
                    continue;
                }

                $product->decrement('stok', min($qty, $product->stok));
            }

            $order->status = 'diproses';
            $order->save();
        });

        $order = Order::findOrFail($id);

        broadcast(new OrderUpdated($this->salesAnalytics->getAnalytics(['filter' => 'today'])))->toOthers();
        broadcast(new CustomerOrderUpdated($this->transformCustomerOrder($order), $order->id))->toOthers();

        return redirect()->route('incoming.orders');
    }

    public function siap($id)
    {
        $order = Order::find($id);
        $order->status = 'siap';
        $order->save();

        broadcast(new OrderUpdated($this->salesAnalytics->getAnalytics(['filter' => 'today'])))->toOthers();
        broadcast(new CustomerOrderUpdated($this->transformCustomerOrder($order), $order->id))->toOthers();

        return redirect()->route('incoming.orders');
    }

    public function selesai($id)
    {
        $order = Order::find($id);
        $order->status = 'selesai';
        $order->save();

        broadcast(new OrderUpdated($this->salesAnalytics->getAnalytics(['filter' => 'today'])))->toOthers();
        broadcast(new CustomerOrderUpdated($this->transformCustomerOrder($order), $order->id))->toOthers();

        return redirect()->route('incoming.orders');
    }

    public function tolak($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->status = 'ditolak';
            $order->save();

            broadcast(new OrderUpdated($this->salesAnalytics->getAnalytics(['filter' => 'today'])))->toOthers();
            broadcast(new CustomerOrderUpdated($this->transformCustomerOrder($order), $order->id))->toOthers();
        }

        return redirect('/incoming-orders');
    }

    private function transformCustomerOrder(Order $order): array
    {
        return [
            'id' => $order->id,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'total' => (int) $order->total,
            'metode' => $order->metode ?? '-',
            'waktu' => $order->waktu ?? '-',
            'alamat' => $order->alamat,
        ];
    }
}
