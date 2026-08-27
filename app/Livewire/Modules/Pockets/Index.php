<?php

namespace App\Livewire\Modules\Pockets;

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

    public $search = '';

    #[On('pocketItem-saved')]
    public function refreshPockets(): void
    {
        // Re-render is triggered automatically when this method is called
    }

    public function render()
    {
        $pockets = Pocket::query()->with(['user', 'pocketItems'])->where('user_id', Auth::user()->id)
            ->where('nombre', 'like', '%' . $this->search . '%')->paginate(15);

        $pockets->each(function ($pocket) {
            $pocket->apartado = $pocket->pocketItems->sum('total');
        });
        return view('livewire.modules.pockets.index', compact('pockets'));
    }
}
