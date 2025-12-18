<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class purchase_cars extends Model
{
    protected $guarded = [];

    public function purchase()
    {
        return $this->belongsTo(purchase::class);
    }
}
