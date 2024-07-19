<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTWGServiceRequest;
use App\Http\Requests\GetTWGServiceRequest;
use App\Http\Requests\UpdateTWGServiceRequest;
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
        return new TWGServiceCollection($data);
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

}
