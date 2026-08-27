<x-m-modal wire:model="pocketItemForm.modal"
    title="{{ $pocketItemForm->editingId !== null ? 'Editar abono' : 'Agregar abono' }}">
    <div class="space-y-6">
        {{-- BASIC DATA --}}
        <div class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-m-select label="Método de pago" :options="$pocketItemForm->getPaymentMethods()" option-label="nombre" option-value="id"
                    wire:model="pocketItemForm.pocketItemMethod" placeholder="Selecciona un método..." />
                <x-m-input label="Monto" prefix="$" wire:model.live="pocketItemForm.pocketItemAmount"
                    type="number" step="0.01" placeholder="0.00" />
            </div>
            <x-m-datetime label="Fecha del abono" wire:model="pocketItemForm.pocketItemDate" />
            <x-m-input label="Descripción" placeholder="Ej. Abono del mes, aporte inicial..."
                wire:model="pocketItemForm.pocketItemDescription" />
        </div>
    </div>

    <x-slot:actions>
        <x-m-button label="Cancelar" wire:click="$set('pocketItemForm.modal', false)" />
        <x-m-button label="{{ $pocketItemForm->editingId !== null ? 'Guardar cambios' : 'Agregar abono' }}"
            icon="{{ $pocketItemForm->editingId !== null ? 'o-check' : 'o-plus-circle' }}" class="btn-primary"
            wire:click="savePocketItem" spinner="savePocketItem" />
    </x-slot:actions>
</x-m-modal>
