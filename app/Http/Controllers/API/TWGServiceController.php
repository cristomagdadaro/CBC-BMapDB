<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateTWGServiceRequest;
use App\Http\Requests\DeleteTWGServiceRequest;
use App\Http\Requests\GetTWGServiceRequest;
use App\Http\Requests\UpdateTWGServiceRequest;
use App\Http\Resources\BaseCollection;
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

    public function show(GetTWGServiceRequest $request, int $id)
    {
        $this->service->appendWith(['expert']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function store(CreateTWGServiceRequest $request)
    {
        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        return $this->service->create($data);
    }

    public function update(UpdateTWGServiceRequest $request,$id)
    {
        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');
        return $this->service->update($id, $data);
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
