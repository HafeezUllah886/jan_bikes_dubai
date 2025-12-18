<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\purchase;
use App\Models\purchase_cars;
use App\Models\purchase_parts;
use Illuminate\Support\Facades\DB;

class PurchaseAPIController extends Controller
{
    public function store(Request $request)
{
    try {

        $check = purchase::where('key', $request->key)->first();

        if ($check) {
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase Already Sent',
            ], 200);
        }
        
        DB::beginTransaction();
        $ref = getRef();

        $purchase = purchase::create([
            'date' => $request->date,
            'c_no' => $request->cno,
            'bl_no' => $request->bl_no,
            'bl_amount' => $request->bl_amount,
            'bl_amount_pkr' => $request->bl_amount_pkr,
            'conversion_rate' => $request->ex_rate,
            'refID' => $ref,
            'key' => $request->key,
        ]);

        $total = 0;

        // Process cars
        if ($request->has('cars') && is_array($request->cars)) {
            foreach ($request->cars as $car) {
                $total += (float)$car['price_pkr'];
                purchase_cars::create([
                    'purchase_id' => $purchase->id,
                    'model' => $car['model'] ?? null,
                    'maker' => $car['maker'] ?? null,
                    'chassis_no' => $car['chassis_no'] ?? null,
                    'auction' => $car['auction'] ?? null,
                    'year' => $car['year'] ?? null,
                    'color' => $car['color'] ?? null,
                    'grade' => $car['grade'] ?? null,
                    'price' => $car['price'] ?? 0,
                    'price_pkr' => $car['price_pkr'] ?? 0,
                    'remarks' => $car['remarks'] ?? null,
                    'refID' => $ref,
                ]);
            }
        }

        // Process parts
        if ($request->has('parts') && is_array($request->parts)) {
            foreach ($request->parts as $part) {
                $total += (float)$part['price_pkr'];
                purchase_parts::create([
                    'purchase_id' => $purchase->id,
                    'description' => $part['description'] ?? null,
                    'weight_ltr' => $part['weight_ltr'] ?? null,
                    'grade' => $part['grade'] ?? null,
                    'qty' => $part['qty'] ?? 1,
                    'price' => $part['price'] ?? 0,
                    'price_pkr' => $part['price_pkr'] ?? 0,
                    'refID' => $ref,
                ]);
            }
        }

        // Update purchase with totals
        $net_amount = $total + (float)$request->bl_amount_pkr;

        $purchase->update([
            'container_amount' => $total,
            'net_amount' => $net_amount,
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Purchase Stored Successfully',
            'purchase_id' => $purchase->id
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
