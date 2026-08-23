<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //

    protected $fillable = [
        'user_id',
        'budget_item_id',
        'payment_method_id',
        'fecha',
        'descripcion',
        'total',
        'notes',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function budgetItem()
    {
        return $this->belongsTo(BudgetItem::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
