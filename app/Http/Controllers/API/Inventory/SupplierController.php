<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\CreateSupplierRequest;
use App\Http\Requests\Inventory\GetSupplierRequest;
use App\Http\Requests\Inventory\UpdateSupplierRequest;
use App\Repository\API\Inventory\SupplierRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class SupplierController extends BaseController
{
    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->repository = $supplierRepository;
    }

    public function index(GetSupplierRequest $request)
    {
        $data = $this->repository->search(new Collection($request->validated()));
        return new ResourceCollection($data);
    }

    public function store(CreateSupplierRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function update(UpdateSupplierRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
