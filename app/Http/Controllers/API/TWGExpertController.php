<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTWGExpertRequest;
use App\Http\Requests\DeleteTWGExpertRequest;
use App\Http\Requests\GetTWGExpertRequest;
use App\Http\Requests\UpdateTWGExpertRequest;
use App\Http\Resources\TWGExpertCollection;
use App\Models\TWGExpert;
use App\Repository\API\TWGExpertRepositoryAbstract;
use App\Repository\API\TWGProjectRepositoryAbstract;
use Illuminate\Support\Collection;

class TWGExpertController extends BaseController
{
    public function __construct(TWGExpertRepositoryAbstract $expertRepository)
    {
        $this->repository = $expertRepository;
    }

    public function index(GetTWGExpertRequest $request)
    {
        $data = $this->repository->search(new Collection($request->validated()));
        return new TWGExpertCollection($data);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function store(CreateTWGExpertRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function update(UpdateTWGExpertRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

    public function multiDestroy(DeleteTWGExpertRequest $request)
    {
        return $this->repository->multiDestroy($request->validated());
    }
}
