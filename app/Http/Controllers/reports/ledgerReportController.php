<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\transactions;
use Illuminate\Http\Request;

class ledgerReportController extends Controller
{
    public function index()
    {
        return view('reports.ledger_report.index');
    }

    public function reportData(Request $request)
    {
        $request->validate([
            'accountID' => 'required|exists:accounts,id',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $accountID = $request->accountID;
        $from = $request->from;
        $to = $request->to;

        $transactions = transactions::with('account')
            ->where('account_id', $accountID)
            ->whereBetween('date', [$from, $to])
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $pre_cr = transactions::where('account_id', $accountID)->whereDate('date', '<', $from)->sum('cr');
        $pre_db = transactions::where('account_id', $accountID)->whereDate('date', '<', $from)->sum('db');
        $pre_balance = $pre_cr - $pre_db;

        $cur_cr = transactions::where('account_id', $accountID)->sum('cr');
        $cur_db = transactions::where('account_id', $accountID)->sum('db');
        $cur_balance = $cur_cr - $cur_db;

        return view('reports.ledger_report.details', compact('transactions', 'pre_balance', 'cur_balance', 'from', 'to'));
    }
}
