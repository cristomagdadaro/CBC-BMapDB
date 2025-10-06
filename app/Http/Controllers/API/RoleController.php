<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\GetRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\RoleRepo;
use Illuminate\Http\JsonResponse;
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

    /**
     * Get roles formatted for selection options (public use)
     */
    public function options(GetRoleRequest $request): JsonResponse
    {
        $data = $this->service->search(new Collection($request->validated()));

        // Transform the data to option format
        $data->getCollection()->transform(function ($role) {
            return [
                'value' => $role->id,
                'label' => $role->name,
            ];
        });

        return response()->json($data);
    }
}
