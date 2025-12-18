<?php

namespace App\Http\Controllers;

use App\Models\AccountAdjustment;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? lastDayOfMonth();
        $accountAdjustments = AccountAdjustment::whereBetween('date', [$from, $to])->get();

        $accounts = accounts::all();
        return view('finance.accounts_adjustments.index', compact('accountAdjustments', 'from', 'to', 'accounts'));
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

            $accountAdjustment = AccountAdjustment::create([
                'account_id' => $request->account_id,
                'amount' => $request->amount,
                'type' => $request->type,
                'date' => $request->date,
                'notes' => $request->notes,
                'refID' => $ref,
            ]);

            if ($request->type == 'Credit') {
               createTransaction($request->account_id, $request->date, $request->amount, 0, $request->notes, $ref);
            } else {
               createTransaction($request->account_id, $request->date, 0, $request->amount, $request->notes, $ref);
            }
            
            DB::commit();
            return redirect()->back()->with('success', 'Account Adjustment Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountAdjustment $accountAdjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountAdjustment $accountAdjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountAdjustment $accountAdjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($refID)
    {
        try {
            DB::beginTransaction();
            $accountAdjustment = AccountAdjustment::where('refID', $refID)->first();
            $accountAdjustment->delete();
            transactions::where('refID', $refID)->delete();
            session()->forget('confirmed_password');
            DB::commit();
            return to_route('accounts_adjustments.index')->with('success', 'Account Adjustment Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');
            return to_route('accounts_adjustments.index')->with('error', $e->getMessage());
        }
    }
}
