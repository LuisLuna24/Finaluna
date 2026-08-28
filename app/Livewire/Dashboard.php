<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Pocket;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $userId = Auth::id();

        $totalIncomes = Income::where('user_id', $userId)->sum('total');
        $totalSavings = Income::where('user_id', $userId)->sum('total_ahorro');

        $totalExpenses = Expense::where('user_id', $userId)->sum('total');

        $balance = $totalIncomes - $totalExpenses - $totalSavings;

        $activeBudgets = Budget::where('user_id', $userId)
            ->where('is_active', true)
            ->with(['budgetItems.expenses', 'incomes'])
            ->get()
            ->map(function ($budget) {
                $budget->gasto = $budget->budgetItems->sum(fn ($item) => $item->expenses->sum('total'));
                $budget->ingreso = $budget->incomes->sum('total');
                $budget->total_ahorro = $budget->incomes->sum('total_ahorro');
                $budget->porcentaje = $budget->presupuesto > 0
                    ? ($budget->gasto / $budget->presupuesto) * 100
                    : 0;
                $budget->fecha_inicio_formateada = Carbon::parse($budget->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY');
                $budget->fecha_fin_formateada = Carbon::parse($budget->fecha_fin)->locale('es')->isoFormat('D MMM YYYY');

                return $budget;
            });

        $activePockets = Pocket::where('user_id', $userId)
            ->where('is_active', true)
            ->withSum('pocketItems as totalApartado', 'monto')
            ->with('icon')
            ->get()
            ->map(function ($pocket) {
                $pocket->porcentaje = $pocket->meta_apartado > 0
                    ? ($pocket->totalApartado / $pocket->meta_apartado) * 100
                    : 0;

                return $pocket;
            });

        $recentExpenses = Expense::where('user_id', $userId)
            ->with(['budgetItem.category', 'paymentMethod'])
            ->latest('fecha')
            ->limit(5)
            ->get()
            ->each(function ($expense) {
                $expense->fecha_formateada = Carbon::parse($expense->fecha)->locale('es')->isoFormat('D MMM YYYY');
            });

        $recentIncomes = Income::where('user_id', $userId)
            ->with(['budget', 'paymentMethod'])
            ->latest('fecha')
            ->limit(5)
            ->get()
            ->each(function ($income) {
                $income->fecha_formateada = Carbon::parse($income->fecha)->locale('es')->isoFormat('D MMM YYYY');
            });

        $totalPocketGoal = $activePockets->sum('meta_apartado');
        $totalPocketSaved = $activePockets->sum('totalApartado');

        return view('livewire.dashboard', compact(
            'totalIncomes',
            'totalExpenses',
            'totalSavings',
            'balance',
            'activeBudgets',
            'activePockets',
            'recentExpenses',
            'recentIncomes',
            'totalPocketGoal',
            'totalPocketSaved',
        ));
    }
}
