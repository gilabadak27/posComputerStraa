<?php

use App\Http\Controllers\CashierDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// Redirect /dashboard to /cashier
Route::get('/dashboard', function () {
    return redirect()->route('cashier');
})->name('dashboard');

// Cashier POS Main Route
Route::get('/cashier', [CashierDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('cashier');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
