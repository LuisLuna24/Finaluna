<?php

namespace App\Livewire\Modules\Catalogs\Categories;

use App\Models\Category;
use App\Models\ExpenseType;
use App\Models\Icon;
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

    public $icon_id;

    public $expense_type_id;

    public $editId;

    public function create(): void
    {
        $this->resetForm();
        $this->formModal = true;
    }

    public function edit($id): void
    {
        $category = Category::findOrFail($id);

        $this->editId = $category->id;
        $this->name = $category->nombre;
        $this->icon_id = $category->icon_id;
        $this->expense_type_id = $category->expense_type_id;
        $this->formModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'icon_id' => 'required|exists:icons,id',
            'expense_type_id' => 'required|exists:expense_types,id',
        ]);

        DB::beginTransaction();

        $data = [
            'nombre' => $this->name,
            'icon_id' => $this->icon_id,
            'expense_type_id' => $this->expense_type_id,
        ];

        try {
            if ($this->editId) {
                $icon = Category::findOrFail($this->editId);
                $icon->update($data);
                $this->success(
                    'Categoría actualizado exitosamente.',
                    timeout: 2000,
                    position: 'toast-top toast-center'
                );
            } else {
                Category::create($data);
                $this->success(
                    'Categoría creada exitosamente.',
                    timeout: 2000,
                    position: 'toast-top toast-center'
                );
            }

            $this->formModal = false;
            $this->resetForm();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['name', 'icon_id', 'expense_type_id', 'editId']);
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->success('Categoría eliminada exitosamente.', timeout: 2000, position: 'toast-top toast-center');
    }

    public function toggleActive($id): void
    {
        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);
            $category->update(['is_active' => ! $category->is_active]);
            $this->success('Categoría actualizada exitosamente.', timeout: 2000, position: 'toast-top toast-center');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    public function render()
    {
        $icons = Icon::all()->map(function ($icon) {
            return [
                'id' => $icon->id,
                'name' => $icon->name,
            ];
        });

        $expenseTypes = ExpenseType::all()->map(function ($expenseType) {
            return [
                'id' => $expenseType->id,
                'name' => $expenseType->nombre,
            ];
        });

        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'nombre', 'label' => 'Nombre'],
            ['key' => 'icon.name', 'label' => 'Icono'],
            ['key' => 'expenseType.nombre', 'label' => 'Tipo de gasto'],
            ['key' => 'is_active', 'label' => 'Estatus'],
        ];
        $categories = Category::query()->with('icon', 'expenseType')->where('nombre', 'like', '%'.$this->search.'%')->paginate(15);

        return view('livewire.modules.catalogs.categories.index', compact('categories', 'headers', 'icons', 'expenseTypes'));
    }
}
