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

    public function index(GetBreederRequest $request)
    {
        $data = $this->breederRepository->search(new Collection($request->validated()));
        return new BreederCollection($data);
    }

    public function store(CreateBreederRequest $request)
    {
        return $this->breederRepository->create((array)$request->validated());
    }

    public function show($id)
    {
        return $this->breederRepository->find($id);
    }

    public function update(UpdateBreederRequest $request, $id)
    {
        $breeder = $this->breederRepository->find($id);
        return $this->breederRepository->update($breeder,(array)$request->validated());
    }

    public function destroy($id)
    {
        $breeder = $this->breederRepository->find($id);
        return $this->breederRepository->delete($breeder);
    }
}
