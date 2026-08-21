<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('catalogs')->name('catalogs.')->group(function () {
        Route::view('icons', 'modules.catalogs.icons.index')->name('icons');
        Route::view('categories', 'modules.catalogs.categories.index')->name('categories');
        Route::view('subcategories', 'modules.catalogs.subcategories.index')->name('subcategories');
        Route::view('payment', 'modules.catalogs.payments.index')->name('payments');
        Route::view('expense', 'modules.catalogs.expenses.index')->name('expenses');
    });
});

require __DIR__ . '/settings.php';
