<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    //

    protected $fillable = [
        'name',
        'icon',
    ];

    public $timestamps = false;

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function pockets()
    {
        return $this->hasMany(Pocket::class);
    }
}
