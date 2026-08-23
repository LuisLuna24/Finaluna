<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10">
                    <x-m-icon name="o-swatch" class="h-6 w-6 text-primary" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Categorías
                    </h1>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        Administra las categorías de tu sistema
                    </p>
                </div>
            </div>
        </div>
        <x-m-button wire:click="create" icon="o-plus" label="Nueva categoría" class="btn-primary" />
    </div>

    {{-- Toolbar --}}
    <div class="border-b p-4 shadow-sm ">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-md">
                <x-m-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                    placeholder="Buscar icono..." clearable />
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <x-m-icon name="o-squares-2x2" class="h-5 w-5" />
                <span>
                    {{ $categories->total() }} categorías
                </span>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <x-m-table :headers="$headers" :rows="$categories" with-pagination>

        @scope('cell_icon.name', $category)
            <x-m-icon :name="'o-' . $category->icon->icon" />
        @endscope
        @scope('actions', $category)
            <div class="flex items-center justify-end gap-1">
                {{-- Editar --}}
                <x-m-button icon="o-pencil" wire:click="edit({{ $category->id }})" spinner tooltip="Editar" />
                {{-- Eliminar --}}
                <x-m-button icon="o-trash" wire:click="delete({{ $category->id }})"
                    wire:confirm="¿Estás seguro de que deseas eliminar esta categoría?" spinner
                    class="btn-sm btn-ghost text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                    tooltip="Eliminar" />
            </div>
        @endscope
        @scope('cell_is_active', $category)
            <x-m-toggle wire:click="toggleActive({{ $category->id }})" :checked="$category->is_active" />
        @endscope

        {{-- Sin registros --}}
        <x-slot:empty>
            <div class="flex flex-col items-center justify-center py-14">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-700">
                    <x-m-icon name="o-cube" class="h-8 w-8 text-gray-400 dark:text-gray-500" />
                </div>
                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">
                    Sin categorías registradas
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Aún no has agregado ninguna categoría.
                </p>
                <x-m-button wire:click="create" icon="o-plus" label="Nueva categoría" class="btn-primary" />
            </div>
        </x-slot:empty>
    </x-m-table>

    <x-m-modal wire:model="formModal" title="{{ $editId ? 'Editar categoría' : 'Nueva categoría' }}"
        class="backdrop-blur">
        <x-m-form wire:submit="save">
            <x-m-input label="Nombre" wire:model="name" />
            <x-m-select label="Icono" placeholder="Seleccione un icono" wire:model="icon_id" :options="$icons"
                option-value="id" option-label="name" />
            <x-m-select label="Tipo de gasto" placeholder="Seleccione un tipo de gasto" wire:model="expense_type_id"
                :options="$expenseTypes" option-value="id" option-label="name" />
            <x-slot:actions>
                <x-m-button label="Cancelar" @click="$wire.formModal = false" />
                <x-m-button label="{{ $editId ? 'Actualizar' : 'Guardar' }}" class="btn-primary" type="submit"
                    spinner="save" />
            </x-slot:actions>
        </x-m-form>
    </x-m-modal>

</div>
