<?php

namespace App\Livewire\Modules\Movements\Expenses;

use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public string $search = '';

    #[Reactive]
    public ?int $id = null;

    #[On('expense-saved')]
    public function refreshExpenses(): void
    {
        // Re-render is triggered automatically when this method is called
    }

    public function newExpense(?int $budgetItemId = null): void
    {
        $this->dispatch('new-expense', $budgetItemId);
    }

    public function editExpense(int $id): void
    {
        $this->dispatch('edit-expense', $id);
    }

    public function removeExpense(int $id): void
    {
        $expense = Expense::find($id)->with(['budgetItem'])->first();
        $expense?->delete();
        $this->dispatch('expense-saved');
    }

    public function removeBudgetItem(int $id): void
    {
        $budgetItem = BudgetItem::find($id)->with(['expenses', 'budget'])->first();

        if ($budgetItem->expenses()->exists() === false) {
            $budgetItem?->delete();
            Budget::where('id', $budgetItem->budget_id)->update(['presupuesto' => $budgetItem->budget->presupuesto - $budgetItem->presupuesto, 'updated_at' => now()]);
            $this->dispatch('expense-saved');
        } else {
            $this->warning(
                'No se puede eliminar la partida porque tiene gastos asociados.',
                timeout: 2000,
                position: 'toast-top toast-center'
            );
        }
    }

    public function render()
    {
        $budgetsItems = BudgetItem::query()
            ->with(['budget', 'category', 'expenseType', 'category.icon', 'expenses.paymentMethod'])
            ->where('budget_id', $this->id)
            ->where('notas', 'like', '%' . $this->search . '%')
            ->paginate(15);

        return view('livewire.modules.movements.expenses.index', compact('budgetsItems'));
    }
}
