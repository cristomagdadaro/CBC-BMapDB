<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBreederRequest;
use App\Http\Requests\GetBreederRequest;
use App\Http\Requests\UpdateBreederRequest;
use App\Http\Resources\BreederCollection;
use App\Repository\API\BreederRepository;
use Illuminate\Support\Collection;

class BreederController extends Controller
{
    protected BreederRepository $breederRepository;

    public function __construct(BreederRepository $breederRepository)
    {
        $this->breederRepository = $breederRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetBreederRequest $request)
    {
        $data = $this->breederRepository->search(new Collection($request->validated()));
        return new BreederCollection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBreederRequest $request)
    {
        $breeder = $this->breederRepository->create((array)$request->collect());
        return $breeder;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $breeder = $this->breederRepository->find($id);
        return $breeder;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBreederRequest $request, $id)
    {
        $breeder = $this->breederRepository->find($id);
        $breeder->update((array)$request->validated());
        return $breeder;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $breeder = $this->breederRepository->find($id);
        $breeder->delete();
        return $breeder;
    }
}
