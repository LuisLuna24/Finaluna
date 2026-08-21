<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    //

    protected $fillable = [
        'user_id',
        'budget_id',
        'payment_method_id',
        'fecha',
        'descripcion',
        'total',
        'porcentaje_ahorro',
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

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMehod::class);
    }
}
