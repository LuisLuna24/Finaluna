<x-layouts::app :title="__('Gestionar Apartado')">
    @livewire('modules.pockets.form', ['editId' => $id ?? null])
</x-layouts::app>
