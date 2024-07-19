<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetBreederRequest;
use App\Http\Requests\CreateBreederRequest;
use App\Http\Requests\GetCommoditiesRequest;
use App\Http\Requests\UpdateBreederRequest;
use App\Http\Requests\DeleteBreederRequest;
use App\Repository\API\BreederRepo;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BaseCollection;

class BreederController extends BaseController
{
    public function __construct(BreederRepo $breederRepository)
    {
        $this->service = $breederRepository;
    }

    public function index(GetBreederRequest $request): BaseCollection
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function store(CreateBreederRequest $request): JsonResponse
    {
        $data = $this->service->create($request->validated());
        return $this->sendResponse('Breeder created successfully.', $data);
    }

    public function show(int $id): JsonResponse
    {
        $data = $this->service->find($id);
        return $this->sendResponse('Breeder retrieved successfully.', $data);
    }

    public function noPageSearch(int $id, GetCommoditiesRequest $request)
    {
        $data = $this->service->find($id)->load('commodities');
        return new BaseCollection($data->commodities);
    }

    public function update(UpdateBreederRequest $request, int $id): JsonResponse
    {
        $data = $this->service->update($id, $request->validated());
        return $this->sendResponse('Breeder updated successfully.', $data);
    }

    public function destroy($id): JsonResponse
    {
        $this->service->delete($id);
        return $this->sendResponse('Breeder deleted successfully.');
    }

    public function multiDestroy(DeleteBreederRequest $request): JsonResponse
    {
        $this->service->multiDestroy($request->validated());
        return $this->sendResponse('Breeders deleted successfully.');
    }
}
