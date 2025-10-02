<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\BaseControllerInterface;
use App\Http\Resources\BaseCollection;
use App\Repository\AbstractRepoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

abstract class BaseController extends Controller implements BaseControllerInterface
{
    protected AbstractRepoService $service;

    public function _index($request): BaseCollection
    {
        $this->authorize('view', $this->service->model);

        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function _show($request, int $id): JsonResponse
    {
        $this->authorize('view', $this->service->model);

        $with = $request->input('with');
        $count = $request->input('count');

        if ($with) {
            $this->service->appendWith = explode(',', $with);
        }
        if ($count) {
            $this->service->appendCount = explode(',', $count);
        }

        return $this->sendResponse($this->service->find($id, $request->collect()));
    }

    public function _store($request): JsonResponse
    {
        $this->authorize('create', $this->service->model);
        return $this->service->create($request->validated());
    }

    public function _update($request, int $id): JsonResponse
    {
        $this->authorize('update', $this->service->model);
        return $this->service->update($id, $request->validated());
    }

    public function _destroy(int $id)
    {
        $this->authorize('delete', $this->service->model);
        return $this->service->delete($id);
    }

    public function _multiDestroy($request)
    {
        $this->authorize('delete', $this->service->model);
        return $this->service->multiDestroy($request->validated());
    }

    public function sendResponse($data = null): JsonResponse
    {
        $response = [
            'data' => $data,
        ];

        return response()->json($response);
    }

    protected function insertUserId($request): array
    {
        if (!auth()->user()->isAdmin()) {
            return array_merge($request, ['user_id' => auth()->id()]);
        }

        return $request;
    }
}
