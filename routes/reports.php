<?php

use App\Http\Controllers\reports\ledgerReportController;
use App\Http\Middleware\adminCheck;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', adminCheck::class)->group(function () {

    Route::get('/reports/ledger', [ledgerReportController::class, 'index'])->name('reportLedger');
    Route::get('/reports/ledgerData', [ledgerReportController::class, 'reportData'])->name('reportLedgerData');
});
