<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartPurchaseExpenseProfit extends Model
{
    protected $guarded = [];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function account()
    {
        return $this->belongsTo(accounts::class);
    }

    public function getAmountAttribute($value)
    {
        return abs($value);
    }
}
