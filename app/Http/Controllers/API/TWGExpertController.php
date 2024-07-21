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

    public function show($id)
    {
        $data = $this->service->find($id);
        return $this->sendResponse('TWG Expert retrieved successfully.', $data);
    }

    public function store(CreateTWGExpertRequest $request)
    {
        $data =  $this->service->create($request->validated());
        return $this->sendResponse('TWG Expert created successfully.', $data);
    }

    public function update(UpdateTWGExpertRequest $request, $id)
    {
        $data =  $this->service->update($id, $request->validated());
        return $this->sendResponse('TWG Expert updated successfully.', $data);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->sendResponse('TWG Expert deleted successfully.');
    }

    public function multiDestroy(DeleteTWGExpertRequest $request)
    {
        $this->service->multiDestroy($request->validated());
        return $this->sendResponse('TWG Expert deleted successfully.');
    }
}
