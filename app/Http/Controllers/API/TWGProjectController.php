<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateTWGProjectRequest;
use App\Http\Requests\DeleteTWGProjectRequest;
use App\Http\Requests\GetTWGProjectRequest;
use App\Http\Requests\UpdateTWGProjectRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\TWGProjectRepo;
use Illuminate\Support\Collection;

class TWGProjectController extends BaseController
{
    public function __construct(TWGProjectRepo $project)
    {
        $this->service = $project;
    }

    public function index(GetTWGProjectRequest $request)
    {
        return parent::_index($request);
    }

    public function show(GetTWGProjectRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }

    public function store(CreateTWGProjectRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdateTWGProjectRequest $request, int $id)
    {
        return parent::_update($request, $id);
    }

    public function destroy(int $id)
    {
        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteTWGProjectRequest $request)
    {
        return parent::_multiDestroy($request);
    }
}
