<?php

namespace App\Livewire\Modules\Movements\Budgets;

use App\Livewire\Forms\Budgets\BudgetItemForm;
use App\Livewire\Forms\Incomes\IncomeForm;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\ExpenseType;
use App\Models\Income;
use App\Models\PaymentMethod;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    public int $step = 1;

    public ?int $editId = null;

    public array $budget = [
        'name' => '',
        'start_date' => '',
        'end_date' => '',
        'notes' => '',
    ];

    public IncomeForm $incomeForm;

    public BudgetItemForm $budgetItemForm;

    // Incomes step
    public array $incomes = [];

    // Budget Items step
    public array $budgetItems = [];

    public function mount(?int $editId = null): void
    {
        $this->editId = $editId;

        if ($this->editId) {
            $budget = Budget::with(['incomes.paymentMethod', 'budgetItems.category', 'budgetItems.subcategory', 'budgetItems.expenseType'])
                ->findOrFail($this->editId);

            $this->budget = [
                'name' => $budget->nombre,
                'start_date' => $budget->fecha_inicio,
                'end_date' => $budget->fecha_fin,
                'notes' => $budget->notas,
            ];

            $this->incomes = $budget->incomes->map(fn (Income $income) => [
                'id' => $income->id,
                'method_id' => $income->payment_method_id,
                'method' => $income->paymentMethod?->nombre ?? 'Desconocido',
                'date' => $income->fecha,
                'amount' => (float) $income->total,
                'description' => $income->descripcion,
                'savings_allocation' => $income->porcentaje_ahorro,
                'savings_total' => $income->total_ahorro,
                'notes' => $income->notes ?? '',
            ])->values()->all();

            $this->budgetItems = $budget->budgetItems->map(fn (BudgetItem $item) => [
                'id' => $item->id,
                'category_id' => $item->category_id,
                'category_name' => $item->category?->nombre ?? 'N/A',
                'subcategory_id' => $item->subcategory_id,
                'subcategory_name' => $item->subcategory?->nombre ?? 'N/A',
                'expense_type_id' => $item->expense_type_id,
                'expense_type_name' => $item->expenseType?->nombre ?? 'N/A',
                'presupuesto' => (float) $item->presupuesto,
                'notas' => $item->notas,
            ])->values()->all();
        }
    }

    public function next(): void
    {
        $this->step++;
    }

    public function prev(): void
    {
        $this->step--;
    }

    public function newIncome(): void
    {
        $this->incomeForm->reset();
        $this->incomeForm->incomeDate = now()->format('Y-m-d\TH:i');
        $this->incomeForm->incomeSavingsAllocation = 10;
        $this->incomeForm->modal = true;
    }

    public function saveIncome(): void
    {
        $this->incomeForm->validate([
            'incomeMethod' => 'required|exists:payment_methods,id',
            'incomeAmount' => 'required|numeric|min:0.01',
            'incomeDate' => 'required|date',
            'incomeDescription' => 'required|string|max:255',
            'incomeSavingsAllocation' => 'required|integer|min:0|max:100',
            'incomeNotes' => 'nullable|string|max:1000',
        ]);

        $method = PaymentMethod::find($this->incomeForm->incomeMethod);
        $amount = (float) $this->incomeForm->incomeAmount;

        $incomeData = [
            'method_id' => $this->incomeForm->incomeMethod,
            'method' => $method?->nombre ?? 'Desconocido',
            'date' => $this->incomeForm->incomeDate,
            'amount' => $amount,
            'description' => $this->incomeForm->incomeDescription,
            'savings_allocation' => $this->incomeForm->incomeSavingsAllocation,
            'savings_total' => round($this->incomeForm->incomeSavingsAllocation * $amount / 100, 2),
            'notes' => $this->incomeForm->incomeNotes,
        ];

        if ($this->incomeForm->editingId !== null) {
            $incomeId = $this->incomes[$this->incomeForm->editingId]['id'] ?? null;
            if ($incomeId !== null) {
                $incomeData['id'] = $incomeId;
            }
            $this->incomes[$this->incomeForm->editingId] = $incomeData;
        } else {
            $this->incomes[] = $incomeData;
        }

        $this->incomeForm->reset();
    }

    public function editIncome(int $index): void
    {
        $income = $this->incomes[$index] ?? null;

        if (! $income) {
            return;
        }

        $this->incomeForm->reset();
        $this->incomeForm->editingId = $index;
        $this->incomeForm->incomeMethod = (string) $income['method_id'];
        $this->incomeForm->incomeDate = $income['date'];
        $this->incomeForm->incomeAmount = (string) $income['amount'];
        $this->incomeForm->incomeDescription = $income['description'];
        $this->incomeForm->incomeSavingsAllocation = $income['savings_allocation'];
        $this->incomeForm->incomeNotes = $income['notes'];
        $this->incomeForm->modal = true;
    }

    public function removeIncome(int $index): void
    {
        unset($this->incomes[$index]);
        $this->incomes = array_values($this->incomes);
        $this->incomeForm->reset();
    }

    public function newBudgetItem(): void
    {
        $this->budgetItemForm->reset();
        $this->budgetItemForm->modal = true;
    }

    public function saveBudgetItem(): void
    {
        $this->budgetItemForm->validate([
            'budgetExpenseTypeId' => 'required|exists:expense_types,id',
            'budgetCategoryId' => 'required|exists:categories,id',
            'budgetSubcategoryId' => 'nullable|exists:subcategories,id',
            'budgetAmount' => 'required|numeric|min:0.01',
            'budgetNotes' => 'nullable|string|max:1000',
        ]);

        $category = Category::find($this->budgetItemForm->budgetCategoryId);
        $subcategory = Subcategory::find($this->budgetItemForm->budgetSubcategoryId);
        $expenseType = ExpenseType::find($this->budgetItemForm->budgetExpenseTypeId);

        $itemData = [
            'category_id' => $this->budgetItemForm->budgetCategoryId,
            'category_name' => $category?->nombre ?? 'N/A',
            'subcategory_id' => $this->budgetItemForm->budgetSubcategoryId,
            'subcategory_name' => $subcategory?->nombre ?? 'N/A',
            'expense_type_id' => $this->budgetItemForm->budgetExpenseTypeId,
            'expense_type_name' => $expenseType?->nombre ?? 'N/A',
            'presupuesto' => (float) $this->budgetItemForm->budgetAmount,
            'notas' => $this->budgetItemForm->budgetNotes ?? $subcategory?->nombre ?? 'N/A',
        ];

        if ($this->budgetItemForm->editingId !== null) {
            $itemId = $this->budgetItems[$this->budgetItemForm->editingId]['id'] ?? null;
            if ($itemId !== null) {
                $itemData['id'] = $itemId;
            }
            $this->budgetItems[$this->budgetItemForm->editingId] = $itemData;
        } else {
            $this->budgetItems[] = $itemData;
        }

        $this->budgetItemForm->reset();
    }

    public function editBudgetItem(int $index): void
    {
        $item = $this->budgetItems[$index] ?? null;

        if (! $item) {
            return;
        }

        $this->budgetItemForm->reset();
        $this->budgetItemForm->editingId = $index;
        $this->budgetItemForm->budgetExpenseTypeId = $item['expense_type_id'];
        $this->budgetItemForm->budgetCategoryId = $item['category_id'];
        $this->budgetItemForm->budgetSubcategoryId = $item['subcategory_id'];
        $this->budgetItemForm->budgetAmount = (string) $item['presupuesto'];
        $this->budgetItemForm->budgetNotes = $item['notas'];
        $this->budgetItemForm->modal = true;
    }

    public function removeBudgetItem(int $index): void
    {
        unset($this->budgetItems[$index]);
        $this->budgetItems = array_values($this->budgetItems);
        $this->budgetItemForm->reset();
    }

    public function save()
    {
        $this->validate([
            'budget.name' => 'required|string|max:255',
            'budget.start_date' => 'required|date',
            'budget.end_date' => 'required|date|after_or_equal:budget.start_date',
        ]);

        $totalPresupuesto = collect($this->budgetItems)->sum('presupuesto');

        DB::transaction(function () use ($totalPresupuesto) {
            if ($this->editId) {
                $budget = Budget::findOrFail($this->editId);
                $budget->update([
                    'nombre' => $this->budget['name'],
                    'fecha_inicio' => $this->budget['start_date'],
                    'fecha_fin' => $this->budget['end_date'],
                    'presupuesto' => $totalPresupuesto,
                    'balance' => collect($this->incomes)->sum('amount'),
                    'notas' => $this->budget['notes'],
                ]);
            } else {
                $budget = Budget::create([
                    'user_id' => Auth::user()->id ?? 1,
                    'nombre' => $this->budget['name'],
                    'fecha_inicio' => $this->budget['start_date'],
                    'fecha_fin' => $this->budget['end_date'],
                    'presupuesto' => $totalPresupuesto,
                    'gasto_real' => 0,
                    'balance' => collect($this->incomes)->sum('amount'),
                    'is_active' => true,
                    'notas' => $this->budget['notes'],
                ]);
            }

            $incomeIds = [];
            foreach ($this->incomes as $incomeData) {
                $incomeFields = [
                    'payment_method_id' => $incomeData['method_id'],
                    'fecha' => $incomeData['date'],
                    'descripcion' => $incomeData['description'],
                    'total' => $incomeData['amount'],
                    'porcentaje_ahorro' => $incomeData['savings_allocation'],
                    'total_ahorro' => $incomeData['savings_total'],
                    'notes' => $incomeData['notes'],
                ];

                if ($this->editId && isset($incomeData['id'])) {
                    Income::findOrFail($incomeData['id'])->update($incomeFields);
                    $incomeIds[] = $incomeData['id'];
                } else {
                    $income = Income::create([
                        'user_id' => Auth::user()->id ?? 1,
                        'budget_id' => $budget->id,
                        ...$incomeFields,
                        'is_active' => true,
                    ]);
                    $incomeIds[] = $income->id;
                }
            }

            if ($this->editId) {
                $budget->incomes()->whereNotIn('id', $incomeIds)->delete();
            }

            $budgetItemIds = [];
            foreach ($this->budgetItems as $itemData) {
                $itemFields = [
                    'category_id' => $itemData['category_id'],
                    'subcategory_id' => $itemData['subcategory_id'],
                    'expense_type_id' => $itemData['expense_type_id'],
                    'presupuesto' => $itemData['presupuesto'],
                    'notas' => $itemData['notas'],
                ];

                if ($this->editId && isset($itemData['id'])) {
                    BudgetItem::findOrFail($itemData['id'])->update($itemFields);
                    $budgetItemIds[] = $itemData['id'];
                } else {
                    $item = BudgetItem::create([
                        'budget_id' => $budget->id,
                        ...$itemFields,
                        'gasto_real' => 0,
                    ]);
                    $budgetItemIds[] = $item->id;
                }
            }

            if ($this->editId) {
                $budget->budgetItems()->whereNotIn('id', $budgetItemIds)->delete();
            }
        });

        $this->redirectRoute('movements.budgets', navigate: true);
    }

    public function render()
    {
        return view('livewire.modules.movements.budgets.form');
    }
}
