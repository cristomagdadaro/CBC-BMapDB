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

    public function __construct(BreederRepository $breederRepository)
    {
        $this->repository = $breederRepository;
    }

    public function index(GetBreederRequest $request)
    {
        $data = $this->repository->search(new Collection($request->validated()));
        return new BreederCollection($data);
    }

    public function store(CreateBreederRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function update(UpdateBreederRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
