<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDataViewRequest;
use App\Http\Requests\GetDataViewsRequest;
use App\Http\Requests\UpdateDataViewRequest;
use App\Http\Resources\DataViewResource;
use App\Repository\API\DataViewRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DataViewController extends BaseController
{
    public function __construct(DataViewRepo $dataViewRepo)
    {
        $this->service =  $dataViewRepo;
    }

    public function index(GetDataViewsRequest $request, string $table = null): JsonResponse
    {
        $result = DB::table('data_views')
            ->select('user_account_id', 'model', 'visibility_guard', 'columns')
            ->when($table, fn($query) => $query->where('model', $table)) // Filter if table is provided
            ->get()
            ->groupBy('user_account_id')
            ->map(fn($models) =>
                $models->groupBy('model')->map(fn($visibilityGuards) =>
                $visibilityGuards->pluck('columns', 'visibility_guard')->toArray())->toArray())
            ->toArray();

        return $this->sendResponse($result);
    }

    public function show(GetDataViewsRequest $request, string $table): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $query = $this->service->model->where('model', $table);

        // If not admin, filter by user_account_id
        if ($user && !$user->isAdmin()) {
            $query->where('user_account_id', $user->id);
        }

        // Get data and group by visibility_guard
        $data = $query->get()->keyBy('visibility_guard');

        return $this->sendResponse(DataViewResource::collection($data));
    }


    public function  store(CreateDataViewRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdateDataViewRequest $request, $table, $uuid): JsonResponse
    {
        $dataView = $this->service->model->where('uuid', $uuid)->firstOrFail();
        $dataView->update($request->validated());
        return $this->sendResponse($dataView);
    }
}
