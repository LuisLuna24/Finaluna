<?php

namespace App\Livewire\Modules\Pockets;

use Livewire\Component;

class Form extends Component
{

    public ?int $editId = null;

    public array $pocket = [
        'name' => '',
        'notes' => '',
    ];

    public function mount(?int $editId = null)
    {
        $this->editId = $editId;
    }

    public function render()
    {
        return view('livewire.modules.pockets.form');
    }
}
