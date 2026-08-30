<?php

use App\Models\Budget;
use App\Models\Pocket;
use App\Models\User;
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
    })->middleware('can:view catalogs');

    Route::prefix('movements')->name('movements.')->group(function () {
        Route::view('budgets', 'modules.movements.budgets.index')->name('budgets')->can('view budgets');
        Route::view('budgets/create', 'modules.movements.budgets.form')->name('budgets.create')->can('create budgets');
        Route::get('budgets/edit/{id}', function (int $id) {
            abort_unless(
                Budget::where('id', $id)->where('user_id', Auth::user()->id)->exists(),
                404
            );

            return view('modules.movements.budgets.form', ['id' => $id]);
        })->name('budgets.edit')->can('edit budgets');

        Route::get('budgets/incomes/{id}', function (int $id) {
            abort_unless(
                Budget::where('id', $id)->where('user_id', Auth::user()->id)->exists(),
                404
            );

            return view('modules.movements.incomes.index', ['id' => $id]);
        })->name('budgets.incomes')->can('view incomes');

        Route::get('budgets/expenses/{id}', function (int $id) {
            abort_unless(
                Budget::where('id', $id)->where('user_id', Auth::user()->id)->exists(),
                404
            );

            return view('modules.movements.expenses.index', ['id' => $id]);
        })->name('budgets.expenses')->can('view expenses');
    });

    Route::prefix('pockets')->name('pockets.')->group(function () {
        Route::view('pockets', 'modules.pockets.index')->name('index')->can('view pockets');
        Route::view('pockets/create', 'modules.pockets.form')->name('create')->can('create pockets');
        Route::get('pockets/edit/{id}', function (int $id) {
            abort_unless(
                Pocket::where('id', $id)->where('user_id', Auth::user()->id)->exists(),
                404
            );

            return view('modules.pockets.form', ['id' => $id]);
        })->name('edit')->can('edit pockets');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::view('users', 'modules.users.users.index')->name('user.index')->can('view users');
        Route::view('users/create', 'modules.users.users.form')->name('user.create')->can('create users');
        Route::view('users/edit/{id}', 'modules.users.users.form')->name('user.edit')->can('edit users');

        Route::view('roles', 'modules.users.roles.index')->name('roles')->can('view roles');
        Route::view('permissions', 'modules.users.permissions.index')->name('permissions')->can('view permissions');
    });
});

require __DIR__ . '/settings.php';
