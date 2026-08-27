<x-m-modal wire:model="budgetItemForm.modal"
    title="{{ $budgetItemForm->editingId !== null ? 'Editar Partida Presupuestal' : 'Agregar Partida Presupuestal' }}">
    <div class="space-y-6">
        {{-- FORM FIELDS --}}
        <div class="space-y-4">
            <div class="space-y-2">
                <x-m-group label="Tipo de Gasto" wire:model.live="budgetItemForm.budgetExpenseTypeId" :options="$budgetItemForm->getExpenseTypes()"
                    option-label="nombre" option-value="id" class="[&:checked]:!btn-primary max-w-full" />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-m-select label="Categoría" :options="$budgetItemForm->getCategories()" option-label="nombre" option-value="id"
                    wire:model.live="budgetItemForm.budgetCategoryId" placeholder="Selecciona..." />
                <x-m-select label="Subcategoría" :options="$budgetItemForm->getSubcategories()" option-label="nombre" option-value="id"
                    wire:model="budgetItemForm.budgetSubcategoryId" placeholder="Opcional..." />
            </div>

            <x-m-input label="Presupuesto Asignado" prefix="$" wire:model="budgetItemForm.budgetAmount"
                type="number" step="0.01" placeholder="0.00" />

            <x-m-textarea label="Notas (opcional)" placeholder="Agrega información adicional sobre esta partida..."
                wire:model="budgetItemForm.budgetNotes" rows="3" />
        </div>
    </div>

    <x-slot:actions>
        <x-m-button label="Cancelar" wire:click="$set('budgetItemForm.modal', false)" />
        <x-m-button label="{{ $budgetItemForm->editingId !== null ? 'Guardar cambios' : 'Agregar partida' }}"
            icon="{{ $budgetItemForm->editingId !== null ? 'o-check' : 'o-plus-circle' }}" class="btn-primary"
            wire:click="saveBudgetItem"spinner="saveBudgetItem" />
    </x-slot:actions>
</x-m-modal>
