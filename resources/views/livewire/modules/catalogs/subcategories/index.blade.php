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
                        subCategorías
                    </h1>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        Administra las subcategorías de tu sistema
                    </p>
                </div>
            </div>
        </div>
        <x-m-button wire:click="create" icon="o-plus" label="Nuevo icono" class="btn-primary" />
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
                    {{ $subcategories->total() }} subcategorías
                </span>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <x-m-table :headers="$headers" :rows="$subcategories" with-pagination>

        @scope('actions', $subcategory)
            <div class="flex items-center justify-end gap-1">
                {{-- Editar --}}
                <x-m-button icon="o-pencil" wire:click="edit({{ $subcategory->id }})" spinner
                    class="btn-sm btn-ghost text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20"
                    tooltip="Editar" />
                {{-- Eliminar --}}
                <x-m-button icon="o-trash" wire:click="delete({{ $subcategory->id }})"
                    wire:confirm="¿Estás seguro de que deseas eliminar esta subcategoría?" spinner
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
                    Sin subcategorías registradas
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Aún no has agregado ninguna subcategoría.
                </p>
                <x-m-button @click="$wire.name = ''; $wire.icon_id = null; $wire.editId = null; $wire.formModal = true"
                    icon="o-plus" label="Agregar primer icono" class="btn-sm btn-primary mt-5" />
            </div>
        </x-slot:empty>
    </x-m-table>

    <x-m-modal wire:model="formModal" title="{{ $editId ? 'Editar subcategoría' : 'Nueva subcategoría' }}"
        class="backdrop-blur">
        <x-m-form wire:submit="save">
            <x-m-input label="Nombre" wire:model="name" />
            <x-m-select label="Icono" placeholder="Seleccione un icono" wire:model="icon_id" :options="$icons"
                option-value="id" option-label="name" />
            <x-slot:actions>
                <x-m-button label="Cancelar" @click="$wire.formModal = false" />
                <x-m-button label="{{ $editId ? 'Actualizar' : 'Guardar' }}" class="btn-primary" type="submit"
                    spinner="save" />
            </x-slot:actions>
        </x-m-form>
    </x-m-modal>

</div>
