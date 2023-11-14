<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\DeletePermissionRequest;
use App\Http\Requests\GetPermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionCollection;
use App\Models\Permission;
use App\Repository\API\PermissionRepositoryAbstract;
use App\Repository\ErrorRepository;

class PermissionController extends BaseController
{
    public function __construct(PermissionRepositoryAbstract $permissionRepository)
    {
        $this->repository = $permissionRepository;
    }

    public function index(GetPermissionRequest $request)
    {
        $data = $this->repository->search($request->collect());
        return new PermissionCollection($data);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function store(CreatePermissionRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function update(UpdatePermissionRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
