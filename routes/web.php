<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\IncomingOrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\AdminDiscountController;
use App\Http\Controllers\CustomerDiscountController;
/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post('/register-process', function (Request $request) {

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login');

})->name('register.process');

Route::post('/login-process', function (Request $request) {

    $user = User::where('email', $request->email)->first();

    if (!$user) return back()->with('error', 'Email tidak ditemukan');
    if (!Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Password salah');
    }

    session([
        'user' => $user->name,
        'email' => $user->email
    ]);

    if ($user->email === 'admin@gmail.com') {
        session(['is_admin' => true]);
        return redirect('/dashboard');
    } else {
        return redirect('/customer');
    }

})->name('login.process');

/*
|--------------------------------------------------------------------------
| VIEW
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', fn() => view('login'))->name('login');
Route::get('/register', fn() => view('register'))->name('register');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/incoming-orders', [IncomingOrderController::class, 'index'])->name('incoming.orders');
Route::get('/incoming-orders/{type}', [IncomingOrderController::class, 'index']);

Route::post('/order/store', [IncomingOrderController::class, 'store']);
Route::get('/order/terima/{id}', [IncomingOrderController::class, 'terima']);
Route::get('/order/siap/{id}', [IncomingOrderController::class, 'siap']);
Route::get('/order/selesai/{id}', [IncomingOrderController::class, 'selesai']);
Route::get('/order/tolak/{id}', [IncomingOrderController::class, 'tolak']);

Route::get('/admin/promo', [PromoController::class, 'index']);
Route::post('/admin/promo/update/{id}', [PromoController::class, 'update'])->name('promo.update');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::post('/inventory/store', [InventoryController::class, 'store'])->name('inventory.store');
Route::put('/inventory/update/{id}', [InventoryController::class, 'update'])->name('inventory.update');
Route::delete('/inventory/delete/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::get('/sales/data', [SalesController::class, 'data'])->name('sales.data');
Route::get('/admin/discount', [AdminDiscountController::class, 'index'])->name('admin.discount.index');
Route::post('/admin/discount/store', [AdminDiscountController::class, 'store'])->name('admin.discount.store');
Route::put('/admin/discount/update/{id}', [AdminDiscountController::class, 'update'])->name('admin.discount.update');
Route::delete('/admin/discount/delete/{id}', [AdminDiscountController::class, 'destroy'])->name('admin.discount.destroy');
/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

Route::get('/customer', [CustomerController::class, 'index']);

Route::post('/cart/add', [CustomerController::class, 'addToCart']);
Route::get('/cart/plus/{id}', [CustomerController::class, 'plus']);
Route::get('/cart/min/{id}', [CustomerController::class, 'min']);
Route::get('/cart/remove/{id}', [CustomerController::class, 'remove']);

Route::post('/checkout', [CustomerController::class, 'checkout']);
Route::get('/tracking', [CustomerController::class, 'tracking']);
Route::get('/tracking/data/{id}', [CustomerController::class, 'trackingData'])->name('tracking.data');

Route::get('/cart/clear', function () {
    session()->forget('cart');
    return back();
});

Route::get('/myOrders', [CustomerController::class, 'myOrders']);

Route::get('/discount', [CustomerDiscountController::class, 'index'])->name('discount');
Route::post('/discount/claim/{id}', [CustomerDiscountController::class, 'claim'])->name('discount.claim');
