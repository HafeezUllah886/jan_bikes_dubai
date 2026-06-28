<?php

use App\Http\Controllers\AccountAdjustmentController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AdvancePaymentController;
use App\Http\Controllers\ExpenseCategoriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ExtraProfitController;
use App\Http\Controllers\PaymentReceivingController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProfitCategoriesController;
use App\Http\Controllers\ProfitDistributionController;
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
    Route::post('expenses/bulk', [ExpensesController::class, 'storeBulk'])->name('expenses.bulk');
    Route::resource('expenses', ExpensesController::class);
    Route::get('expense/delete/{ref}', [ExpensesController::class, 'delete'])->name('expense.delete')->middleware(confirmPassword::class);

    Route::resource('receivings', PaymentReceivingController::class);
    Route::get('receiving/delete/{ref}', [PaymentReceivingController::class, 'delete'])->name('receiving.delete')->middleware(confirmPassword::class);

    Route::resource('payments', PaymentsController::class);
    Route::get('payment/delete/{ref}', [PaymentsController::class, 'delete'])->name('payment.delete')->middleware(confirmPassword::class);

    Route::resource('advances', AdvancePaymentController::class);
    Route::get('advance/delete/{ref}', [AdvancePaymentController::class, 'delete'])->name('advance.delete')->middleware(confirmPassword::class);
    Route::get('advance/item-delivered/{id}', [AdvancePaymentController::class, 'markAsItemDelivered'])->name('advance.item-delivered');

    Route::resource('extraprofitCategories', ProfitCategoriesController::class);
    Route::resource('extra_profit', ExtraProfitController::class);
    Route::get('extra_profit/delete/{ref}', [ExtraProfitController::class, 'delete'])->name('extra_profit.delete')->middleware(confirmPassword::class);

    Route::get('profit_distribution', [ProfitDistributionController::class, 'index'])->name('profit_distribution.index');
    Route::get('profit_distribution/create', [ProfitDistributionController::class, 'create'])->name('profit_distribution.create');
    Route::get('profit_distribution/show/{id}', [ProfitDistributionController::class, 'show'])->name('profit_distribution.show');
    Route::post('profit_distribution', [ProfitDistributionController::class, 'store'])->name('profit_distribution.store');
    Route::get('profit_distribution/{id}/edit', [ProfitDistributionController::class, 'edit'])->name('profit_distribution.edit');
    Route::put('profit_distribution/{id}', [ProfitDistributionController::class, 'update'])->name('profit_distribution.update');
    Route::delete('profit_distribution/{id}', [ProfitDistributionController::class, 'destroy'])->name('profit_distribution.destroy');

    Route::get('/accountbalance/{id}', function ($id) {
        // Call your Laravel helper function here
        $result = getAccountBalance($id);

        return response()->json(['data' => $result]);
    });
});
