<?php

namespace App\Livewire\Modules\Movements\Budgets;

use App\Livewire\Forms\Expenses\ExpenseForm;
use App\Livewire\Forms\Incomes\IncomeForm;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public $search = '';

    public ExpenseForm $expenseForm;

    public IncomeForm $incomeForm;

    #[On('expense-saved')]
    #[On('income-saved')]
    public function refreshBudgets(): void
    {
        // Re-render is triggered automatically when this method is called
    }

    public function newIncome(int $budgetId): void
    {
        $this->incomeForm->budgetId = $budgetId;
        $this->incomeForm->openNew($budgetId);
    }

    public function editIncome(int $id): void
    {
        $this->incomeForm->budgetId = null;
        $this->incomeForm->openEdit($id);
    }

    public function saveIncome(): void
    {
        $this->incomeForm->save();
        $this->dispatch('income-saved');
    }

    public function newExpense(?int $budgetId = null): void
    {
        $this->expenseForm->budgetId = $budgetId;
        $this->expenseForm->openNew();
    }

    public function editExpense(int $id): void
    {
        $this->expenseForm->budgetId = null;
        $this->expenseForm->openEdit($id);
    }

    public function saveExpense(): void
    {
        $this->expenseForm->save();
        $this->dispatch('expense-saved');
    }

    public function deleteBudget($id): void
    {
        $budget = Budget::find($id);
        $budget->delete();
        $this->toast('success', 'Presupuesto eliminado correctamente');
    }

    public function render()
    {
        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'nombre', 'label' => 'Nombre'],
            ['key' => 'fecha_inicio', 'label' => 'Fecha inicio'],
            ['key' => 'fecha_fin', 'label' => 'Fecha fin'],
            ['key' => 'presupuesto', 'label' => 'Monto'],
            ['key' => 'is_active', 'label' => 'Estatus'],
        ];
        $budgets = Budget::query()->with(['budgetItems.expenses', 'incomes', 'user'])->where('user_id', Auth::user()->id)
            ->where('nombre', 'like', '%'.$this->search.'%')->paginate(15);

        $budgets->each(function ($budget) {
            $budget->gasto = $budget->budgetItems->sum(function ($item) {
                return $item->expenses->sum('total');
            });
            $budget->ingreso = $budget->incomes->sum('total');
            $budget->total_ahorro = $budget->incomes->sum('total_ahorro');
        });

        return view('livewire.modules.movements.budgets.index', compact('budgets', 'headers'));
    }
}
