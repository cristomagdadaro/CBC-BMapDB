<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateCommoditiesRequest;
use App\Http\Requests\DeleteCommoditiesRequest;
use App\Http\Requests\GetCommoditiesRequest;
use App\Http\Requests\UpdateCommoditiesRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\CommodityRepositoryAbstract;
use Illuminate\Support\Collection;

class CommodityController extends BaseController
{
    public function __construct(CommodityRepositoryAbstract $commodityRepository)
    {
        $this->repository = $commodityRepository;
    }

    public function index(GetCommoditiesRequest $request)
    {
        $data = $this->repository->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function store(CreateCommoditiesRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function update(UpdateCommoditiesRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

    public function multiDestroy(DeleteCommoditiesRequest $request)
    {
        return $this->repository->multiDestroy($request->validated());
    }
}
