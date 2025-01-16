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

    public function show(GetTWGProductRequest $request, int $id)
    {
        $this->service->appendWith(['expert']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function store(CreateTWGProductRequest $request)
    {
        $data = $this->insertUserId($request->validated());
        return $this->service->create($data);
    }

    public function update(UpdateTWGProductRequest $request, $id)
    {
        $data = $this->insertUserId($request->validated());
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');
        return $this->service->update($id, $data);
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
