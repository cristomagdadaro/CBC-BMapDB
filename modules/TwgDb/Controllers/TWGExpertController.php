<?php

namespace Modules\TwgDb\Controllers;

use App\Http\Controllers\BaseController;
use Modules\TwgDb\Models\TWGExpert;
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
        $user = auth()->user();
        $model = TWGExpert::findOrFail($id);

        if (!$user || (!$user->isAdmin() && (!$user->isTwgManager() || (int) $user->affiliation !== (int) $model->institution))) {
            abort(403, __('You are not authorized to delete this expert.'));
        }

        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteTWGExpertRequest $request)
    {
        return parent::_multiDestroy($request);
    }
}
