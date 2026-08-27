<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
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
                        Administra tus apartados
                    </p>
                </div>
            </div>
        </div>
        <x-m-button link="{{ route('pockets.create') }}" icon="o-plus" label="Nuevo apartado" class="btn-primary" />
    </div>

    <div class="border-b p-4 shadow-sm ">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-md">
                <x-m-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                    placeholder="Buscar Apartado..." clearable />
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <x-m-icon name="o-squares-2x2" class="h-5 w-5" />
                <span>
                    {{ $pockets->total() }} Apartados
                </span>
            </div>
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4 mt-10">

        @forelse ($pockets as $pocket)
            @php
                $porcentaje = $pocket->meta_apartado > 0 ? ($pocket->apartado / $pocket->meta_apartado) * 100 : 0;

                $excedido = $pocket->apartado > $pocket->meta_apartado;
                $restante = $pocket->meta_apartado - $pocket->apartado;
            @endphp

            <article
                class="group relative overflow-hidden rounded-2xl border bg-base-100 p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg
            {{ $excedido ? 'border-error/30' : 'border-base-200' }}">

                {{-- Indicador superior --}}
                <div class="absolute inset-x-0 top-0 h-1
                {{ $excedido ? 'bg-error' : 'bg-primary' }}">
                </div>

                {{-- Encabezado --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <div
                                class="flex size-9 shrink-0 items-center justify-center rounded-xl
                            {{ $excedido ? 'bg-error/10 text-error' : 'bg-primary/10 text-primary' }}">
                                <x-m-icon name="o-wallet" class="size-5" />
                            </div>

                            <div class="min-w-0">
                                <h3 class="truncate text-base font-bold text-base-content"
                                    title="{{ $pocket->nombre }}">
                                    {{ $pocket->nombre }}
                                </h3>
                                <p class="mt-0.5 text-xs text-base-content/50">
                                    {{ $pocket->fecha_inicio }} - {{ $budget->fecha_fin }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <x-m-dropdown>
                        <x-slot:trigger>
                            <x-m-button icon="o-ellipsis-vertical" class="btn-circle btn-ghost btn-sm" />
                        </x-slot:trigger>

                        <x-m-menu-item icon="o-arrow-trending-up" title="Abonar"
                            wire:click.stop="newPocketItem({{ $pocket->id }})" />

                        <x-m-menu-item icon="o-pencil" title="Editar"
                            link="{{ route('pockets.edit', $pocket->id) }}" />
                        <x-m-menu-item icon="o-trash" title="Eliminar" wire:click="deletePocket({{ $pocket->id }})"
                            wire:confirm="¿Estás seguro de eliminar este apartado?" />

                    </x-m-dropdown>

                </div>

                {{-- Gasto principal --}}
                <div class="mt-7">
                    <div class="flex items-end justify-between gap-3">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">
                                Gastado
                            </p>
                            <p
                                class="mt-1 text-3xl font-bold tracking-tight
                            {{ $excedido ? 'text-error' : 'text-base-content' }}">

                                ${{ number_format($pocket->meta_apartado, 2) }}
                            </p>
                        </div>

                        {{-- Porcentaje --}}
                        <div class="text-right">
                            <p
                                class="text-2xl font-bold
                            {{ $excedido ? 'text-error' : 'text-primary' }}">

                                {{ number_format($porcentaje, 0) }}%
                            </p>
                            <p class="text-xs text-base-content/50">
                                utilizado
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Barra de progreso --}}
                <div class="mt-5">

                    <div class="mb-2 flex items-center justify-between">

                        <span class="text-xs text-base-content/50">
                            Presupuesto
                        </span>

                        <span class="text-xs font-medium text-base-content/70">
                            ${{ number_format($pocket->meta_apartado, 2) }}
                        </span>

                    </div>
                    <x-m-progress value="{{ min($porcentaje, 100) }}" max="100"
                        class="{{ $excedido ? 'progress-error' : 'progress-primary' }}" />

                </div>

                {{-- Métricas secundarias --}}
                <div class="mt-5 grid grid-cols-2 gap-3">

                    <div class="rounded-xl bg-base-200/50 p-3">

                        <p class="text-xs text-base-content/50">
                            Abonos
                        </p>

                        <p class="mt-1 text-sm font-semibold text-success">
                            ${{ number_format($pocket->apartado, 2) }}
                        </p>

                    </div>
                    <div class="rounded-xl bg-base-200/50 p-3 text-right">
                        <p class="text-xs text-base-content/50">
                            {{ $excedido ? 'Excedente' : 'Disponible' }}
                        </p>
                        <p
                            class="mt-1 text-sm font-semibold
                        {{ $excedido ? 'text-error' : 'text-base-content' }}">

                            ${{ number_format(abs($restante), 2) }}

                        </p>
                    </div>
                </div>

                {{-- Estado --}}
                <div class="mt-4 flex items-center gap-2">
                    <span class="size-2 rounded-full
                    {{ $excedido ? 'bg-error' : 'bg-success' }}">
                    </span>

                    @if ($excedido)
                        <span class="text-xs font-medium text-error">
                            Presupuesto excedido
                        </span>
                    @else
                        <span class="text-xs text-base-content/60">
                            Dentro del presupuesto
                        </span>
                    @endif
                </div>
            </article>
        @empty
            <div class="md:col-span-2 lg:col-span-4">
                <div class="rounded-2xl border border-dashed border-base-300 bg-base-200/30 px-6 py-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-base-200">
                        <x-m-icon name="o-banknotes" class="h-7 w-7 text-base-content/40" />
                    </div>
                    <h3 class="mt-4 font-semibold">
                        Aún no hay apartados
                    </h3>

                    <p class="mx-auto mt-1 max-w-md text-sm text-base-content/60">
                        Registra tu primer apartado para comenzar a planificar tus gastos.
                    </p>
                    <div class="mt-5">
                        <x-m-button icon="o-plus" label="Agregar" link="{{ route('pockets.create') }}" />
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
