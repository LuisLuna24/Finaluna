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
                        {{ $editId ? 'Editar apartado' : 'Crear nuevo apartado' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-m-card title="Información general">
            <div class="space-y-5">
                <x-m-input label="Nombre" wire:model="pocket.name" placeholder="Nombre del apartado" />
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-m-datetime label="Fecha de inicio" wire:model="pocket.fecha_inicio" />
                    <x-m-datetime label="Fecha de fin" wire:model="pocket.fecha_fin" />
                </div>
                <x-m-input icon="o-banknotes" label="Meta del apartado" wire:model="pocket.monto" placeholder="2400" />
            </div>
        </x-m-card>

        <x-m-card title="Abonos">
            <x-slot:menu>
                <x-m-button label="Agregar abono" icon="o-plus" class="btn-primary btn-sm"
                    wire:click="newPocketItem" />
            </x-slot:menu>

            {{-- SUMMARY --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-base-200 bg-base-100 p-4">
                    <p class="text-sm font-medium text-base-content/60">
                        Abonos registrados
                    </p>
                    <p class="mt-1 text-2xl font-bold">
                        {{ count($pocketItems) }}
                    </p>
                </div>
                <div class="rounded-xl border border-primary/20 bg-primary/5 p-4">
                    <p class="text-sm font-medium text-base-content/60">
                        Total de abonos
                    </p>
                    <p class="mt-1 text-2xl font-bold text-primary">
                        ${{ number_format(collect($pocketItems)->sum('monto'), 2) }}
                    </p>
                </div>
            </div>

            {{-- POCKET ITEMS TABLE --}}
            <div class="mt-4 overflow-hidden rounded-2xl border border-base-200 bg-base-100 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr class="text-xs uppercase tracking-wide text-base-content/60">
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Método</th>
                                <th class="text-right">Monto</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pocketItems as $index => $item)
                                <tr class="hover:bg-base-200/50">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-success/10">
                                                <x-m-icon name="o-banknotes" class="h-4 w-4 text-success" />
                                            </div>
                                            <p class="font-medium">
                                                {{ $item['descripcion'] ?: 'Sin descripción' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm text-base-content/70">
                                            {{ $item['fecha'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <x-m-badge :value="$item['method']" class="badge-soft" />
                                    </td>
                                    <td class="text-right">
                                        <span class="font-semibold text-success">
                                            ${{ number_format($item['monto'], 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <x-m-button wire:click="editPocketItem({{ $index }})" icon="o-pencil"
                                                class="btn-circle btn-ghost text-info" />
                                            <x-m-button wire:click="removePocketItem({{ $index }})"
                                                icon="o-trash" class="btn-circle btn-ghost text-error" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center">
                                        <div
                                            class="rounded-2xl border border-dashed border-base-300 bg-base-200/30 px-6 py-12 text-center">
                                            <div
                                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-base-200">
                                                <x-m-icon name="o-banknotes" class="h-7 w-7 text-base-content/40" />
                                            </div>
                                            <h3 class="mt-4 font-semibold">
                                                Aún no hay abonos
                                            </h3>
                                            <p class="mx-auto mt-1 max-w-md text-sm text-base-content/60">
                                                Agrega tu primer abono para comenzar a guardar hacia tu meta.
                                            </p>
                                            <div class="mt-5">
                                                <x-m-button label="Agregar primer abono" icon="o-plus"
                                                    class="btn-primary" wire:click="newPocketItem" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-m-card>
    </div>

    {{-- NAVIGATION --}}
    <div class="mt-6 flex justify-end">
        <x-m-button label="{{ $editId ? 'Actualizar apartado' : 'Guardar apartado' }}" icon="o-check"
            class="btn-primary" wire:click="savePocket" spinner />
    </div>

    {{-- POCKET ITEM MODAL --}}
    @include('modules.pockets.pocket-items-form')
</div>
