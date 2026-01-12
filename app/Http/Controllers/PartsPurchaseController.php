<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\purchase_payments;
use App\Models\stock;
use App\Models\transactions;
use App\Models\units;
use App\Models\warehouses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\PurchasesImport;
use App\Models\auctions;
use App\Models\parts_purchase;
use App\Models\yards;
use Maatwebsite\Excel\Facades\Excel;


class PartsPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->start ?? firstDayOfMonth();
        $end = $request->end ?? lastDayOfMonth();
        $inv_no = $request->inv_no ?? null;
        
         if($inv_no){
            $purchases = parts_purchase::where('inv_no', $inv_no)->get();
         }else{
            $purchases = parts_purchase::whereBetween('date', [$start, $end])->get();
         }
        $invoices = parts_purchase::where('status', 'Available')->select('inv_no')->distinct()->get();

        return view('parts_purchase.index', compact('purchases', 'start', 'end', 'inv_no', 'invoices'));
    }

    public function available()
    {
    
        $purchases = purchase::where('status', 'Available')->orderby('id', 'desc');
       
        $purchases = $purchases->get();

        return view('parts_purchase.available', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $yards = yards::all();
        $auctions = auctions::all();

        $lastpurchase = purchase::orderby('id', 'desc')->first();

        $rate = purchase::latest()->first()->rate ?? 0;
        $accounts = accounts::bank()->get();
        return view('purchase.create', compact('auctions', 'yards', 'lastpurchase', 'rate', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try
        {
            $request->validate(
                [
                    'chassis'   =>  'required|unique:purchases,chassis',
                ],
                [
                    'chassis.unique' => 'Chassis No. Already Exist',
                ]
            );
            DB::beginTransaction();
            $ref = getRef();
            $purchase = purchase::create(
                [
                    "account_id"            =>  $request->account,
                    "meter_type"            =>  $request->meter_type,
                    "company"               =>  $request->company,
                    "model"                 =>  $request->model,
                    "color"                 =>  $request->color,
                    "chassis"               =>  $request->chassis,
                    "engine"                =>  $request->engine,
                    "auction"               =>  $request->auction,
                    "yard"                  =>  $request->yard,
                    "date"                  =>  $request->date,
                    "price"                 =>  $request->price,
                    "ptax"                  =>  $request->ptax,
                    "tax"                   =>  $request->tax,
                    "rikso"                 =>  $request->rikso,
                    "auction_fee"           =>  $request->auction_fee,
                    "total"                 =>  $request->total,
                    "rate"                  =>  $request->rate,
                    "net_dirham"            =>  $request->net_dirham,
                    "notes"                 =>  $request->notes,
                    "type"                  =>  $request->type,
                    "refID"                 =>  $ref,
                ]
            );

            createTransaction($request->account, $request->date, 0, $request->net_dirham, "Payment of Purchase ID $purchase->id - Chassis No. $request->chassis", $ref);

            DB::commit();
            return to_route('purchase.show', $purchase->id)->with('success', "Purchase Created");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(purchase $purchase)
    {
        return view('purchase.view', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $purchase = purchase::find($id);
        $yards = yards::all();
        $auctions = auctions::all();

        $transporters = accounts::where('type', 'Transporter')->get();
        $accounts = accounts::bank()->get();

        return view('purchase.edit', compact('purchase', 'yards', 'auctions', 'transporters', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try
        {
            $request->validate(
                [
                    'chassis'   =>  'required|unique:purchases,chassis,'.$id,
                ],
                [
                    'chassis.unique' => 'Chassis No. Already Exist',
                ]
            );
            DB::beginTransaction();
            $purchase = purchase::find($id);
            transactions::where('refID', $purchase->refID)->delete();
        
            $purchase->update(
                [
                  
                    "account_id"            =>  $request->account,
                    "meter_type"            =>  $request->meter_type,
                    "company"               =>  $request->company,
                    "model"                 =>  $request->model,
                    "color"                 =>  $request->color,
                    "chassis"               =>  $request->chassis,
                    "engine"                =>  $request->engine,
                    "auction"               =>  $request->auction,
                    "yard"                  =>  $request->yard,
                    "date"                  =>  $request->date,
                    "price"                 =>  $request->price,
                    "ptax"                  =>  $request->ptax,
                    "tax"                   =>  $request->tax,
                    "rikso"                 =>  $request->rikso,
                    "auction_fee"           =>  $request->auction_fee,
                    "total"                 =>  $request->total,
                    "rate"                  =>  $request->rate,
                    "net_dirham"            =>  $request->net_dirham,
                    "notes"                 =>  $request->notes,
                    "type"                  =>  $request->type,
                ]
            );
                 createTransaction($request->account, $request->date, 0, $request->net_dirham, "Payment of Purchase ID $purchase->id - Chassis No. $request->chassis", $purchase->refID);
            DB::commit();
            return to_route('purchase.show', $purchase->id)->with('success', "Purchase Updated");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try
        {
            DB::beginTransaction();
            $purchase = purchase::find($id);
            transactions::where('refID', $purchase->refID)->delete();
            $purchase->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('purchase.index')->with('success', "Purchase Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('purchase.index')->with('error', $e->getMessage());
        }
    }


    public function import(Request $request)
    {
        try
        {
            $file = $request->file('excel');
        $extension = $file->getClientOriginalExtension();
        if($extension == "xlsx")
        {
            Excel::import(new PurchasesImport, $file);
            return back()->with("success", "Successfully imported");
        }
        else
        {
            return back()->with("error", "Invalid file extension");
        }
        }
        catch(\Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }
    }
}
