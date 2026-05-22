<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function saleCar()
    {
        return $this->hasOne(sale_cars::class);
    }

    public function expenseProfits()
    {
        return $this->hasMany(PurchaseExpenseProfit::class);
    }

    public function costWithExpenseProfit()
    {
        $expense = $this->expenseProfits()->where('type', 'expense')->sum('amount');
        $profit = $this->expenseProfits()->where('type', 'profit')->sum('amount');

        return $this->total + $expense - $profit;
    }
}
