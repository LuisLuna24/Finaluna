<?php

use App\Livewire\Modules\Movements\Budgets\Form as BudgetForm;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\ExpenseType;
use App\Models\Income;
use App\Models\PaymentMethod;
use App\Models\Subcategory;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

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

it('creates a budget with incomes and budget items', function () {
    Livewire::test(BudgetForm::class)
        ->set('budget.name', 'Presupuesto mensual')
        ->set('budget.start_date', '2026-09-01')
        ->set('budget.end_date', '2026-09-30')
        ->call('newIncome')
        ->set('incomeForm.incomeMethod', (string) $this->paymentMethod->id)
        ->set('incomeForm.incomeAmount', '1500.00')
        ->set('incomeForm.incomeDate', '2026-09-01')
        ->set('incomeForm.incomeDescription', 'Salario')
        ->set('incomeForm.incomeSavingsAllocation', 10)
        ->call('saveIncome')
        ->assertSet('incomes', fn ($incomes) => count($incomes) === 1)
        ->call('newBudgetItem')
        ->set('budgetItemForm.budgetExpenseTypeId', $this->expenseType->id)
        ->set('budgetItemForm.budgetCategoryId', $this->category->id)
        ->set('budgetItemForm.budgetSubcategoryId', $this->subcategory->id)
        ->set('budgetItemForm.budgetAmount', '800.00')
        ->call('saveBudgetItem')
        ->assertSet('budgetItems', fn ($items) => count($items) === 1)
        ->call('save')
        ->assertRedirect(route('movements.budgets'));

    $budget = Budget::where('nombre', 'Presupuesto mensual')->first();

    expect($budget)->not->toBeNull()
        ->and((float) $budget->presupuesto)->toBe(800.0)
        ->and((float) $budget->balance)->toBe(1500.0)
        ->and($budget->incomes)->toHaveCount(1)
        ->and($budget->budgetItems)->toHaveCount(1);

    $income = $budget->incomes->first();
    expect((float) $income->total)->toBe(1500.0)
        ->and($income->payment_method_id)->toBe($this->paymentMethod->id)
        ->and($income->descripcion)->toBe('Salario');

    $item = $budget->budgetItems->first();
    expect((float) $item->presupuesto)->toBe(800.0)
        ->and($item->category_id)->toBe($this->category->id)
        ->and($item->subcategory_id)->toBe($this->subcategory->id);
});

it('edits an income and a budget item inside the wizard', function () {
    Livewire::test(BudgetForm::class)
        ->set('budget.name', 'Presupuesto')
        ->set('budget.start_date', '2026-09-01')
        ->set('budget.end_date', '2026-09-30')
        ->call('newIncome')
        ->set('incomeForm.incomeMethod', (string) $this->paymentMethod->id)
        ->set('incomeForm.incomeAmount', '1500.00')
        ->set('incomeForm.incomeDate', '2026-09-01')
        ->set('incomeForm.incomeDescription', 'Salario')
        ->set('incomeForm.incomeSavingsAllocation', 10)
        ->call('saveIncome')
        ->call('editIncome', 0)
        ->set('incomeForm.incomeAmount', '2000.00')
        ->set('incomeForm.incomeDescription', 'Salario actualizado')
        ->call('saveIncome')
        ->assertSet('incomes.0', fn ($income) => $income['description'] === 'Salario actualizado')
        ->call('newBudgetItem')
        ->set('budgetItemForm.budgetExpenseTypeId', $this->expenseType->id)
        ->set('budgetItemForm.budgetCategoryId', $this->category->id)
        ->set('budgetItemForm.budgetSubcategoryId', $this->subcategory->id)
        ->set('budgetItemForm.budgetAmount', '800.00')
        ->call('saveBudgetItem')
        ->call('editBudgetItem', 0)
        ->set('budgetItemForm.budgetAmount', '1000.00')
        ->call('saveBudgetItem')
        ->assertSet('budgetItems.0', fn ($item) => $item['presupuesto'] === 1000.0)
        ->call('save')
        ->assertRedirect(route('movements.budgets'));

    $budget = Budget::where('nombre', 'Presupuesto')->first();

    expect($budget)->not->toBeNull()
        ->and((float) $budget->incomes->first()->total)->toBe(2000.0)
        ->and((float) $budget->budgetItems->first()->presupuesto)->toBe(1000.0);
});

it('updates an existing budget', function () {
    $budget = Budget::create([
        'user_id' => $this->user->id,
        'nombre' => 'Presupuesto viejo',
        'fecha_inicio' => '2026-09-01',
        'fecha_fin' => '2026-09-30',
        'presupuesto' => 800.00,
        'gasto_real' => 0,
        'balance' => 1500.00,
        'is_active' => true,
    ]);

    Income::create([
        'user_id' => $this->user->id,
        'budget_id' => $budget->id,
        'payment_method_id' => $this->paymentMethod->id,
        'fecha' => '2026-09-01',
        'descripcion' => 'Salario',
        'total' => 1500.00,
        'porcentaje_ahorro' => 10,
        'is_active' => true,
    ]);

    BudgetItem::create([
        'budget_id' => $budget->id,
        'category_id' => $this->category->id,
        'subcategory_id' => $this->subcategory->id,
        'expense_type_id' => $this->expenseType->id,
        'presupuesto' => 800.00,
        'gasto_real' => 0,
    ]);

    Livewire::test(BudgetForm::class, ['editId' => $budget->id])
        ->assertSet('budget.name', 'Presupuesto viejo')
        ->assertSet('incomes', fn ($incomes) => count($incomes) === 1)
        ->assertSet('budgetItems', fn ($items) => count($items) === 1)
        ->call('editIncome', 0)
        ->assertSet('incomeForm.incomeAmount', '1500')
        ->call('saveIncome')
        ->set('budget.name', 'Presupuesto actualizado')
        ->call('save')
        ->assertRedirect(route('movements.budgets'));

    expect($budget->fresh()->nombre)->toBe('Presupuesto actualizado')
        ->and($budget->fresh()->incomes)->toHaveCount(1)
        ->and($budget->fresh()->budgetItems)->toHaveCount(1);
});
