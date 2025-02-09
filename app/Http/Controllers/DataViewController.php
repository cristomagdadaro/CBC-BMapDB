<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDataViewRequest;
use App\Http\Requests\GetDataViewsRequest;
use App\Http\Requests\UpdateDataViewRequest;
use App\Repository\API\DataViewRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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

    public function show(GetDataViewsRequest $request, string $table)
    {
        if (auth()->user()->isAdmin()){
            $data = $this->service->model->all()->where('model', $table)->groupBy('visibility_guard');
        } else {
            $id = auth()->user()->id;
            $data = $this->service->model->all()->where('model', $table)->where('user_account_id', $id)->groupBy('visibility_guard');
        }

        $default = Schema::table($table, function ($res) { return  $this->sendResponse([$res]);});
        return $this->sendResponse([$data, ['default' => $default]]);
    }

    public function  store(CreateDataViewRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdateDataViewRequest $request)
    {
        $id = auth()->user()->id;
        $data = $this->service->model->where('user_account_id', $id);
        $data->update($request->validated());

        return $this->sendResponse($data);
    }
}
