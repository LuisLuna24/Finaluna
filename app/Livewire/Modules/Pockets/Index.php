<?php

namespace App\Livewire\Modules\Pockets;

use App\Livewire\Forms\Pockets\PocketItemForm;
use App\Models\Pocket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public PocketItemForm $pocketItemForm;

    public $search = '';

    #[On('pocketItem-saved')]
    public function refreshPockets(): void
    {
        // Re-render is triggered automatically when this method is called
    }

    public function deletePocket(int $id): void
    {
        $pocket = Pocket::findOrFail($id);
        $pocket->delete();

        $this->success('Apartado eliminado correctamente.');
    }

    public function addMoney(int $pocketId): void
    {
        $this->pocketItemForm->openNew($pocketId);
        $this->pocketItemForm->editingId = null;
    }

    public function savePocketItem(): void
    {
        $this->pocketItemForm->save();
        $this->success('Abono guardado correctamente.');
        $this->pocketItemForm->modal = false;
    }

    public function render()
    {
        $pockets = Pocket::query()->with(['user', 'pocketItems'])->where('user_id', Auth::user()->id)
            ->where('nombre', 'like', '%'.$this->search.'%')->paginate(15);

        $pockets->each(function ($pocket) {
            $pocket->apartado = $pocket->pocketItems->sum('monto');
        });

        return view('livewire.modules.pockets.index', compact('pockets'));
    }
}
