<div>
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10">
                    <x-m-icon name="o-banknotes" class="h-6 w-6 text-primary" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Presupuestos
                    </h1>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        {{ $editId ? 'Editar presupuesto' : 'Crear nuevo presupuesto' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <x-m-form class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <x-m-card title="Información general">
            <x-m-input label="Nombre" wire:model="pocket.name" placeholder="Nombre del apartado" />
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 my-2">
                <x-m-datetime label="Fecha de inicio" wire:model="pocket.fecha_inicio" />
                <x-m-datetime label="Fecha de fin" wire:model="pocket.fecha_fin" />
            </div>
            <x-m-input icon="o-banknotes" label="Meta del apartado" wire:model="pocket.monto" placeholder="2400"
                money />
        </x-m-card>

        <x-m-card title="Abonos">

        </x-m-card>
    </x-m-form>
</div>
