<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetAccountForRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\AccountForRepo;
use Illuminate\Support\Facades\Request;

class AccountForController extends BaseController
{
    public function __construct(AccountForRepo $accountForRepository)
    {
        $this->service = $accountForRepository;
    }

    public function index(GetAccountForRequest $request, $user_id)
    {
        $request->merge(['user_id' => $user_id]);

        $data = $this->service->search($request->collect());
        return new BaseCollection($data);
    }

    public function destroy(Request $request)
    {
        $accountFor = $this->service->find($request->id);
        $accountFor->delete();
        return $accountFor;
    }
}
