<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDataViewRequest;
use App\Http\Requests\GetDataViewsRequest;
use App\Http\Requests\UpdateDataViewRequest;
use App\Http\Resources\DataViewResource;
use App\Repository\API\DataViewRepo;
use Illuminate\Http\JsonResponse;

class DataViewController extends BaseController
{
    public function __construct(DataViewRepo $dataViewRepo)
    {
        $this->service =  $dataViewRepo;
    }

    public function index(GetDataViewsRequest $request, string $table = null): JsonResponse
    {
        /** @var DataViewRepo $dataViewRepo */
        $dataViewRepo = $this->service;

        $result = $dataViewRepo->getGroupedDataViews($table);

        return $this->sendResponse($result);
    }

    public function show(GetDataViewsRequest $request, string $table): JsonResponse
    {
        /** @var DataViewRepo $dataViewRepo */
        $dataViewRepo = $this->service;
        $data = $dataViewRepo->getDataViewsForTable($table, \Illuminate\Support\Facades\Auth::user());

        return $this->sendResponse(DataViewResource::collection($data));
    }


    public function  store(CreateDataViewRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdateDataViewRequest $request, $table, $uuid): JsonResponse
    {
        /** @var DataViewRepo $dataViewRepo */
        $dataViewRepo = $this->service;
        $dataView = $dataViewRepo->updateByUuid($uuid, $request->validated());
        
        return $this->sendResponse($dataView);
    }
}
