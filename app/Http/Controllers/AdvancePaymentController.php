<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\advancePayment;
use App\Models\paymentReceiving;
use App\Models\transactions;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvancePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? lastDayOfMonth();
        $status = $request->status ?? "All";
        $advances = advancePayment::whereBetween('date', [$from, $to])->orderBy('id', 'desc');
        if($status != 'All')
        {
            $advances = $advances->where('status', $status);
        }
        $advances = $advances->get();
        $froms = accounts::where('type', '!=', 'Business')->get();
        $accounts = accounts::Business()->get();

        return view('finance.advances.index', compact('advances', 'froms', 'accounts', 'from', 'to', 'status'));
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
        try
        {
            DB::beginTransaction();
            $ref = getRef();
            advancePayment::create(
                [
                    'fromID' => $request->fromID,
                    'toID' => $request->accountID,
                    'userID' => auth()->user()->id,
                    'for' => $request->for,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );

            $from = accounts::find($request->fromID);
            $to = accounts::find($request->accountID);

            createTransaction($request->accountID, $request->date, $request->amount, 0, "Advance Received from: $from->title for: $request->for <br>" . $request->notes, $ref);
            createTransaction($request->fromID, $request->date, 0, $request->amount, "Advance Given for: $request->for <bt>" . $request->notes, $ref);

            DB::commit();
            return back()->with('success', 'Receipt Saved');
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $receiving = advancePayment::find($id);
        return view('finance.advances.receipt', compact('receiving'));
    }

    public function markAsItemDelivered($id)
    {
        try
        {
            $advance = advancePayment::find($id);
            $advance->status = "Item Delivered";
            $advance->save();
            return back()->with('success', 'Advance Marked as Item Delivered');
        }
        catch(\Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }
    }



    public function edit(paymentReceiving $paymentReceiving)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, paymentReceiving $paymentReceiving)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        try
        {
            DB::beginTransaction();
            advancePayment::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('advances.index')->with('success', "Advance Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('advances.index')->with('error', $e->getMessage());
        }
    }
}
