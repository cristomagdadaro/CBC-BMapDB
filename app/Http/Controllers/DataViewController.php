<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDataViewRequest;
use App\Http\Requests\GetDataViewsRequest;
use App\Http\Requests\UpdateDataViewRequest;
use App\Repository\API\DataViewRepo;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        $user = auth()->user();
        $query = $this->service->model->where('model', $table);

        // If not admin, filter by user_account_id
        if (!$user->isAdmin()) {
            $query->where('user_account_id', $user->id);
        }

        // Get data and group by visibility_guard
        $data = $query->get()->groupBy('visibility_guard')->mapWithKeys(function ($items, $key) {
            $item = $items->first(); // Assuming one row per visibility_guard
            return [
                $key => [
                    'uuid'             => $item->uuid,
                    'user_account_id'  => $item->user_account_id,
                    'model'            => $item->model,
                    'columns'          => $item->columns,
                    'default'          => Schema::getColumnListing($item->model),
                    'visibility_guard' => $item->visibility_guard,
                    'created_at'       => $item->created_at,
                    'updated_at'       => $item->updated_at,
                    'deleted_at'       => $item->deleted_at,
                ]
            ];
        });

        return $this->sendResponse($data);
    }


    public function  store(CreateDataViewRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdateDataViewRequest $request, $table, $uuid): JsonResponse
    {
        $data = $this->service->model->where('uuid', $uuid);
        return $this->sendResponse($data->update($request->validated()));
    }
}
