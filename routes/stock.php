<?php

use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('stock/cars', [StockController::class, 'carsStock'])->name('stock.cars');
    Route::get('stock/parts', [StockController::class, 'partsStock'])->name('stock.parts');
});
