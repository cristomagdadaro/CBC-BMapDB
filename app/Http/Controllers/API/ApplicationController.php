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

class ApplicationController extends BaseController
{

    public function __construct(ApplicationRepo $applicationRepository)
    {
        $this->service = $applicationRepository;
    }

    public function index(GetApplicationRequest $request)
    {
        $data = $this->service->search($request->collect());
        return new BaseCollection($data);
    }

    public function show($id)
    {
        $data = $this->service->find($id);
        return $this->sendResponse('Application retrieved successfully.', $data);
    }

    public function store(CreateApplicationRequest $request)
    {
        $data = $this->service->create($request->validated());
        return $this->sendResponse('Application created successfully.', $data);
    }

    public function update(UpdateApplicationRequest $request, $id)
    {
        $data = $this->service->update($id, $request->validated());
        return $this->sendResponse('Application updated successfully.', $data);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->sendResponse('Application deleted successfully.');
    }
}
