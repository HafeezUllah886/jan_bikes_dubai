<?php

namespace App\Http\Controllers;

use App\Imports\PurchasesImport;
use App\Models\accounts;
use App\Models\auctions;
use App\Models\purchase;
use App\Models\purchase_cars;
use App\Models\purchase_parts;
use App\Models\yards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->start ?? firstDayOfMonth();
        $end = $request->end ?? lastDayOfMonth();

        $purchases = purchase::whereBetween('date', [$start, $end])->orderby('id', 'desc')->get();

        return view('purchase.index', compact('purchases', 'start', 'end'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('purchase.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $ref = getRef();

            $purchase = purchase::create(
                [
                    'date' => $request->date,
                    'c_no' => $request->cno,
                    'bl_no' => $request->bl_no,
                    'bl_amount' => $request->bl_amount,
                    'bl_amount_pkr' => $request->bl_amount_pkr,
                    'conversion_rate' => $request->ex_rate,
                    'refID' => $ref,
                ]
            );

            $total = 0;

            $cars = $request->car_id;

            if ($cars) {

                foreach ($cars as $key => $car) {
                    $total += $request->price_pkr[$key];
                    purchase_cars::create(
                        [
                            'purchase_id' => $purchase->id,
                            'model' => $request->model[$key],
                            'maker' => $request->maker[$key],
                            'chassis_no' => $request->chassis[$key],
                            'auction' => $request->auction[$key],
                            'year' => $request->year[$key],
                            'color' => $request->color[$key],
                            'grade' => $request->grade[$key],
                            'price' => $request->price[$key],
                            'price_pkr' => $request->price_pkr[$key],
                            'remarks' => $request->remarks[$key],
                            'refID' => $ref,
                        ]
                    );
                }

            }

            $parts = $request->part_id;

            if ($parts) {

                foreach ($parts as $key => $part) {
                    $total += $request->part_price_pkr[$key];
                    purchase_parts::create(
                        [
                            'purchase_id' => $purchase->id,
                            'description' => $request->part_desc[$key],
                            'weight_ltr' => $request->part_weight[$key],
                            'grade' => $request->part_grade[$key],
                            'qty' => $request->part_qty[$key],
                            'price' => $request->part_price[$key],
                            'price_pkr' => $request->part_price_pkr[$key],
                            'refID' => $ref,
                        ]
                    );
                }
            }

            $net_amount = $total + $request->bl_amount_pkr;

            $purchase->update(
                [
                    'container_amount' => $total,
                    'net_amount' => $net_amount,
                ]
            );

            DB::commit();

            return to_route('purchase.index')->with('success', 'Purchase Created');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchase = purchase::find($id);

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

        return view('purchase.edit', compact('purchase', 'yards', 'auctions', 'transporters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate(
                [
                    'chassis' => 'required|unique:purchases,chassis,'.$id,
                ],
                [
                    'chassis.unique' => 'Chassis No. Already Exist',
                ]
            );
            DB::beginTransaction();

            $purchase = purchase::find($id);
            $purchase->update(
                [
                    'transporter_id' => $request->transporter,
                    'year' => $request->year,
                    'maker' => $request->maker,
                    'model' => $request->model,
                    'chassis' => $request->chassis,
                    'loot' => $request->loot,
                    'yard' => $request->yard,
                    'date' => $request->date,
                    'auction' => $request->auction,
                    'price' => $request->price,
                    'ptax' => $request->ptax,
                    'afee' => $request->afee,
                    'atax' => $request->atax,
                    'transport_charges' => $request->transport_charges,
                    'total' => $request->total,
                    'recycle' => $request->recycle,
                    'adate' => $request->adate,
                    'ddate' => $request->ddate,
                    'number_plate' => $request->number_plate,
                    'nvalidity' => $request->nvalidity,
                    'notes' => $request->notes,
                ]
            );

            DB::commit();

            return to_route('purchase.show', $purchase->id)->with('success', 'Purchase Updated');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try {
            DB::beginTransaction();
            $purchase = purchase::find($id);
            $purchase->delete();
            DB::commit();
            session()->forget('confirmed_password');

            return redirect()->route('purchase.index')->with('success', 'Purchase Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');

            return redirect()->route('purchase.index')->with('error', $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        try {
            $file = $request->file('excel');
            $extension = $file->getClientOriginalExtension();
            if ($extension == 'xlsx') {
                Excel::import(new PurchasesImport, $file);

                return back()->with('success', 'Successfully imported');
            } else {
                return back()->with('error', 'Invalid file extension');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
