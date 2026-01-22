<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');



Route::middleware(['auth'])->group(function() {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('permissions/toggle', [PermissionController::class, 'toggle'])->name('permissions.toggle');
    
    Route::resource('products', ProductController::class);
    Route::post('stocks/confirm/{id}', [StockController::class, 'confirmed'])->name('stocks.confirmed');
    Route::resource('stocks', StockController::class);
    Route::resource('orders', OrderController::class);
});


require __DIR__ . '/auth.php';