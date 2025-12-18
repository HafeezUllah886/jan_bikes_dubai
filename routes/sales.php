<?php

use App\Http\Middleware\adminCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

Route::middleware('auth', adminCheck::class)->group(function () {

  Route::resource('sale', SalesController::class);
});
