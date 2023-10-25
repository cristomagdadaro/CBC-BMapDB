<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteRoleRequest;
use App\Http\Requests\GetRoleRequest;
use App\Http\Resources\RoleCollection;
use App\Models\Role;
use App\Repository\API\RoleRepository;
use App\Repository\ErrorRepository;
use Illuminate\Http\JsonResponse;

;

class RolesController extends Controller
{

    protected RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index(GetRoleRequest $request)
    {
        $data = $this->roleRepository->search($request->collect());
        return new RoleCollection($data);
    }

    public function destroy(DeleteRoleRequest $request)
    {
        $role = $this->roleRepository->find($request->validated()['id']);
        $response = $this->roleRepository->delete($role);

        if ($response instanceof \Exception)
            return response()->json(['message' => (new ErrorRepository($response))->getErrorMessage()], 500);

        return response()->json(['message' => 'Role deleted'], 200);
    }
}
