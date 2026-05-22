<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartPurchaseExpenseProfit extends Model
{
    protected $guarded = [];

    public function partsPurchase()
    {
        return $this->belongsTo(parts_purchase::class);
    }

    public function getAmountAttribute($value)
    {
        return abs($value);
    }
}
