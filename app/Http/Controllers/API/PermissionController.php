<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\DeletePermissionRequest;
use App\Http\Requests\GetPermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionCollection;
use App\Models\Permission;
use App\Repository\API\PermissionRepository;
use App\Repository\ErrorRepository;

class PermissionController extends Controller
{
    protected PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function index(GetPermissionRequest $request)
    {
        $data = $this->permissionRepository->search($request->collect());
        return new PermissionCollection($data);
    }

    public function show($id)
    {
        return $this->permissionRepository->find($id);
    }

    public function store(CreatePermissionRequest $request)
    {
        $permission = $this->permissionRepository->create($request->validated());
        return $this->permissionRepository->save($permission);
    }

    public function update(UpdatePermissionRequest $request, $id)
    {
        $permission = $this->permissionRepository->find($id);
        return $this->permissionRepository->update($permission, $request->validated());
    }

    public function destroy($id)
    {
        $permission = $this->permissionRepository->find($id);
        return $this->permissionRepository->delete($permission);
    }
}
