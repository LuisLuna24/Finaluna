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
                        Subcategorías
                    </h1>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        Administra las subcategorías de tu sistema
                    </p>
                </div>
            </div>
        </div>
        <x-m-button wire:click="create" icon="o-plus" label="Nueva subcategoría" class="btn-primary" />
    </div>

    {{-- Toolbar --}}
    <div class="border-b p-4 shadow-sm ">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-md flex items-end gap-2">
                <x-m-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                    placeholder="Buscar icono..." clearable />
                <x-m-select label="Categoría" inline placeholder="Todas" wire:model.live="searchCategory"
                    :options="$categories" option-value="id" option-label="name" />
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
        @scope('cell_is_active', $subcategory)
            <x-m-toggle wire:click="toggleActive({{ $subcategory->id }})" :checked="$subcategory->is_active" />
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
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Aún no has agregado ninguna subcategoría.
                </p>
                <x-m-button wire:click="create" icon="o-plus" label="Nueva subcategoría" class="btn-primary" />
            </div>
        </x-slot:empty>
    </x-m-table>

    <x-m-modal wire:model="formModal" title="{{ $editId ? 'Editar subcategoría' : 'Nuevas subcategorías' }}"
        class="backdrop-blur">
        <x-m-form wire:submit="save">
            <x-m-select label="Categoría" placeholder="Seleccione una categoría" wire:model.live="category_id"
                :options="$categories" option-value="id" option-label="name" />

            @if ($editId)
                <x-m-input label="Nombre" wire:model="name" />
            @else
                @if (count($existingSubcategories) > 0)
                    <div class="mt-2 text-sm text-gray-500">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Subcategorías actuales en la
                            categoría:</span>
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach ($existingSubcategories as $exist)
                                <span
                                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-800 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-300">{{ $exist }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex gap-2 items-end mt-2">
                    <div class="flex-1">
                        <x-m-input label="Nombre de subcategoría" wire:model="newName"
                            wire:keydown.enter.prevent="addName" />
                    </div>
                    <x-m-button icon="o-plus" class="btn-primary" wire:click="addName" type="button" />
                </div>

                @if (count($names) > 0)
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold mb-2">Subcategorías a agregar:</h4>
                        <ul class="space-y-2">
                            @foreach ($names as $index => $n)
                                <li
                                    class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 p-2 rounded-lg border dark:border-gray-700">
                                    <span>{{ $n }}</span>
                                    <x-m-button icon="o-trash" class="btn-sm btn-ghost text-red-500"
                                        wire:click="removeName({{ $index }})" type="button" />
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @error('names')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            @endif

            <x-slot:actions>
                <x-m-button label="Cancelar" @click="$wire.formModal = false" />
                <x-m-button label="{{ $editId ? 'Actualizar' : 'Guardar' }}" class="btn-primary" type="submit"
                    spinner="save" />
            </x-slot:actions>
        </x-m-form>
    </x-m-modal>

</div>
