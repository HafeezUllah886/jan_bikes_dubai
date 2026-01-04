<?php

namespace App\Http\Controllers;

use App\Models\imports;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ImportApprovalController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start ?? Carbon::now()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->toDateString();
        $imports = imports::orderBy('id', 'desc')->whereBetween('date', [$start, $end])->get();
        return view('import.index', compact('imports', 'start', 'end'));
    }

    public function view(Request $request)
    {
        $import = imports::findOrFail($request->id);
        return view('import.view', compact('import'));
    }

    public function approve(Request $request, $id)
    {
        $import = imports::findOrFail($id);
        $car_expense_dubai = $request->car_expense;
        $bike_expense_dubai = $request->bike_expense;
        $part_expense_dubai = $request->parts_expense;
        
        return view('import.approve', compact('import', 'car_expense_dubai', 'bike_expense_dubai', 'part_expense_dubai'));
    }

    public function delete(Request $request)
    {
        $import = imports::findOrFail($request->id);
        $import->delete();
        return redirect()->route('imports.index')->with('success', 'Import deleted successfully');
    }
}
