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

    public function approve(Request $request)
    {
        $import = imports::findOrFail($request->id);
        $import->status = 'approved';
        $import->save();
        return redirect()->route('imports.index')->with('success', 'Import approved successfully');
    }

    public function delete(Request $request)
    {
        $import = imports::findOrFail($request->id);
        $import->delete();
        return redirect()->route('imports.index')->with('success', 'Import deleted successfully');
    }
}
