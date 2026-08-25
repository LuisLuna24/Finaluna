<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    //

    protected $fillable = [
        'user_id',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'presupuesto',
        'gasto_real',
        'balance',
        'notes',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Gastos
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // Ingresos
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function pocketItems()
    {
        return $this->belongsToMany(PocketItem::class, 'budget_pocket_items');
    }

    public function budgetItems()
    {
        return $this->hasMany(BudgetItem::class);
    }
}
