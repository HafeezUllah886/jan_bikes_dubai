<?php

namespace App\Http\Controllers;

use App\Models\imports;
use Illuminate\Http\Request;

class ImportApprovalController extends Controller
{
    public function index()
    {
        $imports = imports::orderBy('id', 'desc')->get();
        return view('import.index', compact('imports'));
    }
}
