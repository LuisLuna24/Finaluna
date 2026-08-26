<?php

namespace App\Livewire\Modules\Movements\Expenses;

use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;

class View extends Component
{
    use WithPagination;

    protected $listeners = [
        'view-expense' => 'viewExpense',
    ];

    public ?int $expenseId = null;

    public $modalView = false;

    public function viewExpense(int $id): void
    {
        $this->expenseId = $id;
        $this->modalView = true;
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
        $expenses = Expense::query()->with(['paymentMethod'])->where('budget_item_id', $this->expenseId)->paginate(10);
        return view('livewire.modules.movements.expenses.view', compact('headers', 'expenses'));
    }
}
