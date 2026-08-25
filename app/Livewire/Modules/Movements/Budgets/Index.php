<?php

namespace App\Livewire\Modules\Movements\Budgets;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public $search = '';

    public function newIncome($id): void
    {
        $this->dispatch('new-income', $id);
    }

    public function editIncome($id): void
    {
        $this->dispatch('edit-income', $id);
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
            ->where('nombre', 'like', '%' . $this->search . '%')->paginate(15);

        $budgets->each(function ($budget) {
            $budget->gasto = $budget->budgetItems->sum(function ($item) {
                return $item->expenses->sum('total');
            });
            $budget->ingreso = $budget->incomes->sum('total');
        });

        return view('livewire.modules.movements.budgets.index', compact('budgets', 'headers'));
    }
}
