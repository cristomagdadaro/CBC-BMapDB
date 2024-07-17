<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
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

    public function store(CreateCommoditiesRequest $request)
    {
        $data =  $this->service->create($request->validated());
        return $this->sendResponse('Commodity created successfully.', $data);
    }

    public function show($id)
    {
        $data = $this->service->find($id);
        return $this->sendResponse('Commodity retrieved successfully.', $data);
    }

    public function update(UpdateCommoditiesRequest $request, $id)
    {
        $data = $this->service->update($id, $request->validated());
        return $this->sendResponse('Commodity updated successfully.', $data);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->sendResponse('Commodity deleted successfully.');
    }

    public function multiDestroy(DeleteCommoditiesRequest $request)
    {
        $this->service->multiDestroy($request->validated());
        return $this->sendResponse('Commodities deleted successfully.');
    }
}
