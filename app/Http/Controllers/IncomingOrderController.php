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
use Illuminate\Support\Facades\Log;
use Throwable;

class IncomingOrderController extends Controller
{
    public function __construct(private SalesAnalyticsService $salesAnalytics)
    {
    }

    public function index($type = null)
    {
        $totalBaru = Order::where('status', 'baru')->count();
        $totalProses = Order::whereIn('status', ['diproses', 'siap'])->count();
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
            'discount_code' => 'nullable|string|max:50',
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

        $user = User::where('email', session('customer_email'))->firstOrFail();
        $discountAmount = 0;
        $discountCode = null;
        $userDiscount = null;

        if ($request->filled('user_discount_id') || $request->filled('discount_code')) {
            $userDiscount = UserDiscount::with('discount')
                ->where('user_id', $user->id)
                ->whereNull('used_at')
                ->when($request->filled('user_discount_id'), function ($query) use ($request) {
                    $query->where('id', $request->user_discount_id);
                })
                ->when($request->filled('discount_code'), function ($query) use ($request) {
                    $query->whereHas('discount', function ($discountQuery) use ($request) {
                        $discountQuery->whereRaw('UPPER(code) = ?', [strtoupper($request->discount_code)]);
                    });
                })
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

            if ($discount->usage_limit && $discount->userDiscounts()->count() >= $discount->usage_limit) {
                return back()->with('error', 'Kuota diskon sudah habis.');
            }

            $discountAmount = (int) floor($total * ($discount->value / 100));
            $discountCode = $discount->code;
        }

        $finalTotal = max($total - $discountAmount, 0);

        $order = Order::create([
            'nama' => session('customer_user'),
            'email' => session('customer_email'),
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
            'created_at' => now('UTC'),
        ]);

        if ($userDiscount) {
            $userDiscount->used_at = now();
            $userDiscount->save();
        }

        $this->broadcastOrderUpdates($order);

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

        $this->broadcastOrderUpdates($order);

        return redirect()->route('incoming.orders');
    }

    public function siap($id)
    {
        $order = Order::find($id);
        $order->status = 'siap';
        $order->save();

        $this->broadcastOrderUpdates($order);

        return redirect()->route('incoming.orders');
    }

    public function selesai($id)
    {
        $order = Order::find($id);
        $order->status = 'selesai';
        $order->save();

        $this->broadcastOrderUpdates($order);

        return redirect()->route('incoming.orders');
    }

    public function tolak($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->status = 'ditolak';
            $order->save();

            $this->broadcastOrderUpdates($order);
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
            'subtotal' => (int) ($order->subtotal ?? $order->total),
            'discount_code' => $order->discount_code,
            'discount_amount' => (int) ($order->discount_amount ?? 0),
            'metode' => $order->metode ?? '-',
            'waktu' => $order->waktu ?? '-',
            'alamat' => $order->alamat,
        ];
    }

    private function broadcastOrderUpdates(Order $order): void
    {
        try {
            broadcast(new OrderUpdated($this->salesAnalytics->getAnalytics(['filter' => 'today'])))->toOthers();
            broadcast(new CustomerOrderUpdated($this->transformCustomerOrder($order), $order->id))->toOthers();
        } catch (Throwable $exception) {
            Log::warning('Order broadcast failed.', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
