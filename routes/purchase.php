<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasePaymentsController;
use App\Http\Middleware\adminCheck;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;
use App\Exports\PurchasesExport;
use App\Http\Controllers\PartsPurchaseController;
use App\Imports\PurchasesImport;
use Maatwebsite\Excel\Facades\Excel;

Route::middleware('auth')->group(function () {

    Route::resource('purchase', PurchaseController::class);

    Route::get("purchases/delete/{id}", [PurchaseController::class, 'destroy'])->name('purchases.delete')->middleware(confirmPassword::class);

   Route::resource('part_purchase', PartsPurchaseController::class);
  
});
