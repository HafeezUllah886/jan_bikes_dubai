<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sale_parts extends Model
{
    protected $guarded = [];

    public function sale()
    {
        return $this->belongsTo(sale::class);
    }


    public function purchase()
    {
        return $this->belongsTo(parts_purchase::class, 'purchase_id');
    }
}
