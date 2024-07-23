<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTWGServiceRequest;
use App\Http\Requests\DeleteTWGServiceRequest;
use App\Http\Requests\GetTWGServiceRequest;
use App\Http\Requests\UpdateTWGServiceRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\TWGServiceCollection;
use App\Repository\API\TWGServiceRepo;
use Illuminate\Support\Collection;

class TWGServiceController extends BaseController
{
    public function __construct(TWGServiceRepo $service)
    {
        $this->service = $service;
    }

    public function index(GetTWGServiceRequest $request)
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show($id)
    {
        $data = $this->service->find($id);
        return $this->sendResponse('TWG Service retrieved successfully.', $data);
    }

    public function store(CreateTWGServiceRequest $request)
    {
        $data = $this->service->create($request->validated());
        return $this->sendResponse('TWG Service created successfully.', $data);
    }

    public function update(UpdateTWGServiceRequest $request,$id)
    {
        $data = $this->service->update($id, $request->validated());
        return $this->sendResponse('TWG Service updated successfully.', $data);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->sendResponse('TWG Service deleted successfully.');
    }

    public function multiDestroy(DeleteTWGServiceRequest $request)
    {
        $this->service->multiDestroy($request->validated());
        return $this->sendResponse('TWG Services deleted successfully.');
    }
}
