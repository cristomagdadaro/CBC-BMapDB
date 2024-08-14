<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateApplicationRequest;
use App\Http\Requests\GetApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Resources\ApplicationCollection;
use App\Http\Resources\BaseCollection;
use App\Models\Application;
use App\Repository\API\ApplicationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ApplicationController extends BaseController
{

    public function __construct(ApplicationRepo $applicationRepository)
    {
        $this->service = $applicationRepository;
    }

    public function index(GetApplicationRequest $request)
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function store(CreateApplicationRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(UpdateApplicationRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
