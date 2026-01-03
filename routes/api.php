<?php

use App\Http\Controllers\api\ImportAPIController;
use Illuminate\Support\Facades\Route;

Route::post('/import/store', [ImportAPIController::class, 'store']);
