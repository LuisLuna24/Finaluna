<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
// 1. IMPORTA TUS CONTROLADORES AQUÍ:
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ExpenseController;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // El dashboard se puede quedar con Route::inertia porque normalmente no recibe datos iniciales complejos
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
    
    // 2. CAMBIA ESTAS RUTAS PARA USAR CONTROLADORES:
    Route::get('/catalogs/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/catalogs/payment-methods', [PaymentMethodController::class, 'index'])->name('payment_methods');
    Route::get('/catalogs/expenses', [ExpenseController::class, 'index'])->name('expenses');
});

require __DIR__.'/settings.php';