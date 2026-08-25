<x-layouts::app :title="__('Gastos')">
    @livewire('modules.movements.expenses.index', ['id' => $id ?? null])
</x-layouts::app>
