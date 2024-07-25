<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateTWGProductRequest;
use App\Http\Requests\DeleteTWGProductRequest;
use App\Http\Requests\GetTWGProductRequest;
use App\Http\Requests\UpdateTWGProductRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\TWGProductRepo;
use Illuminate\Support\Collection;

class TWGProductController extends BaseController
{
    public function __construct(TWGProductRepo $productRepository)
    {
        $this->service = $productRepository;
    }

    public function index(GetTWGProductRequest $request)
    {
        $this->service->appendWith(['expert']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show($id)
    {
        $data = $this->service->find($id);
        return $this->sendResponse('TWG Product retrieved successfully.', $data);
    }

    public function store(CreateTWGProductRequest $request)
    {
        $data = $this->service->create($request->validated());
        return $this->sendResponse('TWG Product created successfully.', $data);
    }

    public function update(UpdateTWGProductRequest $request, $id)
    {
        $data = $this->service->update($id, $request->validated());
        return $this->sendResponse('TWG Product updated successfully.', $data);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->sendResponse('TWG Product deleted successfully.');
    }

    public function multiDestroy(DeleteTWGProductRequest $request)
    {
        $this->service->multiDestroy($request->validated());
        return $this->sendResponse('TWG Products deleted successfully.');
    }
}
