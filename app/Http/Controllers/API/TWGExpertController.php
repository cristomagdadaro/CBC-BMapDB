<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateTWGExpertRequest;
use App\Http\Requests\DeleteTWGExpertRequest;
use App\Http\Requests\GetTWGExpertRequest;
use App\Http\Requests\UpdateTWGExpertRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\TWGExpertRepo;
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
        return new BaseCollection($data);
    }

    public function show(GetTWGExpertRequest $request, int $id)
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function store(CreateTWGExpertRequest $request)
    {
        $data = $this->insertUserId($request->validated());
        return $this->service->create($data);
    }

    public function update(UpdateTWGExpertRequest $request, int $id)
    {
        $data = $this->insertUserId($request->validated());
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');
        return $this->service->update($id, $data);
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
