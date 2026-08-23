<?php

namespace App\Livewire\Modules\Catalogs\Expenses;

use App\Models\ExpenseType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public $search = '';

    public $formModal = false;

    public $name = '';

    public $editId;

    public function create(): void
    {
        $this->resetForm();
        $this->formModal = true;
    }

    public function edit($id): void
    {
        $this->resetForm();

        $expense = ExpenseType::findOrFail($id);
        $this->editId = $expense->id;
        $this->name = $expense->nombre;
        $this->formModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            if ($this->editId) {
                $icon = ExpenseType::findOrFail($this->editId);
                $icon->update(['nombre' => $this->name]);
                $this->success(
                    'Tipo de gasto actualizado exitosamente.',
                    timeout: 2000,
                    position: 'toast-top toast-center'
                );
            } else {
                ExpenseType::create(['nombre' => $this->name]);
                $this->success(
                    'Tipo de gasto creado exitosamente.',
                    timeout: 2000,
                    position: 'toast-top toast-center'
                );
            }

            $this->resetForm();
            $this->formModal = false;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['name', 'editId']);
    }

    public function delete($id)
    {
        ExpenseType::findOrFail($id)->delete();
        $this->success('Tipo de gasto eliminado exitosamente.', timeout: 2000, position: 'toast-top toast-center');
    }

    public function toggleActive($id): void
    {
        DB::beginTransaction();
        try {
            $icon = ExpenseType::findOrFail($id);
            $icon->update(['is_active' => ! $icon->is_active]);
            $this->success('Tipo de gasto actualizado exitosamente.', timeout: 2000, position: 'toast-top toast-center');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    public function render()
    {
        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'nombre', 'label' => 'Nombre'],
            ['key' => 'is_active', 'label' => 'Estatus'],
        ];
        $expenses = ExpenseType::query()->where('nombre', 'like', '%'.$this->search.'%')->paginate(15);

        return view('livewire.modules.catalogs.expenses.index', compact('expenses', 'headers'));
    }
}
