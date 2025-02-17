<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseCollection;
use App\Repository\AbstractRepoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

abstract class BaseController extends Controller
{
    protected AbstractRepoService $service;

    public function _index($request): BaseCollection
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function _show($request, int $id): JsonResponse
    {
        $with = $request->toArray()['with'] ?? null;
        $count = $request->toArray()['count'] ?? null;

        if ($with)
            $this->service->appendWith = explode(',',$with);
        if ($count)
            $this->service->appendCount = explode(',',$count);

        return $this->sendResponse($this->service->find($id, $request->collect()));
    }

    public function _store($request): JsonResponse
    {
        return $this->service->create($request->validated());
    }

    public function _update($request, int $id): JsonResponse
    {
        return $this->service->update($id, $request->validated());
    }

    public function _destroy(int $id)
    {
        return $this->service->delete($id);
    }

    public function _multiDestroy($request)
    {
        return $this->service->multiDestroy($request->validated());
    }

    public function sendResponse(...$data): JsonResponse
    {
        $response = [
            'data' => $data,
        ];

        return response()->json($response);
    }

    protected function insertUserId($request)
    {
        if (!auth()->user()->isAdmin()) {
            return array_merge($request, ['user_id' => auth()->id()]);
        }

        return $request;
    }
}
