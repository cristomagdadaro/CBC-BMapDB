<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\DeleteRoleRequest;
use App\Http\Requests\GetRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\RoleCollection;
use App\Models\Role;
use App\Repository\API\RoleRepo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RoleController extends BaseController
{
    public function __construct(RoleRepo $repository)
    {
        $this->service = $repository;
    }

    public function index(GetRoleRequest $request): BaseCollection
    {
        $this->service->appendWith(['permissions']);
        $data = $this->service->search(new Collection($request->validated()));
        // remove the Admin role from the list
        foreach ($data->items() as $key => $value) {
            if ($value->name == \App\Enums\Role::ADMIN->value) {
                unset($data[$key]);
            }
        }
        return new BaseCollection($data);
    }

    public function show($id)
    {
        return  $this->service->find($id);
    }

    public function store(CreateRoleRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
