<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;

Route::middleware('auth')->group(function () {

  Route::resource('sale', SaleController::class);
   Route::get('sale/getpart/{id}', [SaleController::class, 'getPart'])->name('sale.getpart');
});
