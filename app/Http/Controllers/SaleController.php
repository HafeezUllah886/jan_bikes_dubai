<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\export;
use App\Models\export_cars;
use App\Models\export_parts;
use App\Models\parts;
use App\Models\parts_purchase;
use App\Models\parts_purchase_details;
use App\Models\purchase;
use App\Models\sale_cars;
use App\Models\sale_parts;
use App\Models\sales;
use App\Models\stock;
use App\Models\tax_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->start ?? firstDayOfMonth();
        $end = $request->end ?? lastDayOfMonth();
      
        $sales = sales::whereBetween('date', [$start, $end])->get();
        
        return view('sales.index', compact('sales', 'start', 'end'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = purchase::where('status', 'Available')->get();
        update_parts_available_qty();
        $parts = parts_purchase::where('status', 'Available')->get();
        foreach ($parts as $part) {
            $sales = sale_parts::where('purchase_id', $part->id)->get();
            $avail_qty = $part->qty - $sales->sum('qty');
            $part->avail_qty = $avail_qty;
        }
        
        $customers = accounts::customer()->get();
        return view('sales.create', compact('products', 'parts', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $ref = getRef();

            $sale = sales::create(
                [
                    'customer_id' => $request->customer,
                    'date' => $request->date,
                    'total' => $request->net,
                    'refID' => $ref,
                ]
            );

            if ($request->car_id) {
                $car_ids = $request->car_id;
                foreach ($car_ids as $key => $car_id) {
                    $purchase = purchase::find($car_id);
                    $purchase->update([
                        'status' => 'Sold',
                    ]);
                    $profit = $request->car_price[$key] - $purchase->price;
                    sale_cars::create(
                        [
                            'sale_id' => $sale->id,
                            'purchase_id' => $car_id,
                            'type' => $purchase->type,
                            'chassis' => $purchase->chassis,
                            'pprice' => $purchase->price,
                            'price' => $request->car_price[$key],
                            'profit' => $profit,
                            'date' => $request->date,
                            'profit_type' => $request->car_profit[$key],
                        ]
                    );
                }
            }
            if ($request->part_id) {
                $parts = $request->part_id;

                foreach ($parts as $key => $part) {
                    $part_purchase = parts_purchase::find($part);
                  $profit = $request->part_price[$key] - $part_purchase->price;
                    sale_parts::create(
                        [
                            'sale_id' => $sale->id,
                            'purchase_id' => $part,
                            'description' => $part_purchase->description,
                            'qty' => $request->qty[$key],
                            'pprice' => $part_purchase->price,
                            'price' => $request->part_price[$key],
                            'amount' => $request->part_amount[$key],
                            'profit' => $profit,
                            'date' => $request->date,
                            'profit_type' => $request->part_profit[$key],
                        ]
                    );

                }
            }
            createTransaction($request->customer, $request->date, $sale->total, 0, "Pending Amount of Sale # $sale->id", $ref);
            update_parts_available_qty();
            DB::commit();

            return redirect()->route('sale.index')->with('success', 'Sale created successfully');
       } catch (\Exception $e) {
            DB::rollBack();
 
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sale = sales::find($id);

        return view('sales.view', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $export = export::find($id);
        $products = purchase::where('status', 'Available')->get();
        $exported = export_parts::select('purchase_id', DB::raw('SUM(qty) as total_exported_qty'))
            ->groupBy('purchase_id');

        $parts = parts_purchase_details::select(
            'parts_purchase_details.*',
            'parts.name as part_name',
            DB::raw('(parts_purchase_details.qty - COALESCE(exported.total_exported_qty, 0)) as avail_qty')
        )
            ->leftJoin('parts', 'parts_purchase_details.part_id', '=', 'parts.id')
            ->leftJoinSub($exported, 'exported', function ($join) {
                $join->on('parts_purchase_details.id', '=', 'exported.purchase_id');
            })
            ->whereRaw('COALESCE(exported.total_exported_qty, 0) < parts_purchase_details.qty')
            ->orderBy('parts_purchase_details.id', 'desc')
            ->limit(200)
            ->get();
        $consignees = accounts::consignee()->get();

        return view('export.edit', compact('products', 'parts', 'consignees', 'export'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $export = export::find($id);
            $ref = $export->refID;

            foreach ($export->export_cars as $car) {
                $purchase = purchase::find($car->purchase_id);
                $purchase->update([
                    'status' => 'Available',
                ]);
            }

            $export->export_parts()->delete();
            $export->export_cars()->delete();
            tax_transaction::where('refID', $ref)->delete();
            stock::where('refID', $ref)->delete();

            $export->update(
                [
                    'inv_no' => $request->inv_no,
                    'consignee_id' => $request->consignee,
                    'info_party_id' => $request->info_party,
                    'date' => $request->date,
                    'c_no' => $request->c_no,
                    'purchase_amount' => $request->total,
                    /* 'transport_charges' => $request->expense, */
                    'other_expenses' => $request->otherexpense,
                     'cars_expense' => $request->car_expense,
                    'parts_expense' => $request->parts_expense,
                    'rate' => $request->rate,
                    'total_exp' => $request->total_expense,
                    'total' => $request->net,
                ]
            );

            $consignee = accounts::find($request->consignee);

            if ($request->car_id) {
                $car_ids = $request->car_id;
                foreach ($car_ids as $key => $car_id) {
                    $purchase = purchase::find($car_id);
                    $purchase->update([
                        'status' => 'Exported',
                    ]);
                    export_cars::create(
                        [
                            'export_id' => $export->id,
                            'purchase_id' => $car_id,
                            'type' => $purchase->type,
                            'chassis' => $purchase->chassis,
                            'yen' => $purchase->total,
                            'dirham' => $purchase->net_dirham,
                        ]
                    );

                    tax_transaction::create(
                        [
                            'consignee' => $consignee->title,
                            'item' => $purchase->chassis,
                            'bl_no' => $request->inv_no,
                            'qty' => 1,
                            'tax_rate' => $purchase->tax,
                            'tax_amount' => $purchase->ptax,
                            'type' => $purchase->type,
                            'date' => $request->date,
                            'refID' => $ref,
                        ]
                    );
                }
            }
            if ($request->part_id) {
                $parts = $request->part_id;

                foreach ($parts as $key => $part) {
                    $part_purchase = parts_purchase_details::find($part);
                    $part_details = parts::find($part_purchase->part_id);
                    
                    export_parts::create(
                        [
                            'export_id' => $export->id,
                            'purchase_id' => $part,
                            'part_id' => $part_details->id,
                            'part_name' => $part_details->name,
                            'qty' => $request->qty[$key],
                            'yen' => $request->part_yen[$key],
                            'dirham' => $request->part_dirham[$key],
                        ]
                    );

                    $tax = $request->part_price[$key] * $request->part_tax[$key] / 100;
                    $totalTax = $tax * $request->qty[$key];

                    tax_transaction::create(
                        [
                            'consignee' => $consignee->title,
                            'item' => $part_details->name,
                            'bl_no' => $request->inv_no,
                            'qty' => $request->qty[$key],
                            'tax_rate' => $request->part_tax[$key],
                            'tax_amount' => $totalTax,
                            'type' => 'Parts',
                            'date' => $request->date,
                            'refID' => $ref,
                        ]
                    );
                    createStock($part_purchase->part_id, 0, $request->qty[$key], $request->date, 'Exported in BL# '.$request->inv_no, $ref);
                }
            }
            DB::commit();

            return redirect()->route('export.index')->with('success', 'Export updated successfully');
       } catch (\Exception $e) {
            DB::rollBack();
 
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $export = export::find($id);
            foreach ($export->export_cars as $car) {
                $purchase = purchase::find($car->purchase_id);
                $purchase->update([
                    'status' => 'Available',
                ]);
            }
            $export->export_parts()->delete();
            $export->export_cars()->delete();
            tax_transaction::where('refID', $export->refID)->delete();
            stock::where('refID', $export->refID)->delete();
            $export->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('export.index')->with('error', 'Export deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');
 
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function getPart($id)
    {
        $part = parts_purchase::find($id);
        $part->qty = $part->qty - sale_parts::where('purchase_id', $part->id)->sum('qty');

        return response()->json($part);
    }
}
