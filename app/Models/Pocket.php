<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pocket extends Model
{
    //

    protected $fillable = [
        'user_id',
        'icon_id',
        'fecha_inicio',
        'fecha_fin',
        'meta_apartado',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function icon()
    {
        return $this->belongsTo(Icon::class);
    }

    public function pocketItems()
    {
        return $this->hasMany(PocketItem::class);
    }
}
