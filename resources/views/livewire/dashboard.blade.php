<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10">
                    <x-m-icon name="o-chart-pie" class="h-6 w-6 text-primary" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ __('Dashboard') }}
                    </h1>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        Resumen financiero de tus ingresos, gastos y ahorros
                    </p>
                </div>
            </div>
        </div>
        <x-m-button icon="o-banknotes" label="Nuevo presupuesto" link="{{ route('movements.budgets.create') }}"
            class="btn-primary" />
    </div>

    {{-- Metricas principales --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-m-card shadow>
            <div class="flex items-center gap-4">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-success/10">
                    <x-m-icon name="o-arrow-trending-up" class="size-6 text-success" />
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">Total Ingresos</p>
                    <p class="mt-1 truncate text-2xl font-bold text-success">${{ number_format($totalIncomes, 2) }}</p>
                </div>
            </div>
        </x-m-card>

        <x-m-card shadow>
            <div class="flex items-center gap-4">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-error/10">
                    <x-m-icon name="o-arrow-trending-down" class="size-6 text-error" />
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">Total Gastos</p>
                    <p class="mt-1 truncate text-2xl font-bold text-error">${{ number_format($totalExpenses, 2) }}</p>
                </div>
            </div>
        </x-m-card>

        <x-m-card shadow>
            <div class="flex items-center gap-4">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-info/10">
                    <x-m-icon name="o-banknotes" class="size-6 text-info" />
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">Total Ahorrado</p>
                    <p class="mt-1 truncate text-2xl font-bold text-info">${{ number_format($totalSavings, 2) }}</p>
                </div>
            </div>
        </x-m-card>

        <x-m-card shadow>
            <div class="flex items-center gap-4">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-primary/10">
                    <x-m-icon name="o-wallet" class="size-6 text-primary" />
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">Balance</p>
                    <p class="mt-1 truncate text-2xl font-bold {{ $balance >= 0 ? 'text-primary' : 'text-error' }}">
                        ${{ number_format($balance, 2) }}
                    </p>
                </div>
            </div>
        </x-m-card>
    </div>

    {{-- Presupuestos y apartados --}}
    <div class="grid gap-6 lg:grid-cols-2">

        {{-- Presupuestos activos --}}
        <x-m-card title="Presupuestos activos" subtitle="{{ $activeBudgets->count() }} en curso"
            separator>
            <x-slot:menu>
                <x-m-badge value="{{ $activeBudgets->count() }}" class="badge-primary" />
            </x-slot:menu>

            @forelse ($activeBudgets as $budget)
                @php
                    $excedido = $budget->gasto > $budget->presupuesto;
                @endphp

                <div class="group mb-5 last:mb-0">
                    <a href="{{ route('movements.budgets.edit', $budget->id) }}"
                        class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex size-9 shrink-0 items-center justify-center rounded-xl
                                {{ $excedido ? 'bg-error/10 text-error' : 'bg-primary/10 text-primary' }}">
                                <x-m-icon name="o-wallet" class="size-5" />
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold">{{ $budget->nombre }}</p>
                                <p class="text-xs text-base-content/50">
                                    {{ $budget->fecha_inicio_formateada }}
                                    - {{ $budget->fecha_fin_formateada }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold {{ $excedido ? 'text-error' : 'text-base-content' }}">
                                ${{ number_format($budget->gasto, 2) }}
                            </p>
                            <p class="text-xs text-base-content/50">de ${{ number_format($budget->presupuesto, 2) }}</p>
                        </div>
                    </a>

                    <div class="mt-3">
                        <x-m-progress value="{{ min($budget->porcentaje, 100) }}" max="100"
                            class="{{ $excedido ? 'progress-error' : 'progress-primary' }}" />
                        <div class="mt-1.5 flex items-center justify-between">
                            <span class="text-xs {{ $excedido ? 'text-error' : 'text-base-content/50' }}">
                                {{ $excedido ? 'Presupuesto excedido' : 'Dentro del presupuesto' }}
                            </span>
                            <span class="text-xs font-medium {{ $excedido ? 'text-error' : 'text-primary' }}">
                                {{ number_format($budget->porcentaje, 0) }}%
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-base-300 bg-base-200/30 px-6 py-10 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-base-200">
                        <x-m-icon name="o-banknotes" class="h-6 w-6 text-base-content/40" />
                    </div>
                    <h3 class="mt-3 font-semibold">Aún no hay presupuestos activos</h3>
                    <p class="mx-auto mt-1 max-w-sm text-sm text-base-content/60">
                        Crea un presupuesto para comenzar a planificar tus gastos.
                    </p>
                    <div class="mt-4">
                        <x-m-button icon="o-plus" label="Crear presupuesto"
                            link="{{ route('movements.budgets.create') }}" />
                    </div>
                </div>
            @endforelse
        </x-m-card>

        {{-- Apartados activos --}}
        <x-m-card title="Apartados activos" subtitle="${{ number_format($totalPocketSaved, 2) }} de ${{ number_format($totalPocketGoal, 2) }}"
            separator>

            @forelse ($activePockets as $pocket)
                @php
                    $alcanzado = $pocket->totalApartado >= $pocket->meta_apartado;
                @endphp

                <div class="mb-5 last:mb-0">
                    <a href="{{ route('pockets.edit', $pocket->id) }}"
                        class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex size-9 shrink-0 items-center justify-center rounded-xl
                                {{ $alcanzado ? 'bg-success/10 text-success' : 'bg-info/10 text-info' }}">
                                <x-m-icon name="{{ $pocket->icon?->icon ? 'o-' . $pocket->icon->icon : 'o-wallet' }}" class="size-5" />
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold">{{ $pocket->nombre }}</p>
                                <p class="text-xs text-base-content/50">
                                    Meta: ${{ number_format($pocket->meta_apartado, 2) }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold {{ $alcanzado ? 'text-success' : 'text-base-content' }}">
                                ${{ number_format($pocket->totalApartado, 2) }}
                            </p>
                            <p class="text-xs text-base-content/50">apartado</p>
                        </div>
                    </a>

                    <div class="mt-3">
                        <x-m-progress value="{{ min($pocket->porcentaje, 100) }}" max="100"
                            class="{{ $alcanzado ? 'progress-success' : 'progress-info' }}" />
                        <div class="mt-1.5 flex items-center justify-between">
                            <span class="text-xs {{ $alcanzado ? 'text-success' : 'text-base-content/50' }}">
                                {{ $alcanzado ? 'Meta alcanzada' : 'En progreso' }}
                            </span>
                            <span class="text-xs font-medium {{ $alcanzado ? 'text-success' : 'text-info' }}">
                                {{ number_format($pocket->porcentaje, 0) }}%
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-base-300 bg-base-200/30 px-6 py-10 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-base-200">
                        <x-m-icon name="o-wallet" class="h-6 w-6 text-base-content/40" />
                    </div>
                    <h3 class="mt-3 font-semibold">Aún no hay apartados activos</h3>
                    <p class="mx-auto mt-1 max-w-sm text-sm text-base-content/60">
                        Crea un apartado para ahorrar hacia una meta específica.
                    </p>
                    <div class="mt-4">
                        <x-m-button icon="o-plus" label="Crear apartado" link="{{ route('pockets.create') }}" />
                    </div>
                </div>
            @endforelse
        </x-m-card>
    </div>

    {{-- Movimientos recientes --}}
    <div class="grid gap-6 lg:grid-cols-2">

        {{-- Ultimos ingresos --}}
        <x-m-card title="Últimos ingresos" separator>
            <x-slot:menu>
                <x-m-button icon="o-plus" class="btn-circle btn-ghost btn-sm"
                    link="{{ route('movements.budgets') }}" />
            </x-slot:menu>

            @forelse ($recentIncomes as $income)
                <div class="flex items-center justify-between gap-3 py-2.5">
                    <div class="flex items-center gap-3">
                        <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-success/10">
                            <x-m-icon name="o-arrow-trending-up" class="size-4 text-success" />
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ $income->descripcion }}</p>
                            <p class="text-xs text-base-content/50">
                                {{ $income->fecha_formateada }}
                                @if ($income->budget?->nombre)
                                    · {{ $income->budget->nombre }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-success">+${{ number_format($income->total, 2) }}</p>
                        @if ($income->total_ahorro > 0)
                            <p class="text-xs text-info">Ahorro: ${{ number_format($income->total_ahorro, 2) }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="py-6 text-center text-sm text-base-content/50">Sin ingresos registrados aún.</p>
            @endforelse
        </x-m-card>

        {{-- Ultimos gastos --}}
        <x-m-card title="Últimos gastos" separator>
            <x-slot:menu>
                <x-m-button icon="o-plus" class="btn-circle btn-ghost btn-sm"
                    link="{{ route('movements.budgets') }}" />
            </x-slot:menu>

            @forelse ($recentExpenses as $expense)
                <div class="flex items-center justify-between gap-3 py-2.5">
                    <div class="flex items-center gap-3">
                        <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-error/10">
                            <x-m-icon name="o-arrow-trending-down" class="size-4 text-error" />
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ $expense->descripcion }}</p>
                            <p class="text-xs text-base-content/50">
                                {{ $expense->fecha_formateada }}
                                @if ($expense->budgetItem?->category?->nombre)
                                    · {{ $expense->budgetItem->category->nombre }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-error">-${{ number_format($expense->total, 2) }}</p>
                </div>
            @empty
                <p class="py-6 text-center text-sm text-base-content/50">Sin gastos registrados aún.</p>
            @endforelse
        </x-m-card>
    </div>

</div>