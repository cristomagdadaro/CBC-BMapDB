<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\DeleteRoleRequest;
use App\Http\Requests\GetRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleCollection;
use App\Repository\API\RoleRepository;

class RoleController extends BaseController
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

    public function show($id)
    {
        $role = $this->roleRepository->find($id);
        return response()->json($role, 200);
    }

    public function store(CreateRoleRequest $request)
    {
        return $this->roleRepository->create((array)$request->validated());
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        $data = $this->roleRepository->find($id);
        return $this->roleRepository->update($data, $request->validated());
    }

    public function destroy($id)
    {
        $role = $this->roleRepository->find($id);
        $response = $this->roleRepository->delete($role);

        if ($response instanceof \Exception) {
            return $this->sendError($response);
        }
        return $this->sendReponse('Role deleted successfully.', $role);
    }
}
