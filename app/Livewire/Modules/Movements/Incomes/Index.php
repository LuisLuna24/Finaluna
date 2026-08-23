<?php

namespace App\Livewire\Modules\Movements\Incomes;

use App\Models\Income;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
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

    #[On('income-saved')]
    public function refreshIncomes(): void
    {
        // Re-render is triggered automatically when this method is called
    }

    public function newIncome(): void
    {
        $this->dispatch('new-income');
    }

    public function editIncome($id): void
    {
        $this->dispatch('edit-income', $id);
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
