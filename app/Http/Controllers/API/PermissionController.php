<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeletePermissionRequest;
use App\Http\Requests\GetPermissionRequest;
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

    public function destroy(DeletePermissionRequest $request)
    {
        $permission = $this->permissionRepository->find($request->validated()['id']);
        $response = $this->permissionRepository->delete($permission);

        if ($response instanceof \Exception)
            return response()->json(['message' => (new ErrorRepository($response))->getErrorMessage()], 200);

        return response()->json(['message' => 'Permission deleted'], 200);
    }
}
