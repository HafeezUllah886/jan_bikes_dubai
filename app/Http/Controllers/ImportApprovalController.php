<?php

namespace App\Http\Controllers;

use App\Models\import_cars;
use App\Models\import_parts;
use App\Models\imports;
use App\Models\parts_purchase;
use App\Models\purchase;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ImportApprovalController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start ?? Carbon::now()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->toDateString();
        $bl_no = $request->bl_no ?? null;
         if($bl_no){
            $imports = imports::where('inv_no', $bl_no)->get();
         }else{
            $imports = imports::whereBetween('date', [$start, $end])->get();
         }
        $bl_nos = imports::select('inv_no')->distinct()->get();
        return view('import.index', compact('imports', 'start', 'end', 'bl_nos', 'bl_no'));
    }

    public function view(Request $request)
    {
        $import = imports::findOrFail($request->id);
        return view('import.view', compact('import'));
    }

    public function approve(Request $request, $id)
    {
        $import = imports::findOrFail($id);
        $car_expense_dubai = $request->car_expense;
        $bike_expense_dubai = $request->bike_expense;
        $part_expense_dubai = $request->parts_expense;
        
        return view('import.approve', compact('import', 'car_expense_dubai', 'bike_expense_dubai', 'part_expense_dubai'));
    }

    public function store_approval(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $cars = $request->car_id;
            $parts = $request->part_id;

            $ref = getRef();
            
          if($cars)
          {
              foreach ($cars as $key => $car) {
                $car = import_cars::findOrFail($car);
                purchase::create([

                    'meter_type' => $car->meter_type,
                    'company' => $car->company,
                    'model' => $car->model,
                    'color' => $car->color,
                    'chassis' => $car->chassis,
                    'engine' => $car->engine,
                    'date' => now(),
                    'price' => $car->price,
                    'expense' => $request->expense_per_car_japan[$key] + $request->expense_per_car_dubai[$key],
                    'total' => $request->car_net_cost[$key],
                    'sale_price' => $request->car_sale_price[$key],
                    'notes' => $car->notes,
                    'status' => 'Available',
                    'type' => $car->type,
                    'purchase_type' => "Import",
                    'import_id' => $id,
                    'refID' => $ref,
                ]);
            }
          }
          if($parts)
          {

            foreach($parts as $key => $part){
                $part = import_parts::findOrFail($part);
                parts_purchase::create([
                    'description' => $part->part_name,
                    'qty' => $part->qty,
                    'price' => $request->part_price[$key],
                    'expense' => $request->part_japan_expense[$key] + $request->part_dubai_expense[$key],
                    'total' => $request->part_net_cost[$key],
                    'sale_price' => $request->part_sale_price[$key],
                    'date' => now(),
                    'purchase_type' => "Import",
                    'refID' => $ref,
                    'import_id' => $id,
                ]);
            }
        }
            imports::findOrFail($id)->update([
                'status' => 'Approved',
            ]);
            DB::commit();
               return redirect()->route('imports.index')->with('success', 'Import approved successfully');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
     
    }

    public function delete(Request $request)
    {
        $import = imports::findOrFail($request->id);
        $import->cars()->delete();
        $import->parts()->delete();
        $import->delete();
         session()->forget('confirmed_password');
        return redirect()->route('imports.index')->with('success', 'Import deleted successfully');
    }
}
