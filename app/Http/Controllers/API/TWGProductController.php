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
        return $data = $this->service->find($id);
    }

    public function store(CreateTWGProductRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(UpdateTWGProductRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteTWGProductRequest $request)
    {
        return $this->service->multiDestroy($request->validated());
    }
}
