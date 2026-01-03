<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasePaymentsController;
use App\Http\Middleware\adminCheck;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;
use App\Exports\PurchasesExport;
use App\Http\Controllers\ImportApprovalController;
use App\Imports\PurchasesImport;
use Maatwebsite\Excel\Facades\Excel;

Route::middleware('auth')->group(function () {

    Route::get('imports', [ImportApprovalController::class, 'index'])->name('imports.index');

});
