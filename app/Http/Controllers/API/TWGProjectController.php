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
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show($id)
    {
        $data = $this->service->find($id);
        return $this->sendResponse('TWG Project retrieved successfully.', $data);
    }

    public function store(CreateTWGProjectRequest $request)
    {
        $data = $this->service->create($request->validated());
        return $this->sendResponse('TWG Project created successfully.', $data);
    }

    public function update(UpdateTWGProjectRequest $request, $id)
    {
        $data = $this->service->update($id, $request->validated());
        return $this->sendResponse('TWG Project updated successfully.', $data);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->sendResponse('TWG Project deleted successfully.');
    }

    public function multiDestroy(DeleteTWGProjectRequest $request)
    {
        $this->service->multiDestroy($request->validated());
        return $this->sendResponse('TWG Projects deleted successfully.');
    }
}
