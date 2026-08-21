<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //

    protected $fillable = [
        'user_id',
        'budget_id',
        'subcategory_id',
        'expense_type_id',
        'payment_method_id',
        'fecha',
        'descripcion',
        'total',
        'notes',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMehod::class);
    }
}
