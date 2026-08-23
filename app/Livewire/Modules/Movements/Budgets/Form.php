<?php

namespace App\Livewire\Modules\Movements\Budgets;

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\ExpenseType;
use App\Models\Income;
use App\Models\PaymentMethod;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Form extends Component
{
    public int $step = 1;

    public ?int $editId = null;

    public array $budget = [
        'name' => '',
        'start_date' => '',
        'end_date' => '',
        'notas' => '',
    ];

    // Incomes step
    public array $incomes = [];

    public bool $incomeModal = false;

    // Income Form Fields
    public ?int $editingIncomeIndex = null;

    public $incomeMethod;

    public $incomeDate;

    public $incomeAmount;

    public $incomeDescription;

    public $incomeSavingsAllocation = 10;

    public $incomeNotes;

    // Budget Items step
    public array $budgetItems = [];

    public bool $budgetItemModal = false;

    // Budget Item Form Fields
    public ?int $editingBudgetItemIndex = null;

    public $budgetCategoryId;

    public $budgetSubcategoryId;

    public $budgetExpenseTypeId;

    public $budgetAmount;

    public $budgetNotes;

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
                'notas' => $budget->notas,
            ];

            $this->incomes = $budget->incomes->map(fn (Income $income) => [
                'method_id' => $income->payment_method_id,
                'method' => $income->paymentMethod?->nombre ?? 'Desconocido',
                'date' => $income->fecha,
                'amount' => (float) $income->total,
                'description' => $income->descripcion,
                'savings_allocation' => $income->porcentaje_ahorro,
                'notes' => $income->notes,
            ])->values()->all();

            $this->budgetItems = $budget->budgetItems->map(fn (BudgetItem $item) => [
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

    public function addIncome(): void
    {
        $method = PaymentMethod::find($this->incomeMethod);

        $incomeData = [
            'method_id' => $this->incomeMethod,
            'method' => $method ? $method->nombre : 'Desconocido',
            'date' => $this->incomeDate,
            'amount' => (float) $this->incomeAmount,
            'description' => $this->incomeDescription,
            'savings_allocation' => $this->incomeSavingsAllocation,
            'notes' => $this->incomeNotes,
        ];

        if ($this->editingIncomeIndex !== null) {
            $this->incomes[$this->editingIncomeIndex] = $incomeData;
        } else {
            $this->incomes[] = $incomeData;
        }

        $this->incomeModal = false;
        $this->reset([
            'editingIncomeIndex',
            'incomeMethod',
            'incomeDate',
            'incomeAmount',
            'incomeDescription',
            'incomeNotes',
        ]);
        $this->incomeSavingsAllocation = 10;
    }

    public function editIncome($index): void
    {
        $this->editingIncomeIndex = $index;
        $income = $this->incomes[$index];
        $this->incomeMethod = $income['method_id'];
        $this->incomeDate = $income['date'];
        $this->incomeAmount = $income['amount'];
        $this->incomeDescription = $income['description'];
        $this->incomeSavingsAllocation = $income['savings_allocation'];
        $this->incomeNotes = $income['notes'];

        $this->incomeModal = true;
    }

    public function removeIncome($index): void
    {
        unset($this->incomes[$index]);
        $this->incomes = array_values($this->incomes);
    }

    public function addBudgetItem(): void
    {
        $category = Category::find($this->budgetCategoryId);
        $subcategory = Subcategory::find($this->budgetSubcategoryId);
        $expenseType = ExpenseType::find($this->budgetExpenseTypeId);

        $itemData = [
            'category_id' => $this->budgetCategoryId,
            'category_name' => $category ? $category->nombre : 'N/A',
            'subcategory_id' => $this->budgetSubcategoryId,
            'subcategory_name' => $subcategory ? $subcategory->nombre : 'N/A',
            'expense_type_id' => $this->budgetExpenseTypeId,
            'expense_type_name' => $expenseType ? $expenseType->nombre : 'N/A',
            'presupuesto' => (float) $this->budgetAmount,
            'notas' => $this->budgetNotes,
        ];

        if ($this->editingBudgetItemIndex !== null) {
            $this->budgetItems[$this->editingBudgetItemIndex] = $itemData;
        } else {
            $this->budgetItems[] = $itemData;
        }

        $this->budgetItemModal = false;
        $this->reset([
            'editingBudgetItemIndex',
            'budgetCategoryId',
            'budgetSubcategoryId',
            'budgetExpenseTypeId',
            'budgetAmount',
            'budgetNotes',
        ]);
    }

    public function editBudgetItem($index): void
    {
        $this->editingBudgetItemIndex = $index;
        $item = $this->budgetItems[$index];
        $this->budgetCategoryId = $item['category_id'];
        $this->budgetSubcategoryId = $item['subcategory_id'];
        $this->budgetExpenseTypeId = $item['expense_type_id'];
        $this->budgetAmount = $item['presupuesto'];
        $this->budgetNotes = $item['notas'];

        $this->budgetItemModal = true;
    }

    public function removeBudgetItem($index): void
    {
        unset($this->budgetItems[$index]);
        $this->budgetItems = array_values($this->budgetItems);
    }

    #[Computed]
    public function categories()
    {
        $query = Category::where('is_active', true);
        if ($this->budgetExpenseTypeId) {
            $query->where('expense_type_id', $this->budgetExpenseTypeId);
        }

        return $query->get();
    }

    #[Computed]
    public function subcategories()
    {
        if ($this->budgetCategoryId) {
            return Subcategory::where('category_id', $this->budgetCategoryId)->where('is_active', true)->get();
        }

        return [];
    }

    #[Computed]
    public function expenseTypes()
    {
        return ExpenseType::where('is_active', true)->get();
    }

    #[Computed]
    public function paymentMethods()
    {
        return PaymentMethod::where('is_active', true)->get();
    }

    public function updatedBudgetExpenseTypeId($value)
    {
        $this->budgetCategoryId = null;
        $this->budgetSubcategoryId = null;
    }

    public function updatedBudgetCategoryId($value)
    {
        $category = Category::find($value);
        if ($category && ! $this->budgetExpenseTypeId) {
            $this->budgetExpenseTypeId = $category->expense_type_id;
        }
        $this->budgetSubcategoryId = null;
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
                    'notas' => $this->budget['notas'],
                ]);

                // Sync incomes: delete old and recreate
                $budget->incomes()->delete();
                $budget->budgetItems()->delete();
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
                    'notas' => $this->budget['notas'],
                ]);
            }

            foreach ($this->incomes as $incomeData) {
                Income::create([
                    'user_id' => Auth::user()->id ?? 1,
                    'budget_id' => $budget->id,
                    'payment_method_id' => $incomeData['method_id'],
                    'fecha' => $incomeData['date'],
                    'descripcion' => $incomeData['description'],
                    'total' => $incomeData['amount'],
                    'porcentaje_ahorro' => $incomeData['savings_allocation'],
                    'notes' => $incomeData['notes'],
                    'is_active' => true,
                ]);
            }

            foreach ($this->budgetItems as $itemData) {
                BudgetItem::create([
                    'budget_id' => $budget->id,
                    'category_id' => $itemData['category_id'],
                    'subcategory_id' => $itemData['subcategory_id'],
                    'expense_type_id' => $itemData['expense_type_id'],
                    'presupuesto' => $itemData['presupuesto'],
                    'gasto_real' => 0,
                    'notas' => $itemData['notas'],
                ]);
            }
        });

        $this->redirectRoute('movements.budgets', navigate: true);
    }

    public function render()
    {
        return view('livewire.modules.movements.budgets.form');
    }
}
