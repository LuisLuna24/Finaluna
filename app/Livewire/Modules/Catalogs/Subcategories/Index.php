<?php

namespace App\Livewire\Modules\Catalogs\Subcategories;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public $search = '';

    public $searchCategory;

    public $formModal = false;

    public $name = '';

    public $names = [];

    public $newName = '';

    public $category_id;

    public $editId;

    public $existingSubcategories = [];

    public function updatedCategoryId($value)
    {
        if ($value) {
            $this->existingSubcategories = Subcategory::where('category_id', $value)->pluck('nombre')->toArray();
        } else {
            $this->existingSubcategories = [];
        }
    }

    public function create(): void
    {
        $this->resetForm();
        $this->formModal = true;
    }

    public function edit($id): void
    {
        $this->resetForm();

        $subcategory = Subcategory::findOrFail($id);
        $this->editId = $subcategory->id;
        $this->name = $subcategory->nombre;
        $this->category_id = $subcategory->category_id;
        $this->formModal = true;
    }

    public function addName()
    {
        $this->validate(['newName' => 'required|string|max:255']);
        if (! in_array($this->newName, $this->names)) {
            $this->names[] = $this->newName;
        }
        $this->newName = '';
    }

    public function removeName($index)
    {
        unset($this->names[$index]);
        $this->names = array_values($this->names);
    }

    public function save()
    {
        $this->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        DB::beginTransaction();
        try {
            if ($this->editId) {
                $this->validate(['name' => 'required|string|max:255']);
                $subcategory = Subcategory::findOrFail($this->editId);
                $subcategory->update(['nombre' => $this->name, 'category_id' => $this->category_id]);
                $this->success(
                    'Subcategoría actualizada exitosamente.',
                    timeout: 2000,
                    position: 'toast-top toast-center'
                );
            } else {
                $this->validate(['names' => 'required|array|min:1'], ['names.min' => 'Debe agregar al menos una subcategoría.']);
                foreach ($this->names as $n) {
                    Subcategory::create(['nombre' => $n, 'category_id' => $this->category_id]);
                }
                $this->success(
                    'Subcategorías creadas exitosamente.',
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
        $this->reset(['name', 'names', 'newName', 'editId', 'category_id', 'existingSubcategories']);
    }

    public function delete($id)
    {
        Subcategory::findOrFail($id)->delete();
        $this->success('Subcategoría eliminada exitosamente.', timeout: 2000, position: 'toast-top toast-center');
    }

    public function toggleActive($id): void
    {
        DB::beginTransaction();
        try {
            $category = Subcategory::findOrFail($id);
            $category->update(['is_active' => ! $category->is_active]);
            $this->success('Subcategoría actualizada exitosamente.', timeout: 2000, position: 'toast-top toast-center');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    public function render()
    {
        $categories = Category::all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->nombre,
            ];
        });

        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'nombre', 'label' => 'Nombre'],
            ['key' => 'category.nombre', 'label' => 'Categoría'],
            ['key' => 'is_active', 'label' => 'Estatus'],
        ];
        $subcategories = Subcategory::query()->where('nombre', 'like', '%'.$this->search.'%')->when($this->searchCategory, function ($query) {
            $query->where('category_id', $this->searchCategory);
        })->paginate(15);

        return view('livewire.modules.catalogs.subcategories.index', compact('subcategories', 'headers', 'categories'));
    }
}
