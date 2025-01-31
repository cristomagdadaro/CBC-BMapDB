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
        return parent::_index($request);
    }

    public function show(GetRoleRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }

    public function store(CreateRoleRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdateRoleRequest $request, int $id)
    {
        return parent::_update($request, $id);
    }

    public function destroy(int $id)
    {
        return parent::_destroy($id);
    }
}
