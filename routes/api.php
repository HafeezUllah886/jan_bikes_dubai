<?php

use App\Http\Controllers\api\ImportAPIController;
use App\Models\accounts;
use Illuminate\Support\Facades\Route;

Route::post('/import/store', [ImportAPIController::class, 'store']);
Route::get('/customers', function () {
    return accounts::where('type', 'Customer')->select('id', 'title')->get();
});
