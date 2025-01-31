<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateTWGServiceRequest;
use App\Http\Requests\DeleteTWGServiceRequest;
use App\Http\Requests\GetTWGServiceRequest;
use App\Http\Requests\UpdateTWGServiceRequest;
use App\Repository\API\TWGServiceRepo;

class TWGServiceController extends BaseController
{
    public function __construct(TWGServiceRepo $service)
    {
        $this->service = $service;
    }

    public function index(GetTWGServiceRequest $request)
    {
        return parent::_index($request);
    }

    public function show(GetTWGServiceRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }

    public function store(CreateTWGServiceRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdateTWGServiceRequest $request, int $id)
    {
        return parent::_update($request, $id);
    }

    public function destroy(int $id)
    {
        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteTWGServiceRequest $request)
    {
        return parent::_multiDestroy($request);
    }
}
