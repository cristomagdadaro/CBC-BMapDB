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

    public function show(GetPermissionRequest $request)
    {
        $permission = $this->permissionRepository->find($request->validated()['id']);
        return new PermissionCollection($permission);
    }

    public function store(CreatePermissionRequest $request)
    {
        $permission = $this->permissionRepository->create($request->validated());
        return new PermissionCollection($permission);
    }

    public function update(UpdatePermissionRequest $request)
    {
        $permission = $this->permissionRepository->find($request->validated()['id']);
        $response = $this->permissionRepository->update($permission, $request->validated());

        if ($response instanceof \Exception)
            return response()->json(['message' => (new ErrorRepository($response))->getErrorMessage()], 200);

        return new PermissionCollection($response);
    }

    public function destroy(DeletePermissionRequest $request)
    {
        $permission = $this->permissionRepository->find($request->validated()['id']);
        $response = $this->permissionRepository->delete($permission);

        if ($response instanceof \Exception)
            return response()->json(['message' => (new ErrorRepository($response))->getErrorMessage()], 200);

        return response()->json(['message' => 'Permission deleted'], 200);
    }
}
