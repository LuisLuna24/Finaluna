<?php

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Income;
use App\Models\PaymentMethod;
use App\Models\Pocket;
use App\Models\PocketItem;
use App\Models\Subcategory;
use App\Models\User;

beforeEach(function () {
    $this->paymentMethod = PaymentMethod::create(['nombre' => 'Efectivo']);
    $this->expenseType = ExpenseType::create(['nombre' => 'Fijos']);
    $this->category = Category::create([
        'nombre' => 'Hogar',
        'expense_type_id' => $this->expenseType->id,
    ]);
    $this->subcategory = Subcategory::create([
        'category_id' => $this->category->id,
        'nombre' => 'Renta',
    ]);
});

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('dashboard shows aggregated financial metrics', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $budget = Budget::create([
        'user_id' => $user->id,
        'nombre' => 'Presupuesto mensual',
        'fecha_inicio' => '2026-09-01',
        'fecha_fin' => '2026-09-30',
        'presupuesto' => 800.00,
        'gasto_real' => 0,
        'balance' => 0,
        'is_active' => true,
    ]);

    $budgetItem = BudgetItem::create([
        'budget_id' => $budget->id,
        'category_id' => $this->category->id,
        'subcategory_id' => $this->subcategory->id,
        'expense_type_id' => $this->expenseType->id,
        'presupuesto' => 800.00,
        'gasto_real' => 0,
    ]);

    Income::create([
        'user_id' => $user->id,
        'budget_id' => $budget->id,
        'payment_method_id' => $this->paymentMethod->id,
        'fecha' => '2026-09-01',
        'descripcion' => 'Salario',
        'total' => 1500.00,
        'porcentaje_ahorro' => 10,
        'total_ahorro' => 150.00,
        'is_active' => true,
    ]);

    Expense::create([
        'user_id' => $user->id,
        'budget_item_id' => $budgetItem->id,
        'payment_method_id' => $this->paymentMethod->id,
        'fecha' => '2026-09-05',
        'descripcion' => 'Renta',
        'total' => 200.00,
        'is_active' => true,
    ]);

    $pocket = Pocket::create([
        'user_id' => $user->id,
        'nombre' => 'Vacaciones',
        'meta_apartado' => 1000.00,
        'is_active' => true,
    ]);

    PocketItem::create([
        'pocket_id' => $pocket->id,
        'payment_method_id' => $this->paymentMethod->id,
        'descripcion' => 'Ahorro inicial',
        'fecha' => '2026-09-02',
        'monto' => 100.00,
    ]);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Total Ingresos')
        ->assertSee('1,500.00')
        ->assertSee('Total Gastos')
        ->assertSee('200.00')
        ->assertSee('Total Ahorrado')
        ->assertSee('150.00')
        ->assertSee('Presupuesto mensual')
        ->assertSee('Vacaciones')
        ->assertSee('Salario')
        ->assertSee('Renta');
});

test('dashboard isolates data by user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $otherUser = User::factory()->create();

    $otherBudget = Budget::create([
        'user_id' => $otherUser->id,
        'nombre' => 'Presupuesto ajeno',
        'fecha_inicio' => '2026-09-01',
        'fecha_fin' => '2026-09-30',
        'presupuesto' => 500.00,
        'gasto_real' => 0,
        'balance' => 0,
        'is_active' => true,
    ]);

    Income::create([
        'user_id' => $otherUser->id,
        'budget_id' => $otherBudget->id,
        'payment_method_id' => $this->paymentMethod->id,
        'fecha' => '2026-09-01',
        'descripcion' => 'Ingreso ajeno',
        'total' => 9999.00,
        'is_active' => true,
    ]);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('9,999.00')
        ->assertDontSee('Ingreso ajeno')
        ->assertDontSee('Presupuesto ajeno');
});
