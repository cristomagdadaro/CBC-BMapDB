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
use App\Repository\API\TWGExpertRepo;
use App\Repository\API\TWGProjectRepo;
use Illuminate\Support\Collection;

class TWGExpertController extends BaseController
{
    public function __construct(TWGExpertRepo $expertRepository)
    {
        $this->service = $expertRepository;
    }

    public function index(GetTWGExpertRequest $request)
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new TWGExpertCollection($data);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function store(CreateTWGExpertRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(UpdateTWGExpertRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteTWGExpertRequest $request)
    {
        return $this->service->multiDestroy($request->validated());
    }
}
