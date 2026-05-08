<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\User;
use App\Models\UserDiscount;
use Illuminate\Http\Request;

class CustomerDiscountController extends Controller
{
    public function index()
    {
        $user = $this->currentUser();

        $discounts = Discount::with(['userDiscounts' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
            ->withCount([
                'userDiscounts as total_claimed',
                'userDiscounts as total_used' => fn ($query) => $query->whereNotNull('used_at'),
            ])
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->latest()
            ->get();

        $activeDiscount = $discounts->count();
        $claimedDiscount = $discounts->filter(fn ($discount) => $discount->userDiscounts->isNotEmpty())->count();

        return view('customer-discount', compact(
            'discounts',
            'activeDiscount',
            'claimedDiscount'
        ));
    }

    public function claim($id)
    {
        $user = $this->currentUser();
        $discount = Discount::findOrFail($id);

        if ($discount->expired_at && $discount->expired_at->isPast()) {
            return redirect()->route('discount')->with('error', 'Diskon sudah kedaluwarsa.');
        }

        if ($discount->usage_limit && $discount->userDiscounts()->count() >= $discount->usage_limit) {
            return redirect()->route('discount')->with('error', 'Kuota diskon sudah habis.');
        }

        UserDiscount::firstOrCreate(
            [
                'user_id' => $user->id,
                'discount_id' => $discount->id,
            ],
            [
                'claimed_at' => now(),
            ]
        );

        return redirect()->route('discount')->with('success', 'Diskon berhasil diklaim.');
    }

    private function currentUser(): User
    {
        return User::where('email', session('customer_email'))->firstOrFail();
    }
}
