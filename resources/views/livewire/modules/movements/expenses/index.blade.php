<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10">
                <x-m-icon name="o-banknotes" class="h-6 w-6 text-primary" />
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-base-content">
                    Gastos
                </h1>
                <p class="mt-0.5 text-sm text-base-content/50">
                    Controla cuánto puedes gastar y cuánto has utilizado.
                </p>
            </div>
        </div>
        <x-m-button wire:click="newExpense()" icon="o-plus" label="Nuevo gasto" class="btn-error" />
    </div>


    {{-- TOOLBAR --}}
    <div class="rounded-2xl border border-base-200 bg-base-100 p-4 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-md">
                <x-m-input wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
                    placeholder="Buscar presupuesto..." clearable />
            </div>
            <div class="flex items-center gap-2 text-sm text-base-content/50">
                <x-m-icon name="o-squares-2x2" class="h-5 w-5" />

                <span>
                    {{ $budgetsItems->total() }}
                    {{ Str::plural('presupuesto', $budgetsItems->total()) }}
                </span>
            </div>
        </div>
    </div>

    {{-- CARDS --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        @forelse ($budgetsItems as $item)
            @php
                $presupuesto = (float) $item->presupuesto;
                $gastoReal = (float) $item->gasto_real;
                $porcentaje = $presupuesto > 0 ? ($gastoReal / $presupuesto) * 100 : 0;
                $porcentajeVisual = min($porcentaje, 100);
                $disponible = max($presupuesto - $gastoReal, 0);
                $estado = match (true) {
                    $porcentaje >= 100 => [
                        'label' => 'Presupuesto excedido',
                        'class' => 'text-error',
                        'bg' => 'bg-error/10',
                        'bar' => 'bg-error',
                        'icon' => 'o-exclamation-triangle',
                    ],

                    $porcentaje >= 80 => [
                        'label' => 'Cerca del límite',
                        'class' => 'text-warning',
                        'bg' => 'bg-warning/10',
                        'bar' => 'bg-warning',
                        'icon' => 'o-exclamation-circle',
                    ],

                    default => [
                        'label' => 'Dentro del presupuesto',
                        'class' => 'text-success',
                        'bg' => 'bg-success/10',
                        'bar' => 'bg-success',
                        'icon' => 'o-check-circle',
                    ],
                };
            @endphp
            <article
                class="group relative overflow-hidden rounded-2xl border border-base-200 bg-base-100 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-primary/20 hover:shadow-lg">
                {{-- INDICADOR --}}
                <div class="absolute inset-x-0 top-0 h-1 {{ $estado['bar'] }}"></div>
                <div class="p-5">
                    {{-- HEADER --}}
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-3">
                            {{-- CATEGORY ICON --}}
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $estado['bg'] }}">

                                <x-m-icon name="o-{{ $item->category->icon->icon }}"
                                    class="h-5 w-5 {{ $estado['class'] }}" />
                            </div>
                            {{-- CATEGORY --}}
                            <div class="min-w-0">
                                <h3 class="truncate text-base font-bold text-base-content" title="{{ $item->notas }}">
                                    {{ $item->notas }}
                                </h3>
                                <div class="mt-1 flex items-center gap-2 text-xs text-base-content/50">
                                    <span>
                                        {{ $item->category->nombre }}
                                    </span>
                                    <span>•</span>
                                    <span>
                                        {{ $item->expenseType->nombre }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <x-m-dropdown>
                            <x-slot:trigger>
                                <x-m-button icon="o-ellipsis-vertical" class="btn-circle btn-ghost btn-sm" />
                            </x-slot:trigger>

                            <x-m-menu-item icon="o-plus" title="Ver gastos"
                                wire:click="viewExpense({{ $item->id }})" />

                            <x-m-menu-item icon="o-pencil" title="Editar partida"
                                link="{{ route('movements.budgets.edit', $item->budget_id) }}" />

                            <x-m-menu-item icon="o-trash" title="Eliminar partida"
                                wire:click="removeExpense({{ $item->id }})"
                                wire:confirm="¿Estás seguro de eliminar esta partida?" />

                        </x-m-dropdown>

                    </div>

                    {{-- MAIN AMOUNT --}}
                    <div class="mt-6">
                        <div class="flex items-end justify-between gap-4">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">
                                    Gastado
                                </p>
                                <p class="mt-1 text-3xl font-bold tracking-tight {{ $estado['class'] }}">
                                    ${{ number_format($gastoReal, 2) }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">
                                    Presupuesto
                                </p>
                                <p class="mt-1 text-lg font-bold text-base-content">
                                    ${{ number_format($presupuesto, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- PROGRESS --}}
                    <div class="mt-5">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <x-m-icon name="{{ $estado['icon'] }}" class="h-4 w-4 {{ $estado['class'] }}" />
                                <span class="text-xs font-medium {{ $estado['class'] }}">
                                    {{ $estado['label'] }}
                                </span>
                            </div>
                            <span class="text-sm font-bold {{ $estado['class'] }}">
                                {{ number_format($porcentaje, 0) }}%
                            </span>
                        </div>

                        <div class="h-2.5 overflow-hidden rounded-full bg-base-200">
                            <div class="h-full rounded-full {{ $estado['bar'] }} transition-all duration-500"
                                style="width: {{ $porcentajeVisual }}%"></div>
                        </div>
                    </div>
                    {{-- AVAILABLE --}}
                    <div class="mt-5 rounded-xl {{ $estado['bg'] }} p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs text-base-content/50">
                                    Disponible
                                </p>
                                <p class="mt-1 text-lg font-bold {{ $estado['class'] }}">
                                    ${{ number_format($disponible, 2) }}
                                </p>
                            </div>
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-base-100/70">
                                <x-m-icon name="o-wallet" class="h-5 w-5 {{ $estado['class'] }}" />
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="mt-5 flex items-center justify-between border-t border-base-200 pt-4">
                        <div class="flex items-center gap-2 text-xs text-base-content/50">
                            <x-m-icon name="o-credit-card" class="h-4 w-4" />
                            <span>
                                {{ $item->expenseType->nombre }}
                            </span>
                        </div>
                        <x-m-button icon="o-plus" label="Agregar gasto" class="btn-ghost btn-sm"
                            wire:click="newExpense({{ $item->id }})" />
                    </div>
                </div>
            </article>
        @empty

            <div class="md:col-span-2 xl:col-span-4">
                <div class="rounded-2xl border border-dashed border-base-300 bg-base-200/30 px-6 py-14 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-base-200">
                        <x-m-icon name="o-wallet" class="h-8 w-8 text-base-content/40" />
                    </div>
                    <h3 class="mt-5 text-lg font-semibold">
                        No tienes presupuestos
                    </h3>
                    <p class="mx-auto mt-1 max-w-md text-sm text-base-content/60">
                        Crea tu primer presupuesto para comenzar a controlar
                        tus gastos y administrar mejor tu dinero.
                    </p>
                    <div class="mt-6">
                        <x-m-button wire:click="newExpense()" icon="o-plus" label="Agregar gasto" class="btn-error" />

                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @livewire('modules.movements.expenses.form', ['budgetId' => $id ?? null])
</div>
