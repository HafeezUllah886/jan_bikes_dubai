<?php

use App\Http\Controllers\api\PurchaseAPIController;
use Illuminate\Support\Facades\Route;

Route::post('/purchase/store', [PurchaseAPIController::class, 'store']);
