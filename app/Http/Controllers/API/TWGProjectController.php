<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateTWGProjectRequest;
use App\Http\Requests\DeleteTWGProjectRequest;
use App\Http\Requests\GetTWGProjectRequest;
use App\Http\Requests\UpdateTWGProjectRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\TWGProjectRepo;
use Illuminate\Support\Collection;

class TWGProjectController extends BaseController
{
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

    public function show(GetTWGProjectRequest $request, int $id)
    {
        $this->service->appendWith(['expert']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function store(CreateTWGProjectRequest $request)
    {
        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        return $this->service->create($data);
    }

    public function update(UpdateTWGProjectRequest $request, $id)
    {
        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');
        return $this->service->update($id, $data);
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
