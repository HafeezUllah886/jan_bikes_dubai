<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\import_cars;
use App\Models\import_parts;
use App\Models\imports;
use Illuminate\Http\Request;
use App\Models\purchase;
use App\Models\purchase_cars;
use App\Models\purchase_parts;
use Illuminate\Support\Facades\DB;

class ImportAPIController extends Controller
{
    public function store(Request $request)
{
    try {

        $check = imports::where('export_id', $request->id)->first();

        if ($check) {
            return response()->json([
                'status' => 'error',
                'message' => 'Export Already Sent',
            ], 200);
        }
        
        DB::beginTransaction();
       
        $import = imports::create([
            'inv_no' => $request->inv_no,
            'export_id' => $request->id,
            'date' => $request->date,
            'c_no' => $request->cno,
        ]);

        // Process cars
        if ($request->has('cars') && is_array($request->cars)) {
            $car_expense = $request->car_expense;
            $bike_expense = $request->other_expense;
            $bike_count = 0;
            $car_count = 0;
            foreach ($request->cars as $c) {
                if (isset($c['type']) && $c['type'] == 'Bike') {
                    $bike_count++;
                } else {
                    $car_count++;
                }
            }

            $expensePerCar = $car_count > 0 ? $car_expense / $car_count : 0;
            $expensePerBike = $bike_count > 0 ? $bike_expense / $bike_count : 0;

            foreach ($request->cars as $car) {
                $expense = (isset($car['type']) && $car['type'] == 'bike') ? $expensePerBike : $expensePerCar;

                import_cars::create([
                    'import_id' => $import->id,
                    'type' => $car['type'] ?? null,
                    'meter_type' => $car['meter_type'] ?? null,
                    'company' => $car['company'] ?? null,
                    'model' => $car['model'] ?? null,
                    'color' => $car['color'] ?? null,
                    'chassis' => $car['chassis'] ?? null,
                    'engine' => $car['engine'] ?? null,
                    'price' => $car['price'] ?? 0,
                    'expenses' => $car['type'] == 'Bike' ? $expensePerBike : $expensePerCar,
                    'notes' => $car['notes'] ?? null,
                ]);
            }
        }

        // Process parts
        if ($request->has('parts') && is_array($request->parts)) {
            $total_parts = 0;
            foreach ($request->parts as $forqty) {
                $total_parts += $forqty['qty'];
            }
            $total_expense = $request->part_expense;
            $expensePerPart = $total_expense / $total_parts;
            foreach ($request->parts as $part) {
                import_parts::create([
                    'import_id' => $import->id,
                    'part_name' => $part['part_name'] ?? null,
                    'qty' => $part['qty'] ?? 1,
                    'price' => $part['price'] ?? 0,
                    'expenses' => $expensePerPart,
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Import Stored Successfully',
            'import_id' => $import->id
        ], 200);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
}
}
