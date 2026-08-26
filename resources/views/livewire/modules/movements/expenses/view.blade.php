<x-m-modal wire:model="modalView" title="Ver gastos de la partida presupuestal">
    <div class="space-y-6">
    
    <x-m-table :headers="$headers" :rows="$expenses" />
       
    <x-slot:actions>
        <x-m-button label="Cerrar" wire:click="$set('modalView', false)" />
    </x-slot:actions>

</x-m-modal>
