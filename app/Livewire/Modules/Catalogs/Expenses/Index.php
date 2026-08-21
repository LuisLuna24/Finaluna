<?php

namespace App\Livewire\Modules\Catalogs\Expenses;

use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination;
    use Toast;

    public $search = '';
    public function render()
    {
        return view('livewire.modules.catalogs.expenses.index');
    }
}
