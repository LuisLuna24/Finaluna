<?php

namespace App\Livewire\Forms\Expenses;

use App\Models\BudgetItem;
use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class ExpenseForm extends Form
{
    public bool $modal = false;

    public ?int $budgetId = null;

    public ?int $budgetItemId = null;

    public ?int $editingId = null;

    public string $expenseMethod = '';

    public string $expenseAmount = '';

    public string $expenseDate = '';

    public string $expenseDescription = '';

    public string $expenseNotes = '';

    public function openNew(?int $budgetItemId = null): void
    {
        $this->resetForm();
        $this->budgetItemId = $budgetItemId;
        $this->expenseDate = now()->format('Y-m-d\TH:i');
        $this->modal = true;
    }

    public function openEdit(int $id): void
    {
        $expense = Expense::findOrFail($id);

        $this->editingId = $id;
        $this->budgetItemId = $expense->budget_item_id;
        $this->expenseMethod = (string) $expense->payment_method_id;
        $this->expenseAmount = (string) $expense->total;
        $this->expenseDate = $expense->fecha;
        $this->expenseDescription = $expense->descripcion;
        $this->expenseNotes = $expense->notes ?? '';
        $this->modal = true;
    }

    public function save(): void
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

        if ($this->editingId !== null) {
            Expense::findOrFail($this->editingId)->update($data);
            BudgetItem::where('id', $this->budgetItemId)->decrement('gasto_real', $this->expenseAmount);
        } else {
            Expense::create($data);
            BudgetItem::where('id', $this->budgetItemId)->increment('gasto_real', $this->expenseAmount);
        }

        $this->modal = false;
        $this->reset();
    }

    public function resetForm(): void
    {
        $this->reset([
            'budgetItemId',
            'editingId',
            'expenseMethod',
            'expenseAmount',
            'expenseDate',
            'expenseDescription',
            'expenseNotes',
        ]);
    }

    public function getPaymentMethods(): array
    {
        return PaymentMethod::where('is_active', true)->get()->toArray();
    }

    public function getBudgetItems(): array
    {
        if ($this->budgetId === null) {
            return [];
        }

        return BudgetItem::query()
            ->with(['category', 'expenseType', 'budget'])
            ->where('budget_id', $this->budgetId)
            ->get()
            ->toArray();
    }
}
