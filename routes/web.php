<?php

use App\Http\Controllers\CashierDashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $search = trim($request->query('search', ''));

    $products = Product::active()
        ->with('category')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        })
        ->latest()
        ->take(8)
        ->get();

    return view('index', compact('products', 'search'));
});

Route::get('/products/{product}', function (Product $product) {
    abort_unless($product->status === 'active', 404);

    return view('products.show', [
        'product' => $product->load('category'),
    ]);
})->name('products.show');

// Redirect /dashboard to /cashier
Route::get('/dashboard', function () {
    return redirect()->route('cashier');
})->name('dashboard');

// Cashier POS Main Route
Route::get('/cashier', [CashierDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('cashier');

// Cashier Transaction Report Route
Route::get('/cashier/reports', function () {
    return view('cashier.reports');
})->middleware(['auth', 'verified'])->name('cashier.reports');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
