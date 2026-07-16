<?php

use App\Http\Controllers\reports\ledgerReportController;
use App\Http\Controllers\reports\profitLossReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/reports/ledger', [ledgerReportController::class, 'index'])->name('reportLedger');
    Route::get('/reports/ledgerData', [ledgerReportController::class, 'reportData'])->name('reportLedgerData');
    Route::get('/reports/profit-loss', [profitLossReportController::class, 'index'])->name('reportProfitLoss');
    Route::get('/reports/profit-loss-data', [profitLossReportController::class, 'reportData'])->name('reportProfitLossData');
});
