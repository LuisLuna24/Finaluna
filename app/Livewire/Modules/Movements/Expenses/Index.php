<?php

namespace App\Livewire\Modules\Movements\Expenses;

use App\Livewire\Forms\Expenses\ExpenseForm;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public string $search = '';

    public ?int $id = null;

    public ExpenseForm $expenseForm;

    public bool $modalView = false;

    public ?int $expenseId = null;

    public function newExpense(?int $budgetItemId = null): void
    {
        $this->expenseForm->budgetId = $this->id;
        $this->expenseForm->openNew($budgetItemId);
    }

    public function editExpense(int $id): void
    {
        $this->expenseForm->budgetId = $this->id;
        $this->expenseForm->openEdit($id);
    }

    public function saveExpense(): void
    {
        $this->expenseForm->save();
        $this->dispatch('expense-saved');
    }

    public function viewExpense(int $expenseId): void
    {
        $this->expenseId = $expenseId;
        $this->modalView = true;
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
        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'descripcion', 'label' => 'Descripción'],
            ['key' => 'total', 'label' => 'Monto'],
            ['key' => 'fecha', 'label' => 'Fecha'],
            ['key' => 'paymentMethod.nombre', 'label' => 'Método de pago']
        ];

        $expenses = $this->expenseId
            ? Expense::query()->with(['paymentMethod'])->where('budget_item_id', $this->expenseId)->paginate(10)
            : Expense::query()->whereRaw('0 = 1')->paginate(10);

        $budgetsItems = BudgetItem::query()
            ->with(['budget', 'category', 'expenseType', 'category.icon', 'expenses.paymentMethod'])
            ->where('budget_id', $this->id)
            ->where('notas', 'like', '%' . $this->search . '%')
            ->paginate(15);

        return view('livewire.modules.movements.expenses.index', compact('budgetsItems', 'headers', 'expenses'));
    }
}
