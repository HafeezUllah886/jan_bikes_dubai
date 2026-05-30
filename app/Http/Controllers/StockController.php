<?php

namespace App\Http\Controllers;

use App\Models\parts_purchase;
use App\Models\purchase;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Cars Stock – shows purchases (bikes/cars) with cost, sale price, profit/loss.
     * Default filter: Available
     */
    public function carsStock(Request $request)
    {
        $status = $request->status ?? 'Available';

        $query = purchase::with(['saleCar', 'expenseProfits'])->where('type', 'Car');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $items = $query->orderByDesc('id')->get();

        // Summary totals
        $totalCost = $items->sum(fn ($p) => $p->costWithExpenseProfit());
        $totalSalePrice = $items->sum(fn ($p) => $p->saleCar ? $p->saleCar->total : $p->sale_price);
        $totalProfit = $totalSalePrice - $totalCost;

        return view('stock.cars', compact('items', 'status', 'totalCost', 'totalSalePrice', 'totalProfit'));
    }

    /**
     * Parts Stock – shows parts_purchases with cost, sale price, profit/loss.
     * Default filter: Available
     */
    public function partsStock(Request $request)
    {
        $status = $request->status ?? 'Available';

        $query = parts_purchase::with(['expenseProfits']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $items = $query->orderByDesc('id')->get();

        // For each part: total cost = total + expenses - profits (from expense_profits)
        $items->each(function ($part) {
            $expense = $part->expenseProfits->where('type', 'expense')->sum('amount');
            $profit = $part->expenseProfits->where('type', 'profit')->sum('amount');
            $part->net_cost = $part->total + $expense - $profit;
            $part->profit_loss = $part->sale_price - $part->net_cost;
        });

        $totalCost = $items->sum('net_cost');
        $totalSalePrice = $items->sum('sale_price');
        $totalProfit = $totalSalePrice - $totalCost;

        return view('stock.parts', compact('items', 'status', 'totalCost', 'totalSalePrice', 'totalProfit'));
    }
}
