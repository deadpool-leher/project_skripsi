<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class AdminDiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::withCount([
            'userDiscounts as total_claimed',
            'userDiscounts as total_used' => fn ($query) => $query->whereNotNull('used_at'),
        ])->latest()->get();

        $totalDiscount = $discounts->count();
        $activeDiscount = $discounts->filter(function ($discount) {
            return !$discount->expired_at || $discount->expired_at->isFuture();
        })->count();

        return view('admin-discount', compact(
            'discounts',
            'totalDiscount',
            'activeDiscount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discounts,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percent',
            'value' => 'required|integer|min:1|max:100',
            'expired_at' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        Discount::create($validated);

        return redirect()->route('admin.discount.index');
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discounts,code,' . $discount->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percent',
            'value' => 'required|integer|min:1|max:100',
            'expired_at' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $discount->update($validated);

        return redirect()->route('admin.discount.index');
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return redirect()->route('admin.discount.index');
    }
}
