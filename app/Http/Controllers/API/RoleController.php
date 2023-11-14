<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\DeleteRoleRequest;
use App\Http\Requests\GetRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleCollection;
use App\Models\Role;
use App\Repository\API\RoleRepositoryAbstract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RoleController extends BaseController
{
    public function __construct(RoleRepositoryAbstract $repository)
    {
        $this->repository = $repository;
    }

    public function index(GetRoleRequest $request): RoleCollection
    {
        $data = $this->repository->search($request->collect());
        return new RoleCollection($data);
    }

    public function show($id)
    {
        return  $this->repository->find($id);
    }

    public function store(CreateRoleRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
