<?php

namespace App\Http\Controllers;

use App\Models\sales;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\purchase;
use App\Models\purchase_cars;
use App\Models\purchase_parts;
use App\Models\sale_cars;
use App\Models\sale_parts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? now()->toDateString();

        $purchases = purchase::where('sale_id', null)->get();

        $sales = sales::whereBetween('date', [$from, $to])->get();

        return view('sales.index', compact('purchases', 'sales', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $purchase = purchase::find($request->purchase_id);
        $customers = accounts::customer()->get();
        return view('sales.create', compact('purchase', 'customers'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();

            $ref = getRef();
            
            $sale = sales::create([
                'date' => $request->date,
                'purchases_id' => $request->purchase_id,
                'amount' => 0,
                'notes' => $request->notes,
                'refID' => $ref,
            ]);

            $total = 0;
            $cars = $request->car_id;
            if($cars){
                foreach ($cars as $key => $car) {
                    $car = purchase_cars::find($car);
                    $total += $request->car_price[$key];
                    $profit = $request->car_price[$key] - $request->car_net_cost[$key];
                    sale_cars::create(
                        [
                            'sales_id' => $sale->id,
                            'purchase_car_id' => $car->id,
                            'customer_id' => $request->customer_id[$key],
                            'pprice' => $request->car_pprice[$key],
                            'expense' => $request->car_expense[$key],
                            'net_cost' => $request->car_net_cost[$key],
                            'price' => $request->car_price[$key],
                            'profit' => $profit,
                            'date' => $request->date,
                            'refID' => $ref,
                        ]
                    );
                     $notes = "Pending amount of Chassis: $car->chassis_no /n Color: $car->color | Maker: $car->maker | Model: $car->model /n Year: $car->year | Grade: $car->grade";

                createTransaction($request->customer_id[$key], $sale->date, $request->car_price[$key], 0, $notes, $ref);
                }
            }

            $parts = $request->part_id;
            if($parts){
                foreach ($parts as $key => $part) {
                    $part = purchase_parts::find($part);
                    $total += $request->part_price[$key];
                    $profit = $request->part_price[$key] - $request->part_net_cost[$key];
                    sale_parts::create(
                        [
                            'sales_id' => $sale->id,
                            'purchase_part_id' => $part->id,
                            'customer_id' => $request->part_customer_id[$key],
                            'description' => $part->description,
                            'qty' => $part->qty,
                            'pprice' => $request->part_pprice[$key],
                            'expense' => $request->part_expense[$key],
                            'net_cost' => $request->part_net_cost[$key],
                            'price' => $request->part_price[$key],
                            'profit' => $profit,
                            'date' => $request->date,
                            'refID' => $ref,
                        ]
                    );
                     $notes = "Pending amount of $part->description | $part->weight_ltr /n grade: $part->grade | Qty: $part->qty";

                createTransaction($request->part_customer_id[$key], $sale->date, $request->part_price[$key], 0, $notes, $ref);
                }
               
            }

            $purchase = purchase::find($request->purchase_id);
            $purchase->sale_id = $sale->id;
            $purchase->save();

            $sale->update([
                'amount' => $total,
            ]);
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
        $sale = sales::with('cars', 'parts')->find($id);
        return view('sales.view', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sales $sales)
    {
        //
    }
}
