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
    Route::get('imports/{id}/view', [ImportApprovalController::class, 'view'])->name('imports.view');
    Route::get('imports/{id}/approve', [ImportApprovalController::class, 'approve'])->name('imports.approve');
    Route::post('imports/{id}/approve', [ImportApprovalController::class, 'store_approval'])->name('imports.approve.store');
    Route::get('imports/{id}/delete', [ImportApprovalController::class, 'delete'])->name('imports.delete')->middleware(confirmPassword::class);

});
