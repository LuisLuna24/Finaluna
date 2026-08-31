<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetPocketItem extends Model
{
    //

    protected $fillable = [
        'budget_id',
        'pocket_item_id',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function pocketItem()
    {
        return $this->belongsTo(PocketItem::class);
    }
}
