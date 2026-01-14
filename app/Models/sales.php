<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sales extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(accounts::class, 'customer_id');
    }

    public function sale_cars()
    {
        return $this->hasMany(sale_cars::class, 'sale_id');
    }

    public function sale_parts()
    {
        return $this->hasMany(sale_parts::class, 'sale_id');
    }
    
}
