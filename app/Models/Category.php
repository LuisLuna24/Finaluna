<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    protected $fillable = [
        'icon_id',
        'nombre',
        'expense_type_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function icon()
    {
        return $this->belongsTo(Icon::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }
}
