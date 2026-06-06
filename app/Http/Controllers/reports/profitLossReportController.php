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
        $invoices = purchase::orderBy('date', 'desc')->pluck('inv_no')->toArray();

        return view('reports.profit_loss.index', compact('invoices'));
    }

    public function reportData(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $from = $request->from;
        $to = $request->to;
        $inv = $request->invoice_id ?? 'all';

        $purchases = purchase::with(['expenseProfits', 'saleCar'])
            ->where('profitable', 1)
            ->where('status', 'Sold')
            ->when($inv !== 'all', function ($query) use ($inv) {
                return $query->where('inv_no', $inv);
            })
            ->when($inv === 'all', function ($query) use ($from, $to) {
                return $query->whereBetween('date', [$from, $to]);
            })
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $expenses = expenses::whereBetween('date', [$from, $to])->sum('amount');
        $extra_profits = extra_profit::whereBetween('date', [$from, $to])->sum('amount');

        if ($inv !== 'all') {
            $from = $purchases->first()->date ?? firstDayOfCurrentYear();
            $to = $purchases->last()->date ?? lastDayOfCurrentYear();
            $expenses = expenses::whereBetween('date', [$from, $to])->sum('amount');
            $extra_profits = extra_profit::whereBetween('date', [$from, $to])->sum('amount');
        }

        return view('reports.profit_loss.details', compact('purchases', 'from', 'to', 'expenses', 'extra_profits'));
    }
}
