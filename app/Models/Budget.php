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
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function pocketItems()
    {
        return $this->belongsToMany(PocketItem::class, 'budget_pocket_items');
    }
}
