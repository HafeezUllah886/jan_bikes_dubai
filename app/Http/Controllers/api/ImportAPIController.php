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
            'bike_expenses' => $request->bike_expenses,
            'car_expenses' => $request->car_expenses,
            'part_expenses' => $request->part_expenses,
        ]);

        // Process cars
        if ($request->has('cars') && is_array($request->cars)) {

            foreach ($request->cars as $car) {

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
                    'notes' => $car['notes'] ?? null,
                ]);
            }
        }

        // Process parts
        if ($request->has('parts') && is_array($request->parts)) {
            foreach ($request->parts as $part) {
                import_parts::create([
                    'import_id' => $import->id,
                    'part_name' => $part['part_name'] ?? null,
                    'qty' => $part['qty'] ?? 1,
                    'price' => $part['price'] ?? 0,
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
