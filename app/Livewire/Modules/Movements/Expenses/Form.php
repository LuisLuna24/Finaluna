<?php

namespace App\Livewire\Modules\Movements\Expenses;

use App\Models\BudgetItem;
use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Form extends Component
{
    protected $listeners = [
        'new-expense' => 'newExpense',
        'edit-expense' => 'editExpense',
    ];

    public ?int $budgetId = null;

    public bool $expenseModal = false;

    public ?int $budgetItemId = null;

    public ?int $editingExpenseIndex = null;

    public string $expenseMethod = '';

    public string $expenseAmount = '';

    public string $expenseDate = '';

    public string $expenseDescription = '';

    public string $expenseNotes = '';

    public function mount(?int $budgetItemId = null): void
    {
        $this->budgetItemId = $budgetItemId;
    }

    public function newExpense(?int $budgetItemId = null, ?int $budgetId = null): void
    {
        $this->resetForm();

        if ($budgetItemId !== null) {
            $this->budgetItemId = $budgetItemId;
        }

        if ($budgetId !== null) {
            $this->budgetId = $budgetId;
        }

        $this->expenseModal = true;
    }

    public function editExpense(int $id): void
    {
        $expense = Expense::findOrFail($id);

        $this->editingExpenseIndex = $id;
        $this->budgetItemId = $expense->budget_item_id;
        $this->expenseMethod = (string) $expense->payment_method_id;
        $this->expenseAmount = (string) $expense->total;
        $this->expenseDate = $expense->fecha;
        $this->expenseDescription = $expense->descripcion;
        $this->expenseNotes = $expense->notes ?? '';

        $this->expenseModal = true;
    }

    public function addExpense(): void
    {
        $this->validate([
            'budgetItemId' => 'required|exists:budget_items,id',
            'expenseMethod' => 'required|exists:payment_methods,id',
            'expenseAmount' => 'required|numeric|min:0.01',
            'expenseDate' => 'required|date',
            'expenseDescription' => 'required|string|max:255',
            'expenseNotes' => 'nullable|string|max:1000',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'budget_item_id' => $this->budgetItemId,
            'payment_method_id' => $this->expenseMethod,
            'fecha' => $this->expenseDate,
            'descripcion' => $this->expenseDescription,
            'total' => $this->expenseAmount,
            'notes' => $this->expenseNotes ?: null,
            'is_active' => true,
        ];

        if ($this->editingExpenseIndex !== null) {
            Expense::findOrFail($this->editingExpenseIndex)->update($data);
            BudgetItem::where('id', $this->budgetItemId)->decrement('gasto_real', $this->expenseAmount);
        } else {
            Expense::create($data);
            BudgetItem::where('id', $this->budgetItemId)->increment('gasto_real', $this->expenseAmount);
        }

        $this->expenseModal = false;
        $this->resetForm();
        $this->dispatch('expense-saved');
    }

    public function getPaymentMethodsProperty()
    {
        return PaymentMethod::where('is_active', true)->get();
    }

    public function getBudgetItemsProperty()
    {
        return BudgetItem::query()
            ->with(['category', 'expenseType', 'budget'])
            ->where('budget_id', $this->budgetId)
            ->get();
    }

    public function render()
    {
        return view('livewire.modules.movements.expenses.form');
    }

    private function resetForm(): void
    {
        $this->editingExpenseIndex = null;
        $this->expenseMethod = '';
        $this->expenseAmount = '';
        $this->expenseDate = now()->format('Y-m-d\TH:i');
        $this->expenseDescription = '';
        $this->expenseNotes = '';
    }
}