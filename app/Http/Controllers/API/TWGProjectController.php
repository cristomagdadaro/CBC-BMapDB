<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateTWGProjectRequest;
use App\Http\Requests\DeleteTWGProjectRequest;
use App\Http\Requests\GetTWGProjectRequest;
use App\Http\Requests\UpdateTWGProjectRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\TWGProjectCollection;
use App\Repository\API\TWGProjectRepo;
use Illuminate\Support\Collection;

class TWGProjectController extends BaseController
{
    // constructor
    public function __construct(TWGProjectRepo $project)
    {
        $this->service = $project;
    }

    public function index(GetTWGProjectRequest $request)
    {
        $this->service->appendWith(['expert']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function store(CreateTWGProjectRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(UpdateTWGProjectRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteTWGProjectRequest $request)
    {
        return $this->service->multiDestroy($request->validated());
    }
}
