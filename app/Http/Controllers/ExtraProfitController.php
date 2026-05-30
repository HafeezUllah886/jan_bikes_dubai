<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\extra_profit;
use App\Models\profitCategories;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtraProfitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? lastDayOfMonth();
        $profits = extra_profit::whereBetween('date', [$from, $to])->orderby('id', 'desc')->get();
        $accounts = accounts::business()->get();
        $categories = profitCategories::all();

        return view('finance.extra_profit.index', compact('profits', 'accounts', 'categories', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $ref = getRef();
            extra_profit::create(
                [
                    'accountID' => $request->accountID,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'cat' => $request->catID,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            createTransaction($request->accountID, $request->date, $request->amount, 0, 'Extra Profit - '.$request->notes, $ref);

            DB::commit();

            return back()->with('success', 'Extra Profit Saved');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(extra_profit $extra_profit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(extra_profit $extra_profit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, extra_profit $extra_profit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        try {
            DB::beginTransaction();
            extra_profit::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('extra_profit.index')->with('success', 'Extra Profit Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('extra_profit.index')->with('error', $e->getMessage());
        }
    }
}
