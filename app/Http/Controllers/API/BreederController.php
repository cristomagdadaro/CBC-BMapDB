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
        return $this->service->create($request->validated());
    }

    public function show(int $id): JsonResponse
    {
        return $this->service->find($id);
    }

    public function noPageSearch(int $id, GetCommoditiesRequest $request)
    {
        $this->service->appendWith(['commodities']);
        $data = $this->service->search(new Collection($request->validated()), false);
        if (count($data) === 0) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json(['data' => $data[0]->commodities]);
    }

    public function update(UpdateBreederRequest $request, int $id): JsonResponse
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id): JsonResponse
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteBreederRequest $request): JsonResponse
    {
        return $this->service->multiDestroy($request->validated());
    }
}
