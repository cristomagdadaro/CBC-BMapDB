<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateApplicationRequest;
use App\Http\Requests\GetApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Resources\ApplicationCollection;
use App\Models\Application;
use App\Repository\API\ApplicationRepository;
use Illuminate\Http\Request;

class ApplicationController extends BaseController
{

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->repository = $applicationRepository;
    }

    public function index(GetApplicationRequest $request)
    {
        $data = $this->repository->search($request->collect());
        return new ApplicationCollection($data);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function store(CreateApplicationRequest $request)
    {
        $application = $this->repository->create($request->validated());
        return $this->repository->save($application);
    }

    public function update(UpdateApplicationRequest $request, $id)
    {
        $application = $this->repository->find($id);
        return $this->repository->update($application, $request->validated());
    }

    public function destroy($id)
    {
        $application = $this->repository->find($id);
        return $this->repository->delete($application);
    }
}
