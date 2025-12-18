<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sale_parts extends Model
{
    protected $guarded = [];

    public function sale()
    {
        return $this->belongsTo(sales::class);
    }

    public function customer()
    {
        return $this->belongsTo(accounts::class);
    }

    public function purchase()
    {
        return $this->belongsTo(purchase_parts::class, 'purchase_part_id');
    }
}
