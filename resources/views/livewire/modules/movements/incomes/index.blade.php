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
                        Ingresos
                    </h1>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        Administra tus ingresos
                    </p>
                </div>
            </div>
        </div>
        <x-m-button wire:click="newIncome()" icon="o-plus" label="Nuevo ingreso" class="btn-primary" />
    </div>
    {{-- Toolbar --}}
    <div class="border-b p-4 shadow-sm ">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-md">
                <x-m-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                    placeholder="Buscar Presupuesto..." clearable />
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <x-m-icon name="o-squares-2x2" class="h-5 w-5" />
                <span>
                    {{ $incomes->total() }} Ingresos
                </span>
            </div>
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4 mt-10">

        @forelse ($incomes as $income)
            <article
                class="group relative overflow-hidden rounded-2xl border border-base-200 bg-base-100 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-primary/20 hover:shadow-lg">
                {{-- Indicador superior --}}
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary via-primary/80 to-primary/40">
                </div>

                <div class="p-5">

                    {{-- HEADER --}}
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-3">
                            {{-- Icono --}}
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10">
                                <x-m-icon name="o-arrow-trending-up" class="h-5 w-5 text-success" />
                            </div>

                            {{-- Información --}}
                            <div class="min-w-0">

                                <h3 class="truncate text-base font-bold text-base-content"
                                    title="{{ $income->descripcion }}">
                                    {{ $income->descripcion }}
                                </h3>
                                <div class="mt-1 flex items-center gap-2 text-xs text-base-content/50">
                                    <x-m-icon name="o-calendar-days" class="h-3.5 w-3.5" />
                                    <span>{{ $income->fecha }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- ACCIONES --}}
                        <x-m-dropdown>

                            <x-slot:trigger>
                                <x-m-button icon="o-ellipsis-vertical" class="btn-circle btn-ghost btn-sm" />
                            </x-slot:trigger>

                            <x-m-menu-item icon="o-pencil" title="Editar"
                                wire:click="editIncome({{ $income->id }})" />
                            <x-m-menu-item icon="o-trash" title="Eliminar"
                                wire:click="removeIncome({{ $income->id }})" />

                        </x-m-dropdown>

                    </div>

                    {{-- MONTO PRINCIPAL --}}
                    <div class="mt-6 rounded-xl bg-base-200/50 p-4">
                        <div class="flex items-end justify-between gap-4">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">
                                    Ingreso
                                </p>
                                <p class="mt-1 text-3xl font-bold tracking-tight text-success">
                                    +${{ number_format($income->total, 2) }}
                                </p>
                            </div>

                            {{-- AHORRO --}}
                            <div class="text-right">
                                <p class="text-xs text-base-content/50">Ahorro</p>
                                <p class="mt-1 text-sm font-bold text-primary">
                                    {{ $income->porcentaje_ahorro }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- INFORMACIÓN --}}
                    <div class="mt-5 grid grid-cols-2 gap-3">

                        {{-- MÉTODO --}}
                        <div class="rounded-xl border border-base-200 p-3">
                            <div class="flex items-center gap-2 text-xs text-base-content/50">
                                <x-m-icon name="o-credit-card" class="h-4 w-4" />
                                <span>Método de pago</span>
                            </div>
                            <p class="mt-2 truncate text-sm font-semibold">
                                {{ $income->paymentMethod->nombre }}
                            </p>
                        </div>

                        {{-- AHORRO --}}
                        <div class="rounded-xl border border-base-200 p-3">
                            <div class="flex items-center gap-2 text-xs text-base-content/50">
                                <x-m-icon name="o-banknotes" class="h-4 w-4" />
                                <span>Ahorro</span>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-primary">
                                ${{ number_format(($income->total * $income->porcentaje_ahorro) / 100, 2) }}
                            </p>
                        </div>
                    </div>

                    {{-- BARRA DE AHORRO --}}
                    <div class="mt-5">
                        <div class="mb-2 flex items-center justify-between text-xs">
                            <span class="text-base-content/50">
                                Porcentaje destinado al ahorro
                            </span>
                            <span class="font-semibold text-primary">
                                {{ $income->porcentaje_ahorro }}%
                            </span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-base-200">
                            <div class="h-full rounded-full bg-primary transition-all duration-500"
                                style="width: {{ min($income->porcentaje_ahorro, 100) }}%"></div>
                        </div>
                    </div>

                    {{-- NOTAS --}}
                    @if ($income->notes)
                        <div class="mt-5 border-t border-base-200 pt-4">

                            <div class="flex items-start gap-2">
                                <x-m-icon name="o-chat-bubble-left-ellipsis"
                                    class="mt-0.5 h-4 w-4 shrink-0 text-base-content/40" />
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-base-content/50">
                                        Nota
                                    </p>
                                    <p class="mt-1 text-sm leading-relaxed text-base-content/70">
                                        {{ $income->notes }}
                                    </p>
                                </div>
                            </div>
                        </div>
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
                        Aún no hay ingresos
                    </h3>

                    <p class="mx-auto mt-1 max-w-md text-sm text-base-content/60">
                        Registra tu primer ingreso para comenzar a gestionar tus finanzas.
                    </p>
                    <div class="mt-5">
                        <x-m-button wire:click="newIncome()" icon="o-plus" label="Nuevo ingreso" class="btn-primary" />
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @include('modules.movements.incomes.form')
</div>
