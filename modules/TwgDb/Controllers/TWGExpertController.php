<?php

namespace Modules\TwgDb\Controllers;

use App\Http\Controllers\BaseController;
use Modules\TwgDb\Repositories\TWGExpertRepo;
use Modules\TwgDb\Requests\CreateTWGExpertRequest;
use Modules\TwgDb\Requests\DeleteTWGExpertRequest;
use Modules\TwgDb\Requests\GetTWGExpertRequest;
use Modules\TwgDb\Requests\UpdateTWGExpertRequest;

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
