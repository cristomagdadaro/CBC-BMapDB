<?php

namespace App\Http\Controllers;

use App\Models\TWGExpert;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TWGController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Projects/TWGDatabase', [
            'twg_experts' => TWGExpert::with('twg_projects')->get(),
        ]);
    }

    public function table()
    {
        return TWGExpert::all();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TWGExpert $tWG)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TWGExpert $tWG)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TWGExpert $tWG)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TWGExpert $tWG)
    {
        //
    }
}
