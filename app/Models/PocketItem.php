<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PocketItem extends Model
{
    //

    protected $fillable = [
        'pocket_id',
        'payment_method_id',
        'descripcion',
        'fecha',
        'monto',
    ];

    public function pocket()
    {
        return $this->belongsTo(Pocket::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function budgets()
    {
        return $this->belongsToMany(Budget::class, 'budget_pocket_items');
    }
}
