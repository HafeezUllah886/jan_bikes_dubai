<?php

use App\Models\accounts;
use App\Models\Booking;
use App\Models\import_cars;
use App\Models\parts_purchase;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\sale_details;

function totalSales()
{
    return $sales = sale_details::sum('ti');
}

function totalPurchases()
{
    return purchase::sum('net');
}

function totalSaleGst()
{
    return sale_details::sum('gstValue');
}

function totalPurchaseGst()
{
    return purchase_details::sum('gstValue');
}

function myBalance()
{
    $accounts = accounts::where('type', '!=', 'Customer')->get();
    $balance = 0;
    foreach ($accounts as $account) {
        $balance += getAccountBalance($account->id);
    }

    $customers = accounts::where('type', 'Customer')->get();
    $customersBalance = 0;
    foreach ($customers as $customer) {
        $customersBalance += getAccountBalance($customer->id);
    }

    $accountsBalance = $balance - $customersBalance;
    $stockValue = stockValue();
    $balance = $accountsBalance + $stockValue;

    return $balance;
}

function accountBalanceByType($type)
{
    $accounts = accounts::where('type', $type)->get();
    $balance = 0;
    foreach ($accounts as $account) {
        $balance += getAccountBalance($account->id);
    }

    return $balance;
}

function totalAdvanceBooked()
{
    $bookingAdvance = Booking::sum('advance');
    $importAdvance = import_cars::sum('booking_advance');

    return $bookingAdvance + $importAdvance;
}

function totalAvailablePurchasesAmount()
{
    $purchases = purchase::with('expenseProfits')->where('status', 'Available')->get();
    $carsAndBikesCost = $purchases->sum(function ($p) {
        return $p->costWithExpenseProfit();
    });

    $parts = parts_purchase::with('expenseProfits')->where('status', 'Available')->get();
    $partsCost = $parts->sum(function ($part) {
        $expense = $part->expenseProfits->where('type', 'expense')->sum('amount');
        $profit = $part->expenseProfits->where('type', 'profit')->sum('amount');

        return $part->total + $expense - $profit;
    });

    return $carsAndBikesCost + $partsCost;
}
