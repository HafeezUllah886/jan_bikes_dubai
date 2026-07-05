<?php

use App\Http\Controllers\PartsPurchaseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('purchase', PurchaseController::class);

    Route::post('purchase/store-expense-profit', [PurchaseController::class, 'storeExpenseProfit'])->name('purchase.storeExpenseProfit');
    Route::get('purchase/{purchase}/expense-profit', [PurchaseController::class, 'expenseProfit'])->name('purchase.expenseProfit');
    Route::post('purchase/expense-profit/{expenseProfit}/update', [PurchaseController::class, 'updateExpenseProfit'])->name('purchase.expenseProfit.update');
    Route::post('purchase/expense-profit/{expenseProfit}/delete', [PurchaseController::class, 'deleteExpenseProfit'])->name('purchase.expenseProfit.delete');

    Route::post('purchase/{id}/sell-booked', [PurchaseController::class, 'sellBooked'])->name('purchase.sellBooked');

    Route::post('purchase/{id}/book', [PurchaseController::class, 'bookPurchase'])->name('purchase.book');
    Route::get('purchase/{id}/mark-as-available', [PurchaseController::class, 'markasavailable'])->name('markasavailable');

    Route::get('purchases/delete/{id}', [PurchaseController::class, 'destroy'])->name('purchases.delete')->middleware(confirmPassword::class);

    Route::resource('part_purchase', PartsPurchaseController::class);
    Route::get('part_purchase/{parts_purchase}/expense-profit', [PartsPurchaseController::class, 'expenseProfit'])->name('part_purchase.expenseProfit');
    Route::post('part_purchase/expense-profit/{expenseProfit}/update', [PartsPurchaseController::class, 'updateExpenseProfit'])->name('part_purchase.expenseProfit.update');
    Route::post('part_purchase/expense-profit/{expenseProfit}/delete', [PartsPurchaseController::class, 'deleteExpenseProfit'])->name('part_purchase.expenseProfit.delete');
});
