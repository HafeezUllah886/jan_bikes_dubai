<?php

namespace App\Http\Controllers;

use App\Models\profitCategories;
use Illuminate\Http\Request;

class ProfitCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cats = profitCategories::orderBy('name', 'asc')->get();

        return view('finance.extra_profit.categories', compact('cats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        profitCategories::create($request->all());

        return back()->with('msg', 'Category Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(profitCategories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(profitCategories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        profitCategories::find($id)->update($request->all());

        return back()->with('msg', 'Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(profitCategories $categories)
    {
        //
    }
}
