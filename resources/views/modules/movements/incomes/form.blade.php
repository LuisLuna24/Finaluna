<x-m-modal wire:model="incomeForm.modal" title="{{ $incomeForm->editingId !== null ? 'Editar ingreso' : 'Registrar ingreso' }}">
    <div class="space-y-6">
        {{-- BASIC DATA --}}
        <div class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-m-select label="Método de pago" :options="$incomeForm->getPaymentMethods()" option-label="nombre" option-value="id"
                    wire:model="incomeForm.incomeMethod" placeholder="Selecciona un método..." />
                <x-m-input label="Monto" prefix="$" wire:model.live="incomeForm.incomeAmount" type="number" step="0.01"
                    placeholder="0.00" />
            </div>
            <x-m-datetime label="Fecha del ingreso" wire:model="incomeForm.incomeDate" />
            <x-m-input label="Descripción" placeholder="Ej. Pago de cliente, salario, venta..."
                wire:model="incomeForm.incomeDescription" />
        </div>

        {{-- SAVINGS --}}
        <div class="rounded-2xl border border-base-300 bg-base-200/30 p-5">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10">
                        <x-m-icon name="o-banknotes" class="h-5 w-5 text-primary" />
                    </div>
                    <div>
                        <p class="font-semibold">Ahorro</p>
                        <p class="text-xs text-base-content/50">Porcentaje destinado al ahorro</p>
                    </div>
                </div>
                <span class="text-2xl font-bold text-primary">
                    {{ $incomeForm->incomeSavingsAllocation }}%
                </span>
            </div>

            <div class="mt-5">
                <x-m-range wire:model.live="incomeForm.incomeSavingsAllocation" min="0" max="100" class="range-primary"
                    step="1" />
            </div>

            <div class="mt-4 flex items-start gap-3 rounded-xl bg-primary/10 p-4 text-sm text-primary">
                <x-m-icon name="o-information-circle" class="mt-0.5 h-5 w-5 shrink-0" />
                <span>
                    Se depositarán
                    <strong>
                        ${{ number_format((floatval($incomeForm->incomeAmount ?: 0) * intval($incomeForm->incomeSavingsAllocation)) / 100, 2) }}
                    </strong>
                    en tus bolsillos de ahorro.
                </span>
            </div>
        </div>

        {{-- NOTES --}}
        <x-m-textarea label="Notas (opcional)" placeholder="Agrega información adicional sobre este ingreso..."
            wire:model="incomeForm.incomeNotes" rows="3" />
    </div>

    <x-slot:actions>
        <x-m-button label="Cancelar" wire:click="$set('incomeForm.modal', false)" />
        <x-m-button label="{{ $incomeForm->editingId !== null ? 'Guardar cambios' : 'Registrar ingreso' }}"
            icon="{{ $incomeForm->editingId !== null ? 'o-check' : 'o-plus-circle' }}" class="btn-primary"
            wire:click="saveIncome" />
    </x-slot:actions>
</x-m-modal>