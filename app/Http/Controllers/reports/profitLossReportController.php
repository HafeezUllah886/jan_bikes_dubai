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
        $companies = purchase::whereNotNull('company')->distinct()->pluck('company')->toArray();
        $models = purchase::whereNotNull('model')->distinct()->pluck('model')->toArray();
        $engines = purchase::whereNotNull('engine')->distinct()->pluck('engine')->toArray();
        $inv_nos = purchase::whereNotNull('inv_no')->distinct()->pluck('inv_no')->toArray();

        return view('reports.profit_loss.index', compact('chassisNos', 'companies', 'models', 'engines', 'inv_nos'));
    }

    public function reportData(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $chassis_no = $request->chassis_no;
        $company = $request->company;
        $model = $request->model;
        $engine = $request->engine;
        $inv_no = $request->inv_no;

        $purchases = purchase::with(['expenseProfits', 'saleCar'])
            ->where('profitable', 1)
            ->where('status', 'Sold')
            ->when($chassis_no !== null, function ($query) use ($chassis_no) {
                return $query->whereIn('chassis', $chassis_no);
            })
            ->when($company !== null, function ($query) use ($company) {
                return $query->whereIn('company', $company);
            })
            ->when($model !== null, function ($query) use ($model) {
                return $query->whereIn('model', $model);
            })
            ->when($engine !== null, function ($query) use ($engine) {
                return $query->whereIn('engine', $engine);
            })
            ->when($inv_no !== null, function ($query) use ($inv_no) {
                return $query->whereIn('inv_no', $inv_no);
            })
            ->when($chassis_no === null && $company === null && $model === null && $engine === null && $inv_no === null, function ($query) use ($from, $to) {
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
