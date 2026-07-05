<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public function purchase()
    {
        return $this->belongsTo(purchase::class, 'purchase_id');
    }

    public function customer()
    {
        return $this->belongsTo(accounts::class, 'customer_id');
    }
}
