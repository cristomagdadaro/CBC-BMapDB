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
        $this->service->appendWith(['expert']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function store(CreateTWGServiceRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(UpdateTWGServiceRequest $request,$id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteTWGServiceRequest $request)
    {
        return $this->service->multiDestroy($request->validated());
    }
}
