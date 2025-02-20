<?php

namespace Modules\TwgDb\Controllers;

use App\Http\Controllers\BaseController;
use Modules\TwgDb\Repositories\TWGProjectRepo;
use Modules\TwgDb\Requests\CreateTWGProjectRequest;
use Modules\TwgDb\Requests\DeleteTWGProjectRequest;
use Modules\TwgDb\Requests\GetTWGProjectRequest;
use Modules\TwgDb\Requests\UpdateTWGProjectRequest;

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
