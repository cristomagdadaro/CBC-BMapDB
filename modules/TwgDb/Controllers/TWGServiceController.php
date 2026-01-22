<?php

namespace Modules\TwgDb\Controllers;

use App\Http\Controllers\BaseController;
use Modules\TwgDb\Repositories\TWGServiceRepo;
use Modules\TwgDb\Models\TWGService;
use Modules\TwgDb\Requests\CreateTWGServiceRequest;
use Modules\TwgDb\Requests\DeleteTWGServiceRequest;
use Modules\TwgDb\Requests\GetTWGServiceRequest;
use Modules\TwgDb\Requests\UpdateTWGServiceRequest;

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
        $user = auth()->user();
        $model = TWGService::findOrFail($id);

        if (!$user || (!$user->isAdmin() && (!$user->isTwgManager() || (int) $user->affiliation !== (int) $model->institution))) {
            abort(403, __('You are not authorized to delete this service.'));
        }

        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteTWGServiceRequest $request)
    {
        return parent::_multiDestroy($request);
    }
}
