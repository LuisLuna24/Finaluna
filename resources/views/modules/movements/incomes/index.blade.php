<x-layouts::app :title="__('Ingresos')">
    @livewire('modules.movements.incomes.index', ['id' => $id ?? null])
</x-layouts::app>
