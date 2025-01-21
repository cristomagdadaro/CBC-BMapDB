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
        return parent::_index($request);
    }

    public function show(GetTWGExpertRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }

    public function store(CreateTWGExpertRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdateTWGExpertRequest $request, int $id)
    {
        return parent::_update($request, $id);
    }

    public function destroy($id)
    {
        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteTWGExpertRequest $request)
    {
        return parent::_multiDestroy($request);
    }
}
