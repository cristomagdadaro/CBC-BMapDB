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

    public function index(GetCommoditiesRequest $request, $id = null)
    {
        $this->service->appendWith(['breeder']);
        if ($id) {
            // Set withPagination to false to return the builder instead of the paginator, for the Map search box. By Breeder.
            $per_page = $request->validated()['per_page'] ?? 10;
            $page = $request->validated()['page'] ?? 1;
            $data = $this->service->search(new Collection($request->validated()), false);
            return new BaseCollection($data->where('breeder_id', $id)->orderBy('id', 'asc')->paginate($per_page, ['*'], 'page', $page)->withQueryString());
        } else {
            $data = $this->service->search(new Collection($request->validated()));
            return new BaseCollection($data);
        }
    }

    /** API used at Map search box*/
    public function noPage(GetCommoditiesRequest $request)
    {
        // Set withPagination to false to return the builder instead of the paginator, for the Map search box. All Commodities.
        $this->service->appendWith(['breeder']);
        $data = $this->service->search(new Collection($request->validated()), false);
        return new BaseCollection($data->get());
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
