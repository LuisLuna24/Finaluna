<?php

namespace App\Livewire\Forms\Incomes;

use App\Models\Income;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class IncomeForm extends Form
{
    public bool $modal = false;

    public ?int $budgetId = null;

    public ?int $editingId = null;

    public string $incomeMethod = '';

    public string $incomeAmount = '';

    public string $incomeDate = '';

    public string $incomeDescription = '';

    public int $incomeSavingsAllocation = 0;

    public string $incomeNotes = '';

    public function openNew(?int $budgetId = null): void
    {
        $this->reset();
        if ($budgetId) {
            $this->budgetId = $budgetId;
        }
        $this->incomeDate = now()->format('Y-m-d\TH:i');
        $this->modal = true;
    }

    public function openEdit(int $id): void
    {
        $income = Income::findOrFail($id);

        $this->editingId = $id;
        $this->incomeMethod = (string) $income->payment_method_id;
        $this->incomeAmount = (string) $income->total;
        $this->incomeDate = $income->fecha;
        $this->incomeDescription = $income->descripcion;
        $this->incomeSavingsAllocation = $income->porcentaje_ahorro;
        $this->incomeNotes = $income->notes ?? '';
        $this->modal = true;
    }

    public function save(): void
    {
        $this->validate([
            'incomeMethod' => 'required|exists:payment_methods,id',
            'incomeAmount' => 'required|numeric|min:0.01',
            'incomeDate' => 'required|date',
            'incomeDescription' => 'required|string|max:255',
            'incomeSavingsAllocation' => 'required|integer|min:0|max:100',
            'incomeNotes' => 'nullable|string|max:1000',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'budget_id' => $this->budgetId,
            'payment_method_id' => $this->incomeMethod,
            'fecha' => $this->incomeDate,
            'descripcion' => $this->incomeDescription,
            'total' => $this->incomeAmount,
            'porcentaje_ahorro' => $this->incomeSavingsAllocation,
            'notes' => $this->incomeNotes ?: null,
            'is_active' => true,
        ];

        if ($this->editingId !== null) {
            Income::findOrFail($this->editingId)->update($data);
        } else {
            Income::create($data);
        }

        $this->modal = false;
        $this->reset();
    }

    public function getPaymentMethods(): array
    {
        return PaymentMethod::where('is_active', true)->get()->toArray();
    }
}
