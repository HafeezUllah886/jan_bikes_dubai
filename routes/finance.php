<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AccountAdjustmentController;
use App\Http\Controllers\DepositWithdrawController;
use App\Http\Controllers\ExpenseCategoriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\TransferController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('account/view/{filter}', [AccountsController::class, 'index'])->name('accountsList');
    Route::get('account/statement/{id}/{from}/{to}', [AccountsController::class, 'show'])->name('accountStatement');
    Route::get('account/statement/pdf/{id}/{from}/{to}', [AccountsController::class, 'pdf']);
    Route::resource('account', AccountsController::class);

    Route::resource('transfers', TransferController::class);
    Route::get('transfer/delete/{ref}', [TransferController::class, 'delete'])->name('transfers.delete')->middleware(confirmPassword::class);

    Route::resource('accounts_adjustments', AccountAdjustmentController::class);
    Route::get('accounts_adjustment/delete/{ref}', [AccountAdjustmentController::class, 'delete'])->name('accounts_adjustment.delete')->middleware(confirmPassword::class);

    Route::resource('expensesCategories', ExpenseCategoriesController::class);
    Route::resource('expenses', ExpensesController::class);
    Route::get('expense/delete/{ref}', [ExpensesController::class, 'delete'])->name('expense.delete')->middleware(confirmPassword::class);

    Route::get('/accountbalance/{id}', function ($id) {
        // Call your Laravel helper function here
        $result = getAccountBalance($id);

        return response()->json(['data' => $result]);
    });
});

