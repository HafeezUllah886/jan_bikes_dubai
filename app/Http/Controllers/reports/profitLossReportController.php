<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\expenses;
use App\Models\extra_profit;
use App\Models\purchase;
use App\Models\sale_parts;
use Illuminate\Http\Request;

class profitLossReportController extends Controller
{
    public function index()
    {
        $chassisNos = purchase::orderBy('date', 'desc')->pluck('chassis')->toArray();

        return view('reports.profit_loss.index', compact('chassisNos'));
    }

    public function reportData(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $chassis_no = $request->chassis_no;

        $purchases = purchase::with(['expenseProfits', 'saleCar'])
            ->where('profitable', 1)
            ->where('status', 'Sold')
            ->when($chassis_no !== null, function ($query) use ($chassis_no) {
                return $query->whereIn('chassis', $chassis_no);
            })
            ->when($chassis_no === null, function ($query) use ($from, $to) {
                return $query->whereBetween('date', [$from, $to]);
            })
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $parts_sales = sale_parts::with('purchase')
            ->whereBetween('date', [$from, $to])
            ->whereHas('purchase', function ($query) {
                $query->where('profitable', 1);
            })
            ->get();

        $expenses = expenses::whereBetween('date', [$from, $to])->sum('amount');
        $extra_profits = extra_profit::whereBetween('date', [$from, $to])->sum('amount');

        return view('reports.profit_loss.details', compact('purchases', 'from', 'to', 'expenses', 'extra_profits', 'parts_sales'));
    }
}
