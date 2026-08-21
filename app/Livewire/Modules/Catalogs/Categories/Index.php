<?php

namespace App\Livewire\Modules\Catalogs\Categories;

use App\Models\Category;
use App\Models\Icon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination;
    use Toast;

    public $search = '';

    public $formModal = false;
    public $name = '';
    public $icon_id;
    public $editId;

    public function create(): void
    {
        $this->reset(['name', 'icon', 'editId']);
        $this->formModal = true;
    }

    public function edit($id): void
    {
        $category = Category::findOrFail($id);

        $this->editId = $category->id;
        $this->name = $category->name;
        $this->icon_id = $category->icon_id;
        $this->formModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'icon_id' => 'required|exists:icons,id'
        ]);

        DB::beginTransaction();
        try {
            if ($this->editId) {
                $icon = Category::findOrFail($this->editId);
                $icon->update(['nombre' => $this->name, 'icon_id' => $this->icon_id]);
                $this->success(
                    'Categoría actualizado exitosamente.',
                    timeout: 2000,
                    position: 'toast-top toast-center'
                );
            } else {
                Category::create(['nombre' => $this->name, 'icon_id' => $this->icon_id]);
                $this->success(
                    'Categoría creada exitosamente.',
                    timeout: 2000,
                    position: 'toast-top toast-center'
                );
            }

            $this->formModal = false;
            $this->reset(['name', 'editId', 'icon_id']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
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
            $category->update(['is_active' => !$category->is_active]);
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

        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'nombre', 'label' => 'Nombre'],
            ['key' => 'icon.name', 'label' => 'Icono'],
            ['key' => 'is_active', 'label' => 'Estatus'],
        ];
        $categories = Category::query()->with('icon')->where('nombre', 'like', '%' . $this->search . '%')->paginate(15);
        return view('livewire.modules.catalogs.categories.index', compact('categories', 'headers', 'icons'));
    }
}
