<div>
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
                        {{ $editId ? 'Editar presupuesto' : 'Crear nuevo presupuesto' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <form action="">
        <x-m-steps wire:model="step" class="border-y border-base-content/10 my-5 py-5" stepper-classes="w-full p-5"
            steps-color="step-success">
            {{-- STEP 1 --}}
            <x-m-step step="1" text="Datos" icon="o-calendar-date-range">

                <div class="space-y-6">

                    <x-m-header title="Datos del Presupuesto" separator />

                    <div class="rounded-2xl border border-base-200 bg-base-100 p-5 shadow-sm">
                        <div class="mb-5 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10">
                                <x-m-icon name="o-document-text" class="h-5 w-5 text-primary" />
                            </div>

                            <div>
                                <h3 class="font-semibold text-base-content">
                                    Información general
                                </h3>

                                <p class="text-sm text-base-content/60">
                                    Define los datos principales del presupuesto.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <x-m-input label="Nombre" wire:model="budget.name" placeholder="Ej. Presupuesto mensual" />

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <x-m-datetime label="Fecha inicio" wire:model="budget.start_date" />

                                <x-m-datetime label="Fecha final" wire:model="budget.end_date" />
                            </div>
                            <x-m-textarea label="Notas" wire:model="budget.notes" />
                        </div>
                    </div>

                </div>

            </x-m-step>

            {{-- STEP 2 --}}
            <x-m-step step="2" text="Ingresos" icon="o-arrow-trending-up">
                <div class="space-y-6">
                    <x-m-header title="Ingresos" separator>
                        <x-slot:actions>
                            <x-m-button label="Agregar ingreso" icon="o-plus" class="btn-primary"
                                wire:click="$set('incomeModal', true)" />
                        </x-slot:actions>
                    </x-m-header>

                    {{-- SUMMARY --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-base-200 bg-base-100 p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-base-content/60">
                                        Ingresos registrados
                                    </p>
                                    <p class="mt-1 text-2xl font-bold">
                                        {{ count($incomes) }}
                                    </p>
                                </div>
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-success/10">
                                    <x-m-icon name="o-arrow-trending-up" class="h-6 w-6 text-success" />
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-primary/20 bg-primary/5 p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-base-content/60">
                                        Total de ingresos
                                    </p>
                                    <p class="mt-1 text-2xl font-bold text-primary">
                                        ${{ number_format(collect($incomes)->sum('amount'), 2) }}
                                    </p>
                                </div>

                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10">
                                    <x-m-icon name="o-banknotes" class="h-6 w-6 text-primary" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- INCOME TABLE --}}
                    <div class="overflow-hidden rounded-2xl border border-base-200 bg-base-100 shadow-sm">
                        <div class="border-b border-base-200 px-5 py-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="font-semibold">
                                        Movimientos registrados
                                    </h3>
                                    <p class="text-sm text-base-content/60">
                                        Historial de ingresos del presupuesto.
                                    </p>
                                </div>
                                <span class="badge badge-primary badge-sm">
                                    {{ count($incomes) }}
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr class="text-xs uppercase tracking-wide text-base-content/60">
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                        <th>Método</th>
                                        <th class="text-left">Monto</th>
                                        <th>Ahorro</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($incomes as $index => $income)
                                        <tr class="hover:bg-base-200/50">

                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-success/10">
                                                        <x-m-icon name="o-arrow-up" class="h-4 w-4 text-success" />
                                                    </div>
                                                    <div>
                                                        <p class="font-medium">
                                                            {{ $income['description'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="text-sm text-base-content/70">
                                                    {{ $income['date'] }}
                                                </span>
                                            </td>

                                            <td>
                                                <x-m-badge value="{{ $income['method'] }}" class="badge-soft" />
                                            </td>

                                            <td class="text-left">
                                                <span class="font-semibold text-success">
                                                    +${{ number_format($income['amount'], 2) }}
                                                </span>
                                            </td>

                                            <td class="text-left">
                                                <span class="font-semibold text-success">
                                                    ${{ number_format(($income['amount'] * $income['savings_allocation']) / 100, 2) }}
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <div class="flex justify-center gap-2">
                                                    <x-m-button wire:click="editIncome({{ $index }})"
                                                        icon="o-pencil" class="btn-circle btn-ghost text-info" />
                                                    <x-m-button wire:click="removeIncome({{ $index }})"
                                                        icon="o-trash" class="btn-circle btn-ghost text-error" />
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-8 text-center">
                                                <div
                                                    class="rounded-2xl border border-dashed border-base-300 bg-base-200/30 px-6 py-12 text-center">

                                                    <div
                                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-base-200">
                                                        <x-m-icon name="o-banknotes"
                                                            class="h-7 w-7 text-base-content/40" />
                                                    </div>

                                                    <h3 class="mt-4 font-semibold">
                                                        Aún no hay ingresos
                                                    </h3>

                                                    <p class="mx-auto mt-1 max-w-md text-sm text-base-content/60">
                                                        Registra tu primer ingreso para comenzar a construir
                                                        el presupuesto.
                                                    </p>
                                                    <div class="mt-5">
                                                        <x-m-button label="Agregar primer ingreso" icon="o-plus"
                                                            class="btn-primary"
                                                            wire:click="$set('incomeModal', true)" />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TOTAL --}}
                    <div class="flex justify-end">
                        <div
                            class="flex w-full items-center justify-between gap-6 rounded-2xl border border-primary/20 bg-primary/5 px-5 py-4 sm:w-auto sm:min-w-[320px]">
                            <div>
                                <p class="text-sm text-base-content/60">
                                    Total acumulado
                                </p>
                                <p class="text-xs text-base-content/50">
                                    Ingresos registrados
                                </p>
                            </div>
                            <p class="text-2xl font-bold text-primary">
                                ${{ number_format(collect($incomes)->sum('amount'), 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-m-step>


            {{-- STEP 3 --}}
            <x-m-step step="3" text="Presupuestos" icon="o-clipboard-document-list">

                <div class="space-y-6">
                    <x-m-header title="Partidas Presupuestales" separator>
                        <x-slot:actions>
                            <x-m-button label="Agregar partida" icon="o-plus" class="btn-primary"
                                wire:click="$set('budgetItemModal', true)" />
                        </x-slot:actions>
                    </x-m-header>

                    {{-- SUMMARY --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-base-200 bg-base-100 p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-base-content/60">
                                        Partidas registradas
                                    </p>
                                    <p class="mt-1 text-2xl font-bold">
                                        {{ count($budgetItems) }}
                                    </p>
                                </div>
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-info/10">
                                    <x-m-icon name="o-clipboard-document-list" class="h-6 w-6 text-info" />
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-primary/20 bg-primary/5 p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-base-content/60">
                                        Total presupuestado
                                    </p>
                                    <p class="mt-1 text-2xl font-bold text-primary">
                                        ${{ number_format(collect($budgetItems)->sum('presupuesto'), 2) }}
                                    </p>
                                </div>
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10">
                                    <x-m-icon name="o-banknotes" class="h-6 w-6 text-primary" />
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- BUDGET ITEMS TABLE --}}

                    <div class="overflow-hidden rounded-2xl border border-base-200 bg-base-100 shadow-sm">
                        <div class="border-b border-base-200 px-5 py-4">
                            <div class="flex items-center justify-between gap-4">

                                <div>
                                    <h3 class="font-semibold">
                                        Distribución del presupuesto
                                    </h3>
                                    <p class="text-sm text-base-content/60">
                                        Detalle de las partidas presupuestales asignadas.
                                    </p>
                                </div>

                                <span class="badge badge-primary badge-sm">
                                    {{ count($budgetItems) }}
                                </span>

                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr class="text-xs uppercase tracking-wide text-base-content/60">
                                        <th>Categoría / Subcategoría</th>
                                        <th>Tipo de Gasto</th>
                                        <th>Descripción</th>
                                        <th class="text-right">Presupuesto</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($budgetItems as $index => $item)
                                        <tr class="hover:bg-base-200/50">
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-info/10">
                                                        <x-m-icon name="o-tag" class="h-4 w-4 text-info" />
                                                    </div>
                                                    <div>
                                                        <p class="font-medium">
                                                            {{ $item['category_name'] }}
                                                        </p>
                                                        @if ($item['subcategory_name'] !== 'N/A')
                                                            <p class="text-xs text-base-content/60">
                                                                {{ $item['subcategory_name'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <x-m-badge :value="$item['expense_type_name']" class="badge-soft" />
                                            </td>

                                            <td>
                                                <span class="badge badge-ghost">
                                                    {{ $item['notas'] }}
                                                </span>
                                            </td>

                                            <td class="text-right">
                                                <span class="font-semibold text-primary">
                                                    ${{ number_format($item['presupuesto'], 2) }}
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <div class="flex justify-center gap-2">
                                                    <x-m-button wire:click="editBudgetItem({{ $index }})"
                                                        icon="o-pencil" class="btn-circle btn-ghost text-info" />
                                                    <x-m-button wire:click="removeBudgetItem({{ $index }})"
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
                                                        <x-m-icon name="o-clipboard-document-list"
                                                            class="h-7 w-7 text-base-content/40" />
                                                    </div>

                                                    <h3 class="mt-4 font-semibold">
                                                        Aún no hay partidas presupuestales
                                                    </h3>

                                                    <p class="mx-auto mt-1 max-w-md text-sm text-base-content/60">
                                                        Agrega tu primera partida para comenzar a distribuir el
                                                        presupuesto.
                                                    </p>

                                                    <div class="mt-5">
                                                        <x-m-button label="Agregar primera partida" icon="o-plus"
                                                            class="btn-primary"
                                                            wire:click="$set('budgetItemModal', true)" />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- TOTAL BUDGET ITEMS --}}
                    <div class="flex justify-end gap-2">
                        <div
                            class="flex w-full items-center justify-between gap-6 rounded-2xl border border-primary/20 bg-primary/5 px-5 py-4 sm:w-auto sm:min-w-[320px]">
                            <div>
                                <p class="text-sm text-base-content/60">
                                    Total presupuestado
                                </p>
                            </div>
                            <p class="text-2xl font-bold text-primary">
                                ${{ number_format(collect($budgetItems)->sum('presupuesto'), 2) }}
                            </p>
                        </div>
                        <div
                            class="flex w-full items-center justify-between gap-6 rounded-2xl border border-primary/20 bg-primary/5 px-5 py-4 sm:w-auto sm:min-w-[320px]">
                            <div>
                                <p class="text-sm text-base-content/60">
                                    Balance Presupuestal
                                </p>
                            </div>
                            <p class="text-2xl font-bold text-primary">
                                ${{ number_format(collect($incomes)->sum('amount') - collect($budgetItems)->sum('presupuesto'), 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-m-step>


            {{-- STEP 4 --}}
            <x-m-step step="4" text="Confirmar" icon="o-check-badge">
                <div class="space-y-6">
                    <x-m-header title="Confirmar presupuesto" separator />
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        {{-- RESUMEN DATOS --}}
                        <div class="rounded-2xl border border-base-200 bg-base-100 p-5 shadow-sm">
                            <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                                <x-m-icon name="o-document-text" class="h-5 w-5 text-primary" />
                                Datos Generales
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-base-content/60">Nombre del presupuesto</p>
                                    <p class="font-medium">{{ $budget['name'] ?: 'No definido' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-base-content/60">Fecha inicio</p>
                                        <p class="font-medium">{{ $budget['start_date'] ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-base-content/60">Fecha final</p>
                                        <p class="font-medium">{{ $budget['end_date'] ?: '-' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-base-content/60">Notas</p>
                                    <p class="font-medium">{{ $budget['notas'] ?: 'No definido' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- RESUMEN FINANCIERO --}}
                        <div class="rounded-2xl border border-base-200 bg-base-100 p-5 shadow-sm">
                            <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                                <x-m-icon name="o-banknotes" class="h-5 w-5 text-primary" />
                                Resumen Financiero
                            </h3>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between border-b border-base-200 pb-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-success/10">
                                            <x-m-icon name="o-arrow-trending-up" class="h-5 w-5 text-success" />
                                        </div>
                                        <div>
                                            <p class="font-medium">Total Ingresos</p>
                                            <p class="text-xs text-base-content/60">{{ count($incomes) }} movimientos
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-lg font-bold text-success">
                                        ${{ number_format(collect($incomes)->sum('amount'), 2) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between border-b border-base-200 pb-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10">
                                            <x-m-icon name="o-clipboard-document-list" class="h-5 w-5 text-primary" />
                                        </div>
                                        <div>
                                            <p class="font-medium">Total Presupuestado</p>
                                            <p class="text-xs text-base-content/60">{{ count($budgetItems) }} partidas
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-lg font-bold text-primary">
                                        ${{ number_format(collect($budgetItems)->sum('presupuesto'), 2) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-success/10">
                                            <x-m-icon name="o-clipboard-document-list" class="h-5 w-5 text-success" />
                                        </div>
                                        <div>
                                            <p class="font-medium">Balance</p>
                                            <p class="text-xs text-base-content/60">
                                                {{ collect($incomes)->sum('amount') - collect($budgetItems)->sum('presupuesto') > 0 ? 'Sobrante' : 'Faltante' }}
                                                de ingresos
                                            </p>
                                        </div>
                                    </div>
                                    <p
                                        class="text-lg font-bold {{ collect($incomes)->sum('amount') - collect($budgetItems)->sum('presupuesto') > 0 ? 'text-success' : 'text-danger' }}">
                                        ${{ number_format(collect($incomes)->sum('amount') - collect($budgetItems)->sum('presupuesto'), 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-success/20 bg-success/5 p-6 mt-6 grid">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-success/10">
                                <x-m-icon name="o-check-badge" class="h-7 w-7 text-success" />
                            </div>
                            <div>
                                <h3 class="font-semibold">
                                    Todo listo para guardar
                                </h3>
                                <p class="text-sm text-base-content/60">
                                    Revisa la información anterior. Al guardar, se creará el presupuesto y se
                                    registrarán todos sus movimientos.
                                </p>
                            </div>
                        </div>
                    </div>
            </x-m-step>

        </x-m-steps>
    </form>


    {{-- NAVIGATION --}}
    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div class="text-sm text-base-content/50">
            Paso {{ $step }} de 4
        </div>
        <div class="flex w-full gap-2 sm:w-auto">
            @if ($step > 1)
                <x-m-button label="Anterior" icon="o-arrow-left" wire:click="prev" spinner />
            @endif


            @if ($step < 4)
                <x-m-button label="Siguiente" icon-right="o-arrow-right" class="btn-primary"
                    wire:click="next"spinner />
            @else
                <x-m-button label="{{ $editId ? 'Actualizar presupuesto' : 'Guardar presupuesto' }}" icon="o-check"
                    class="btn-primary" wire:click="save"spinner />
            @endif
        </div>
    </div>


    {{-- INCOME MODAL --}}
    <x-m-modal wire:model="incomeModal"
        title="{{ $editingIncomeIndex !== null ? 'Editar ingreso' : 'Registrar ingreso' }}">
        <div class="space-y-6">
            {{-- BASIC DATA --}}
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-m-select label="Método de pago" :options="$this->paymentMethods" option-label="nombre" option-value="id"
                        wire:model="incomeMethod" placeholder="Selecciona un método..." />
                    <x-m-input label="Monto" prefix="$" wire:model.live="incomeAmount" type="number"
                        step="0.01" placeholder="0.00" />
                </div>
                <x-m-datetime label="Fecha del ingreso" wire:model="incomeDate" />
                <x-m-input label="Descripción" placeholder="Ej. Pago de cliente, salario, venta..."
                    wire:model="incomeDescription" />
            </div>

            {{-- SAVINGS --}}
            <div class="rounded-2xl border border-base-300 bg-base-200/30 p-5">

                <div class="flex items-center justify-between gap-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10">
                            <x-m-icon name="o-banknotes" class="h-5 w-5 text-primary" />
                        </div>
                        <div>
                            <p class="font-semibold">
                                Ahorro
                            </p>
                            <p class="text-xs text-base-content/50">
                                Porcentaje destinado al ahorro
                            </p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-primary">
                        {{ $incomeSavingsAllocation }}%
                    </span>

                </div>

                <div class="mt-5">
                    <x-m-range wire:model.live="incomeSavingsAllocation" min="0" max="100"
                        class="range-primary" step="1" />
                </div>

                <div class="mt-4 flex items-start gap-3 rounded-xl bg-primary/10 p-4 text-sm text-primary">
                    <x-m-icon name="o-information-circle" class="mt-0.5 h-5 w-5 shrink-0" />
                    <span>
                        Se depositarán
                        <strong>
                            ${{ number_format((floatval($incomeAmount ?: 0) * intval($incomeSavingsAllocation)) / 100, 2) }}
                        </strong>
                        en tus bolsillos de ahorro.
                    </span>
                </div>
            </div>
            {{-- NOTES --}}
            <x-m-textarea label="Notas (opcional)" placeholder="Agrega información adicional sobre este ingreso..."
                wire:model="incomeNotes" rows="3" />

        </div>

        <x-slot:actions>
            <x-m-button label="Cancelar" wire:click="$set('incomeModal', false)" />
            <x-m-button label="{{ $editingIncomeIndex !== null ? 'Guardar cambios' : 'Registrar ingreso' }}"
                icon="{{ $editingIncomeIndex !== null ? 'o-check' : 'o-plus-circle' }}" class="btn-primary"
                wire:click="addIncome" />
        </x-slot:actions>

    </x-m-modal>

    {{-- BUDGET ITEM MODAL --}}
    <x-m-modal wire:model="budgetItemModal"
        title="{{ $editingBudgetItemIndex !== null ? 'Editar Partida Presupuestal' : 'Agregar Partida Presupuestal' }}">
        <div class="space-y-6">
            {{-- FORM FIELDS --}}
            <div class="space-y-4">

                <div class="space-y-2">
                    <x-m-group label="Tipo de Gasto" wire:model.live="budgetExpenseTypeId" :options="$this->expenseTypes"
                        option-label="nombre" option-value="id" class="[&:checked]:!btn-primary max-w-full" />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-m-select label="Categoría" :options="$this->categories" option-label="nombre" option-value="id"
                        wire:model.live="budgetCategoryId" placeholder="Selecciona..." />
                    <x-m-select label="Subcategoría" :options="$this->subcategories" option-label="nombre" option-value="id"
                        wire:model="budgetSubcategoryId" placeholder="Opcional..." />
                </div>

                <x-m-input label="Presupuesto Asignado" prefix="$" wire:model="budgetAmount" type="number"
                    step="0.01" placeholder="0.00" />

                <x-m-textarea label="Notas (opcional)"
                    placeholder="Agrega información adicional sobre esta partida..." wire:model="budgetNotes"
                    rows="3" />

            </div>

        </div>

        <x-slot:actions>
            <x-m-button label="Cancelar" wire:click="$set('budgetItemModal', false)" />
            <x-m-button label="{{ $editingBudgetItemIndex !== null ? 'Guardar cambios' : 'Agregar partida' }}"
                icon="{{ $editingBudgetItemIndex !== null ? 'o-check' : 'o-plus-circle' }}" class="btn-primary"
                wire:click="addBudgetItem" />
        </x-slot:actions>

    </x-m-modal>
</div>
