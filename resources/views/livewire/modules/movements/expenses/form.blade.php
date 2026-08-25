<x-m-modal wire:model="expenseModal" title="{{ $editingExpenseIndex !== null ? 'Editar gasto' : 'Registrar gasto' }}">
    <div class="space-y-6">
        
        {{-- BUDGET ITEM --}}
        @if ($budgetItemId === null)
            <div class="rounded-2xl border border-base-300 bg-base-200/30 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-error/10">
                        <x-m-icon name="o-wallet" class="h-5 w-5 text-error" />
                    </div>
                    <div>
                        <p class="font-semibold">Partida presupuestal</p>
                        <p class="text-xs text-base-content/50">Categoría a la que pertenece este gasto</p>
                    </div>
                </div>
                <x-m-select label="Partida presupuestal" :options="$this->budgetItems" option-label="notas" option-value="id"
                    wire:model="budgetItemId" placeholder="Selecciona una partida..." />
            </div>
        @endif

        {{-- BASIC DATA --}}
        <div class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-m-select label="Método de pago" :options="$this->paymentMethods" option-label="nombre" option-value="id"
                    wire:model="expenseMethod" placeholder="Selecciona un método..." />
                <x-m-input label="Monto" prefix="$" wire:model.live="expenseAmount" type="number" step="0.01"
                    placeholder="0.00" />
            </div>
            <x-m-datetime label="Fecha del gasto" wire:model="expenseDate" />
            <x-m-input label="Descripción" placeholder="Ej. Supermercado, gasolina, renta..."
                wire:model="expenseDescription" />
        </div>

        {{-- NOTES --}}
        <x-m-textarea label="Notas (opcional)" placeholder="Agrega información adicional sobre este gasto..."
            wire:model="expenseNotes" rows="3" />
    </div>

    <x-slot:actions>
        <x-m-button label="Cancelar" wire:click="$set('expenseModal', false)" />
        <x-m-button label="{{ $editingExpenseIndex !== null ? 'Guardar cambios' : 'Registrar gasto' }}"
            icon="{{ $editingExpenseIndex !== null ? 'o-check' : 'o-plus-circle' }}" class="btn-error"
            wire:click="addExpense" />
    </x-slot:actions>

</x-m-modal>
