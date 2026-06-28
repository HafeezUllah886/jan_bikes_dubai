<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\expenses;
use App\Models\extra_profit;
use App\Models\ProfitDistribution;
use App\Models\ProfitDistributionDetail;
use App\Models\purchase;
use App\Models\sale_parts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();
            $ref = getRef();

            $profit_distribution = ProfitDistribution::create([
                'date' => date('Y-m-d'),
                'vehicle_profit' => $request->vehicle_profit,
                'parts_profit' => $request->parts_profit,
                'extra_profit' => $request->extra_profit,
                'expenses' => $request->expenses,
                'net_profit' => $request->net_profit,
                'refID' => $ref,
            ]);

            foreach ($request->investor_id as $key => $investor_id) {
                if ($request->amount[$key] > 0) {
                    ProfitDistributionDetail::create([
                        'accountID' => $investor_id,
                        'percentage' => $request->percentage[$key],
                        'amount' => $request->amount[$key],
                        'refID' => $profit_distribution->id,
                    ]);

                    createTransaction($investor_id, date('Y-m-d'), $request->amount[$key], 0, 'Profit Distribution', $ref);
                }
            }

            $purchase_ids = json_decode($request->purchase_ids);
            if (! empty($purchase_ids)) {
                purchase::whereIn('id', $purchase_ids)->update(['is_profit_distributed' => 1]);
            }

            $part_sale_ids = json_decode($request->part_sale_ids);
            if (! empty($part_sale_ids)) {
                sale_parts::whereIn('id', $part_sale_ids)->update(['is_profit_distributed' => 1]);
            }

            $expense_ids = json_decode($request->expense_ids);
            if (! empty($expense_ids)) {
                expenses::whereIn('id', $expense_ids)->update(['is_profit_distributed' => 1]);
            }

            $extra_profit_ids = json_decode($request->extra_profit_ids);
            if (! empty($extra_profit_ids)) {
                extra_profit::whereIn('id', $extra_profit_ids)->update(['is_profit_distributed' => 1]);
            }

            DB::commit();

            return redirect()->route('profit_distribution.index')->with('success', 'Profit distributed successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $profit_distribution = ProfitDistribution::with('details.account')->findOrFail($id);

        $purchases = purchase::with(['expenseProfits', 'saleCar'])
            ->where('profit_distribution_id', $id)
            ->get();

        $parts_sales = sale_parts::with('purchase')
            ->where('profit_distribution_id', $id)
            ->get();

        $expenses = expenses::where('profit_distribution_id', $id)->get();
        $extra_profits = extra_profit::where('profit_distribution_id', $id)->get();

        return view('finance.profit_distribution.show', compact('profit_distribution', 'purchases', 'parts_sales', 'expenses', 'extra_profits'));
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
