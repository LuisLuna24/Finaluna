<?php

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
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

    Route::prefix('movements')->name('movements.')->group(function () {
        Route::view('budgets', 'modules.movements.budgets.index')->name('budgets');
        Route::view('budgets/create', 'modules.movements.budgets.form')->name('budgets.create');
        Route::get('budgets/edit/{id}', function (int $id) {
            abort_unless(
                Budget::where('id', $id)->where('user_id', Auth::user()->id)->exists(),
                404
            );

            return view('modules.movements.budgets.form', ['id' => $id]);
        })->name('budgets.edit');

        Route::get('budgets/incomes/{id}', function (int $id) {
            abort_unless(
                Budget::where('id', $id)->where('user_id', Auth::user()->id)->exists(),
                404
            );

            return view('modules.movements.incomes.index', ['id' => $id]);
        })->name('budgets.incomes');

        Route::get('budgets/expenses/{id}', function (int $id) {
            abort_unless(
                Budget::where('id', $id)->where('user_id', Auth::user()->id)->exists(),
                404
            );

            return view('modules.movements.expenses.index', ['id' => $id]);
        })->name('budgets.expenses');
    });
});

require __DIR__.'/settings.php';
