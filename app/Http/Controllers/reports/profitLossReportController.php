<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\expenses;
use App\Models\extra_profit;
use App\Models\purchase;
use Illuminate\Http\Request;

class profitLossReportController extends Controller
{
    public function index()
    {
        return view('reports.profit_loss.index');
    }

    public function reportData(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $from = $request->from;
        $to = $request->to;

        $purchases = purchase::with(['expenseProfits', 'saleCar'])
            ->where('profitable', 1)
            ->whereBetween('date', [$from, $to])
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $expenses = expenses::whereBetween('date', [$from, $to])->sum('amount');
        $extra_profits = extra_profit::whereBetween('date', [$from, $to])->sum('amount');

        return view('reports.profit_loss.details', compact('purchases', 'from', 'to', 'expenses', 'extra_profits'));
    }
}
