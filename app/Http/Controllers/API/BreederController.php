<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBreederRequest;
use App\Http\Requests\DeleteBreederRequest;
use App\Http\Requests\GetBreederRequest;
use App\Http\Requests\UpdateBreederRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BreederCollection;
use App\Http\Resources\BreederResource;
use App\Models\Breeder;
use App\Repository\API\BreederRepositoryAbstract;
use Illuminate\Support\Collection;

class BreederController extends BaseController
{

    public function __construct(BreederRepositoryAbstract $breederRepository)
    {
        $this->repository = $breederRepository;
    }

    public function index(GetBreederRequest $request)
    {
        $data = $this->repository->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function store(CreateBreederRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function show($id)
    {
        return $this->repository->find($id)->load('commodities');
    }

    public function update(UpdateBreederRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

    public function multiDestroy(DeleteBreederRequest $request)
    {
        return $this->repository->multiDestroy($request->validated());
    }
}
