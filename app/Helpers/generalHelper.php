<?php

use App\Models\material_stock;
use App\Models\parts_purchase;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\ref;
use App\Models\sale_details;
use App\Models\sale_parts;
use App\Models\stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

function getRef(){
    $ref = ref::first();
    if($ref){
        $ref->ref = $ref->ref + 1;
    }
    else{
        $ref = new ref();
        $ref->ref = 1;
    }
    $ref->save();
    return $ref->ref;
}

function firstDayOfMonth()
{
    $startOfMonth = Carbon::now()->startOfMonth();

    return $startOfMonth->format('Y-m-d');
}
function lastDayOfMonth()
{

    $endOfMonth = Carbon::now()->endOfMonth();

    return $endOfMonth->format('Y-m-d');
}

function firstDayOfCurrentYear() {
    $startOfYear = Carbon::now()->startOfYear();
    return $startOfYear->format('Y-m-d');
}

function lastDayOfCurrentYear() {
    $endOfYear = Carbon::now()->endOfYear();
    return $endOfYear->format('Y-m-d');
}

function firstDayOfPreviousYear() {
    $startOfPreviousYear = Carbon::now()->subYear()->startOfYear();
    return $startOfPreviousYear->format('Y-m-d');
}

function lastDayOfPreviousYear() {
    $endOfPreviousYear = Carbon::now()->subYear()->endOfYear();
    return $endOfPreviousYear->format('Y-m-d');
}

function update_parts_available_qty(){
    $purchases = parts_purchase::where('status', 'Available')->get();
    foreach($purchases as $purchase){
       $sales = sale_parts::where('purchase_id', $purchase->id)->sum('qty');
       $available_qty = $purchase->qty - $sales;
       if($available_qty < 1){
           $purchase->status = 'Sold';
           $purchase->save();
       }
    }
}

function projectNameAuth()
{
    return "Jan Bikes Dubai";
}

function projectNameHeader()
{
    return "JAN BIKES DUBAI";
}
function projectNameShort()
{
    return "JBD";
}
