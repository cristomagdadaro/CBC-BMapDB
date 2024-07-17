<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetGeodataRequest;
use App\Http\Resources\BaseCollection;
use App\Models\Geodata;
use App\Repository\API\GeodataRepo;
use Illuminate\Http\Request;

class GeodataController extends Controller
{

    public function __construct(GeodataRepo $geodataRepository)
    {
        $this->repository = $geodataRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetGeodataRequest $request)
    {
        $data = $this->repository->search($request->collect());
        return new BaseCollection($data);
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
    public function show(Geodata $geodata)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Geodata $geodata)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Geodata $geodata)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Geodata $geodata)
    {
        //
    }
}
