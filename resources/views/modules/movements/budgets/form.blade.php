<x-layouts::app :title="__('Formulario presupuestos')">
    @livewire('modules.movements.budgets.form', ['editId' => $id ?? null])
</x-layouts::app>
