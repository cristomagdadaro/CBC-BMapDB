<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateTWGProjectRequest;
use App\Http\Requests\DeleteTWGProjectRequest;
use App\Http\Requests\GetTWGProjectRequest;
use App\Http\Requests\UpdateTWGProjectRequest;
use App\Http\Resources\TWGProjectCollection;
use App\Repository\API\TWGProjectRepositoryAbstract;
use Illuminate\Support\Collection;

class TWGProjectController extends BaseController
{
    // constructor
    public function __construct(TWGProjectRepositoryAbstract $project)
    {
        $this->repository = $project;
    }

    public function index(GetTWGProjectRequest $request)
    {
        $data = $this->repository->search(new Collection($request->validated()));
        return new TWGProjectCollection($data);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function store(CreateTWGProjectRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function update(UpdateTWGProjectRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

    public function multiDestroy(DeleteTWGProjectRequest $request)
    {
        return $this->repository->multiDestroy($request->validated());
    }
}
