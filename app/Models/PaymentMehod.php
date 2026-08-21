<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMehod extends Model
{
    //

    protected $fillable = [
        'nombre',
        'is_active'
    ];

    protected $table = 'payment_methods';

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
        return $this->hasMany(PocketItem::class);
    }
}
