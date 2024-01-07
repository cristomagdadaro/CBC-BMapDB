<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTWGProductRequest;
use App\Http\Requests\DeleteTWGProductRequest;
use App\Http\Requests\GetTWGProductRequest;
use App\Http\Requests\UpdateTWGProductRequest;
use App\Http\Resources\TWGProductCollection;
use App\Repository\API\TWGProductRepositoryAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TWGProductController extends BaseController
{
    public function __construct(TWGProductRepositoryAbstract $productRepository)
    {
        $this->repository = $productRepository;
    }

    public function index(GetTWGProductRequest $request)
    {
        $data = $this->repository->search(new Collection($request->validated()));
        return new TWGProductCollection($data);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function store(CreateTWGProductRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function update(UpdateTWGProductRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

    public function multiDestroy(DeleteTWGProductRequest $request)
    {
        return $this->repository->multiDestroy($request->validated());
    }
}
