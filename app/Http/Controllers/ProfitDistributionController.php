<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\expenses;
use App\Models\extra_profit;
use App\Models\ProfitDistribution;
use App\Models\purchase;
use App\Models\sale_parts;
use Illuminate\Http\Request;

class ProfitDistributionController extends Controller
{
    public function index()
    {
        $profit_distributions = ProfitDistribution::all();

        return view('finance.profit_distribution.index', compact('profit_distributions'));
    }

    public function create(Request $request)
    {
        $from = $request->fromDate;
        $to = $request->toDate;

        $purchases = purchase::with(['expenseProfits', 'saleCar'])
            ->where('profitable', 1)
            ->where('status', 'Sold')
            ->where('is_profit_distributed', 0)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $parts_sales = sale_parts::with('purchase')
            ->whereBetween('date', [$from, $to])
            ->whereHas('purchase', function ($query) {
                $query->where('profitable', 1);
            })
            ->where('is_profit_distributed', 0)
            ->get();

        $expenses = expenses::whereBetween('date', [$from, $to])->where('is_profit_distributed', 0)->get();
        $extra_profits = extra_profit::whereBetween('date', [$from, $to])->where('is_profit_distributed', 0)->get();

        $investors = accounts::investor()->get();

        return view('finance.profit_distribution.create', compact('purchases', 'parts_sales', 'expenses', 'extra_profits', 'from', 'to', 'investors'));
    }

    public function store(Request $request)
    {
        $profit_distribution = ProfitDistribution::create($request->all());

        return redirect()->route('profit_distribution.index');
    }

    public function edit($id)
    {
        $profit_distribution = ProfitDistribution::find($id);

        return view('finance.profit_distribution.edit', compact('profit_distribution'));
    }

    public function update(Request $request, $id)
    {
        $profit_distribution = ProfitDistribution::find($id);
        $profit_distribution->update($request->all());

        return redirect()->route('profit_distribution.index');
    }

    public function destroy($id)
    {
        $profit_distribution = ProfitDistribution::find($id);
        $profit_distribution->delete();

        return redirect()->route('profit_distribution.index');
    }
}
