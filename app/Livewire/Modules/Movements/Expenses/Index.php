<?php

namespace App\Livewire\Modules\Movements\Expenses;

use App\Models\BudgetItem;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
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
        $expense = Expense::find($id);
        $expense?->delete();
        $this->dispatch('expense-saved');
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
