<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTWGServiceRequest;
use App\Http\Requests\GetTWGServiceRequest;
use App\Http\Requests\UpdateTWGServiceRequest;
use App\Http\Resources\TWGServiceCollection;
use App\Repository\API\TWGServiceRepositoryAbstract;
use Illuminate\Support\Collection;

class TWGServiceController extends BaseController
{
    public function __construct(TWGServiceRepositoryAbstract $service)
    {
        $this->repository = $service;
    }

    public function index(GetTWGServiceRequest $request)
    {
        $data = $this->repository->search(new Collection($request->validated()));
        return new TWGServiceCollection($data);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function store(CreateTWGServiceRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function update(UpdateTWGServiceRequest $request,$id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

}
