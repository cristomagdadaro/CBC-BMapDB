<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Interfaces\BaseControllerInterface;
use App\Http\Requests\CreateCommoditiesRequest;
use App\Http\Requests\DeleteCommoditiesRequest;
use App\Http\Requests\GetCommoditiesRequest;
use App\Http\Requests\UpdateCommoditiesRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\CommodityRepo;
use Illuminate\Support\Collection;

class CommodityController extends BaseController
{
    public function __construct(CommodityRepo $commodityRepository)
    {
        $this->service = $commodityRepository;
    }

    public function index(GetCommoditiesRequest $request)
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    /** API used at Map search box*/
    public function noPage(GetCommoditiesRequest $request)
    {
        $data = $this->service->search(new Collection($request->validated()), false);
        return new BaseCollection($data);
    }

    public function store(CreateCommoditiesRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function update(UpdateCommoditiesRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteCommoditiesRequest $request)
    {
        return $this->service->multiDestroy($request->validated());
    }
}
