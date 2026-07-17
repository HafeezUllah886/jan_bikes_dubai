<?php

namespace App\Http\Controllers;


use App\Models\sales;
use App\Models\expenses;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index(Request $request)
    {
        $monthlySales = [];
        $monthlyExpenses = [];
        $monthlyProfit = [];

        $currentYear = $request->input('year', date('Y'));

        for ($i = 1; $i <= 12; $i++) {
            $salesForMonth = sales::whereYear('date', $currentYear)->whereMonth('date', $i)->sum('total');
            $expensesForMonth = expenses::whereYear('date', $currentYear)->whereMonth('date', $i)->sum('amount');
            
            $monthlySales[] = $salesForMonth;
            $monthlyExpenses[] = $expensesForMonth;
            $monthlyProfit[] = 0; // Keeping profit at 0 or you can add profit logic here
        }

        $lastMonth = date('m') - 1;
        if($lastMonth == 0) $lastMonth = 12;

        $lastMonthYear = date('m') == 1 ? $currentYear - 1 : $currentYear;

        $last_sale = sales::whereYear('date', $lastMonthYear)->whereMonth('date', $lastMonth)->sum('total');
        $last_expense = expenses::whereYear('date', $lastMonthYear)->whereMonth('date', $lastMonth)->sum('amount');
        $last_profit = 0;

        return view('dashboard.index', compact(
            'monthlySales', 
            'monthlyExpenses', 
            'monthlyProfit',
            'last_sale',
            'last_expense',
            'last_profit'
        ));
    }
}
