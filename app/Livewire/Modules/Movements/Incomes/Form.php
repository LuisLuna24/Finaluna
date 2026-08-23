<?php

namespace App\Livewire\Modules\Movements\Incomes;

use App\Models\Income;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Form extends Component
{
    protected $listeners = [
        'new-income' => 'newIncome',
        'edit-income' => 'editIncome',
    ];

    public bool $incomeModal = false;

    public ?int $budgetId = null;

    public ?int $editingIncomeIndex = null;

    public string $incomeMethod = '';

    public string $incomeAmount = '';

    public string $incomeDate = '';

    public string $incomeDescription = '';

    public int $incomeSavingsAllocation = 0;

    public string $incomeNotes = '';

    public function mount(?int $id = null): void
    {
        $this->budgetId = $id;
    }

    public function newIncome(?int $id = null): void
    {
        $this->resetForm();
        if ($id) {
            $this->budgetId = $id;
        }
        $this->incomeModal = true;
    }

    public function editIncome(int $id): void
    {
        $income = Income::findOrFail($id);

        $this->editingIncomeIndex = $id;
        $this->incomeMethod = (string) $income->payment_method_id;
        $this->incomeAmount = (string) $income->total;
        $this->incomeDate = $income->fecha;
        $this->incomeDescription = $income->descripcion;
        $this->incomeSavingsAllocation = $income->porcentaje_ahorro;
        $this->incomeNotes = $income->notes ?? '';

        $this->incomeModal = true;
    }

    public function addIncome(): void
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

        if ($this->editingIncomeIndex !== null) {
            Income::findOrFail($this->editingIncomeIndex)->update($data);
        } else {
            Income::create($data);
        }

        $this->incomeModal = false;
        $this->resetForm();
        $this->dispatch('income-saved');
    }

    public function getPaymentMethodsProperty()
    {
        return PaymentMethod::where('is_active', true)->get();
    }

    public function render()
    {
        return view('livewire.modules.movements.incomes.form');
    }

    private function resetForm(): void
    {
        $this->editingIncomeIndex = null;
        $this->incomeMethod = '';
        $this->incomeAmount = '';
        $this->incomeDate = now()->format('Y-m-d\TH:i');
        $this->incomeDescription = '';
        $this->incomeSavingsAllocation = 0;
        $this->incomeNotes = '';
    }
}
