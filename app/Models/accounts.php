<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class accounts extends Model
{
    protected $guarded = [];

    public function scopeCustomer($query)
    {
        return $query->where('type', 'Customer');
    }

    public function scopeBusiness($query)
    {
        return $query->where('type', 'Business');
    }

   
}
