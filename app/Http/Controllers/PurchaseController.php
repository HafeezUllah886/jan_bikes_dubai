<?php

namespace App\Http\Controllers;

use App\Imports\PurchasesImport;
use App\Models\accounts;
use App\Models\auctions;
use App\Models\Booking;
use App\Models\imports;
use App\Models\PartPurchaseExpenseProfit;
use App\Models\parts_purchase;
use App\Models\purchase;
use App\Models\PurchaseExpenseProfit;
use App\Models\sale_cars;
use App\Models\sales;
use App\Models\transactions;
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
        $inv_no = $request->inv_no ?? null;
        $status = $request->status ?? 'all';

        if ($inv_no) {
            $purchases = purchase::where('inv_no', $inv_no)->get();
        } else {

            $purchases = purchase::whereBetween('date', [$start, $end]);
            if ($status != 'all') {
                $purchases = $purchases->where('status', $status);
            }
            $purchases = $purchases->get();
        }
        $invoices = purchase::where('status', 'Available')->select('inv_no')->distinct()->get();

        $imports = purchase::whereNotNull('import_id')->distinct('import_id')->pluck('import_id')->toArray();
        $imports = imports::whereIn('id', $imports)->get();

        $customers = accounts::customer()->get();

        return view('purchase.index', compact('purchases', 'start', 'end', 'inv_no', 'invoices', 'status', 'imports', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = accounts::vendor()->get();

        return view('purchase.create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'chassis' => 'required|unique:purchases,chassis',
                ],
                [
                    'chassis.unique' => 'Chassis No. Already Exist',
                ]
            );
            DB::beginTransaction();
            $ref = getRef();
            $purchase = purchase::create(
                [
                    'inv_no' => $request->invoice_no,
                    'meter_type' => $request->meter_type,
                    'company' => $request->company,
                    'model' => $request->model,
                    'color' => $request->color,
                    'chassis' => $request->chassis,
                    'engine' => $request->engine,
                    'date' => $request->date,
                    'price' => $request->price,
                    'expense' => $request->expenses,
                    'total' => $request->total_cost,
                    'sale_price' => $request->sale_price,
                    'notes' => $request->notes,
                    'type' => $request->type,
                    'purchase_type' => 'Purchase',
                    'refID' => $ref,
                    'profitable' => $request->profit,
                    'vendor_id' => $request->vendor,
                ]
            );

            createTransaction($request->vendor, $request->date, 0, $request->total_cost, 'Pending Amount of Purchase Chassis No. '.$request->chassis, $ref);

            DB::commit();

            return to_route('purchase.show', $purchase->id)->with('success', 'Purchase Created');
        } catch (\Exception $e) {
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
            transactions::where('refID', $purchase->refID)->delete();

            $purchase->update(
                [

                    'account_id' => $request->account,
                    'meter_type' => $request->meter_type,
                    'company' => $request->company,
                    'model' => $request->model,
                    'color' => $request->color,
                    'chassis' => $request->chassis,
                    'engine' => $request->engine,
                    'auction' => $request->auction,
                    'yard' => $request->yard,
                    'date' => $request->date,
                    'price' => $request->price,
                    'ptax' => $request->ptax,
                    'tax' => $request->tax,
                    'rikso' => $request->rikso,
                    'auction_fee' => $request->auction_fee,
                    'total' => $request->total,
                    'rate' => $request->rate,
                    'net_dirham' => $request->net_dirham,
                    'notes' => $request->notes,
                    'type' => $request->type,
                ]
            );
            createTransaction($request->account, $request->date, 0, $request->net_dirham, "Payment of Purchase ID $purchase->id - Chassis No. $request->chassis", $purchase->refID);
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
            transactions::where('refID', $purchase->refID)->delete();
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

    /**
     * Store expense or profit distributed across all items in an import
     */
    public function storeExpenseProfit(Request $request)
    {
        try {
            $request->validate([
                'import_id' => 'required|exists:imports,id',
                'type' => 'required|in:expense,profit',
                'bike_amount' => 'nullable|numeric|min:0',
                'car_amount' => 'nullable|numeric|min:0',
                'parts_amount' => 'nullable|numeric|min:0',
                'date' => 'required|date',
                'description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $import = imports::findOrFail($request->import_id);
            $type = $request->type;
            $date = $request->date;
            $description = $request->description;

            // Handle Bikes
            if ($request->bike_amount) {
                $bikeCount = purchase::where('import_id', $import->id)
                    ->where('type', 'Bike')
                    ->count();

                if ($bikeCount > 0) {
                    $amountPerBike = $request->bike_amount / $bikeCount;
                    $bikes = purchase::where('import_id', $import->id)
                        ->where('type', 'Bike')
                        ->get();

                    foreach ($bikes as $bike) {
                        PurchaseExpenseProfit::create([
                            'purchase_id' => $bike->id,
                            'type' => $type,
                            'amount' => $amountPerBike,
                            'date' => $date,
                            'description' => $description,
                        ]);
                    }
                }
            }

            // Handle Cars
            if ($request->car_amount) {
                $carCount = purchase::where('import_id', $import->id)
                    ->where('type', 'Car')
                    ->count();

                if ($carCount > 0) {
                    $amountPerCar = $request->car_amount / $carCount;
                    $cars = purchase::where('import_id', $import->id)
                        ->where('type', 'Car')
                        ->get();

                    foreach ($cars as $car) {
                        PurchaseExpenseProfit::create([
                            'purchase_id' => $car->id,
                            'type' => $type,
                            'amount' => $amountPerCar,
                            'date' => $date,
                            'description' => $description,
                        ]);
                    }
                }
            }

            // Handle Parts
            if ($request->parts_amount) {
                $partsTotal = parts_purchase::where('import_id', $import->id)
                    ->sum('qty');

                if ($partsTotal > 0) {
                    $amountPerPart = $request->parts_amount / $partsTotal;
                    $parts = parts_purchase::where('import_id', $import->id)
                        ->get();

                    foreach ($parts as $part) {
                        PartPurchaseExpenseProfit::create([
                            'parts_purchase_id' => $part->id,
                            'type' => $type,
                            'amount' => $amountPerPart * $part->qty,
                            'date' => $date,
                            'description' => $description,
                        ]);
                    }
                }
            }

            DB::commit();

            return back()->with('success', 'Expense/Profit distributed successfully');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function expenseProfit(purchase $purchase)
    {
        return view('purchase.partials.expense_profit_modal', compact('purchase'));
    }

    public function updateExpenseProfit(Request $request, PurchaseExpenseProfit $expenseProfit)
    {
        $request->validate([
            'type' => 'required|in:expense,profit',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $expenseProfit->update([
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Expense/Profit entry updated successfully');
    }

    public function deleteExpenseProfit(PurchaseExpenseProfit $expenseProfit)
    {
        $expenseProfit->delete();

        return back()->with('success', 'Expense/Profit entry deleted successfully');
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

    public function markasbooked($id)
    {
        try {
            DB::beginTransaction();
            $purchase = purchase::find($id);
            $purchase->update([
                'status' => 'Booked',
            ]);
            DB::commit();

            return back()->with('success', 'Purchase marked as booked');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function bookPurchase(Request $request, $id)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:accounts,id',
                'price' => 'required|numeric|min:0',
                'advance' => 'nullable|numeric|min:0',
                'date' => 'required|date',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();
            $purchase = purchase::findOrFail($id);

            $ref = getRef();

            $booking = Booking::create([
                'purchase_id' => $purchase->id,
                'customer_id' => $request->customer_id,
                'price' => $request->price,
                'advance' => $request->advance ?? 0,
                'date' => $request->date,
                'notes' => $request->notes,
                'refID' => $ref,
            ]);

            $purchase->update([
                'status' => 'Booked',
            ]);

            createTransaction($request->customer_id, $request->date, $request->price, 0, 'Purchase Booked - Chassis No. '.$purchase->chassis, $ref);

            if ($request->advance > 0) {
                // If there's an advance, credit the customer for the advance payment.
                // Assuming it's recorded against the same reference.
                createTransaction($request->customer_id, $request->date, 0, $request->advance, 'Advance for Purchase Booked - Chassis No. '.$purchase->chassis, $ref);
            }

            DB::commit();

            return back()->with('success', 'Purchase marked as booked successfully');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function markasavailable($id)
    {
        try {
            DB::beginTransaction();
            $purchase = purchase::find($id);
            $purchase->update([
                'status' => 'Available',
            ]);

            $booking = Booking::where('purchase_id', $purchase->id)->first();
            if ($booking) {
                $booking->delete();
            }

            transactions::where('refID', $purchase->refID)->delete();

            DB::commit();

            return back()->with('success', 'Purchase marked as available');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function sellBooked(Request $request, $id)
    {
        try {
            $request->validate([
                'remaining_amount' => 'required|numeric|min:0',
                'vcc' => 'required|numeric|min:0',
                'date' => 'required|date',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();
            $purchase = purchase::findOrFail($id);
            $booking = $purchase->booking;

            if (! $booking) {
                throw new \Exception('No booking found for this purchase.');
            }

            $total_sale_price = $booking->advance + $request->remaining_amount;
            $total_with_vcc = $total_sale_price + $request->vcc;
            $ref = getRef();

            // Create formal Sale
            $sale = sales::create([
                'customer_id' => $booking->customer_id,
                'date' => $request->date,
                'total' => $total_with_vcc,
                'refID' => $ref,
            ]);

            // Create Sale Car details
            sale_cars::create([
                'sale_id' => $sale->id,
                'purchase_id' => $purchase->id,
                'type' => $purchase->type,
                'chassis' => $purchase->chassis,
                'pprice' => $purchase->price,
                'price' => $total_sale_price,
                'vcc' => $request->vcc,
                'total' => $total_with_vcc,
                'date' => $request->date,
            ]);

            $purchase->update([
                'status' => 'Sold',
            ]);

            // Create formal Sale transaction for the full amount
            createTransaction($booking->customer_id, $request->date, 0, $request->remaining_amount, 'Sale from Booking - Chassis No. '.$purchase->chassis.($request->notes ? ' | '.$request->notes : ''), $ref);

            DB::commit();

            return redirect()->route('sale.index')->with('success', 'Booked item successfully sold');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }
}
