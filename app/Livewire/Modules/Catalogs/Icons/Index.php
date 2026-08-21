<?php

namespace App\Livewire\Modules\Catalogs\Icons;

use App\Models\Icon;
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
    public $icon = '';
    public $editId;

    public function create(): void
    {
        $this->reset(['name', 'icon', 'editId']);
        $this->formModal = true;
    }

    public function edit($id): void
    {
        $icon = Icon::findOrFail($id);

        $this->editId = $icon->id;
        $this->name = $icon->name;
        $this->icon = $icon->icon;
        $this->formModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($this->editId) {
            $icon = Icon::findOrFail($this->editId);
            $icon->update(['name' => $this->name, 'icon' => $this->icon]);
            $this->success(
                'Icono actualizado exitosamente.',
                timeout: 2000,
                position: 'toast-top toast-center'
            );
        } else {
            Icon::create(['name' => $this->name, 'icon' => $this->icon]);
            $this->success(
                'Icono creado exitosamente.',
                timeout: 2000,
                position: 'toast-top toast-center'
            );
        }

        $this->formModal = false;
        $this->reset(['name', 'editId', 'icon']);
    }

    public function delete($id)
    {
        Icon::findOrFail($id)->delete();
        $this->success('Icono eliminado exitosamente.', timeout: 2000, position: 'toast-top toast-center');
    }

    public function render()
    {
        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Nombre'],
            ['key' => 'icon', 'label' => 'Icono'],
        ];
        $icons = Icon::query()->where('name', 'like', '%' . $this->search . '%')->paginate(15);
        return view('livewire.modules.catalogs.icons.index', compact('icons', 'headers'));
    }
}
