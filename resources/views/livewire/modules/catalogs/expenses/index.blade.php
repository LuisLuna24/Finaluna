<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10">
                    <x-m-icon name="o-credit-card" class="h-6 w-6 text-primary" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Tipos de gastos
                    </h1>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        Administra los tipos de gastos de tu sistema
                    </p>
                </div>
            </div>
        </div>
        <x-m-button wire:click="create" icon="o-plus" label="Nuevo tipo de gasto" class="btn-primary" />
    </div>
    {{-- Toolbar --}}
    <div class="border-b p-4 shadow-sm ">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-md">
                <x-m-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                    placeholder="Buscar tipo de gasto..." clearable />
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <x-m-icon name="o-squares-2x2" class="h-5 w-5" />
                <span>
                    {{ $expenses->total() }} tipos de gastos
                </span>
            </div>
        </div>
    </div>
    <x-m-table :headers="$headers" :rows="$expenses">
        @scope('actions', $expense)
            <div class="flex items-center justify-end gap-1">
                {{-- Editar --}}
                <x-m-button icon="o-pencil" wire:click="edit({{ $expense->id }})" spinner
                    class="btn-sm btn-ghost text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20"
                    tooltip="Editar" />
                {{-- Eliminar --}}
                <x-m-button icon="o-trash" wire:click="delete({{ $expense->id }})"
                    wire:confirm="¿Estás seguro de que deseas eliminar este tipo de gasto?" spinner
                    class="btn-sm btn-ghost text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                    tooltip="Eliminar" />
            </div>
        @endscope

        @scope('cell_is_active', $expense)
            <x-m-toggle wire:click="toggleActive({{ $expense->id }})" :checked="$expense->is_active" />
        @endscope
        {{-- Sin registros --}}
        <x-slot:empty>
            <div class="flex flex-col items-center justify-center py-14">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-700">
                    <x-m-icon name="o-cube" class="h-8 w-8 text-gray-400 dark:text-gray-500" />
                </div>
                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">
                    Sin tipos de gastos registrados
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Aún no has agregado ningún tipo de gasto.
                </p>
                <x-m-button wire:click="create" icon="o-plus" label="Nuevo tipo de gasto" class="btn-primary" />
            </div>
        </x-slot:empty>
    </x-m-table>

    <x-m-modal wire:model.live="formModal" title="{{ $editId ? 'Editar' : 'Crear' }} Método de pago">
        <x-m-form wire:submit="save">
            <div class="grid grid-cols-1 gap-4">
                <x-m-input label="Nombre" wire:model="name" />
            </div>
            <x-slot:actions>
                <x-m-button label="Cancelar" @click="$wire.formModal = false" class="btn-ghost" />
                <x-m-button label="Guardar" type="submit" class="btn-prim" />
            </x-slot:actions>
        </x-m-form>
    </x-m-modal>
</div>
