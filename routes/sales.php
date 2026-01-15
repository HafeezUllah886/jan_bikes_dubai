<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Middleware\confirmPassword;

Route::middleware('auth')->group(function () {

  Route::resource('sale', SaleController::class);
   Route::get('sale/getpart/{id}', [SaleController::class, 'getPart'])->name('sale.getpart');
   Route::get('sale/delete/{id}', [SaleController::class, 'delete'])->name('sale.delete')->middleware(confirmPassword::class);
});
