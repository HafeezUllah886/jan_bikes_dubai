<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sales extends Model
{
    protected $guarded = [];

    public function cars()
    {
        return $this->hasMany(sale_cars::class);
    }

    public function parts()
    {
        return $this->hasMany(sale_parts::class);
    }

    public function purchase()
    {
        return $this->belongsTo(purchase::class, 'purchases_id', 'id');
    }
}
