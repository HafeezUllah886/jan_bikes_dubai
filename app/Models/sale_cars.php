<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sale_cars extends Model
{
    protected $guarded = [];

    public function sale()
    {
        return $this->belongsTo(sale::class);
    }

    public function purchase()
    {
        return $this->belongsTo(purchase::class);
    }
}
