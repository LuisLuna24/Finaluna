<?php

namespace App\Livewire\Modules\Movements\Incomes;

use App\Livewire\Forms\Incomes\IncomeForm;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    #[Reactive]
    public ?int $id = null;

    public IncomeForm $incomeForm;

    public function newIncome(): void
    {
        $this->incomeForm->budgetId = $this->id;
        $this->incomeForm->openNew($this->id);
    }

    public function editIncome($id): void
    {
        $this->incomeForm->budgetId = $this->id;
        $this->incomeForm->openEdit($id);
    }

    public function saveIncome(): void
    {
        $this->incomeForm->save();
        $this->dispatch('income-saved');
    }

    public function removeIncome($id): void
    {
        $income = Income::find($id);
        $income->delete();
    }

    public function render()
    {
        $incomes = Income::query()->with(['budget', 'user'])->where('budget_id', $this->id)->where('user_id', Auth::user()->id)
            ->where('descripcion', 'like', '%'.$this->search.'%')->paginate(15);

        return view('livewire.modules.movements.incomes.index', compact('incomes'));
    }
}
