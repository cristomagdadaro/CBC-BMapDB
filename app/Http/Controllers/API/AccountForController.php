<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetAccountForRequest;
use App\Http\Resources\AccountForCollection;
use App\Models\AccountFor;
use App\Repository\API\AccountForRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class AccountForController extends BaseController
{
    protected AccountForRepository $accountForRepository;

    public function __construct(AccountForRepository $accountForRepository)
    {
        $this->accountForRepository = $accountForRepository;
    }

    public function index(GetAccountForRequest $request, $user_id)
    {
        $request->merge(['user_id' => $user_id]);

        $data = $this->accountForRepository->search($request->collect());
        return new AccountForCollection($data);
    }

    public function destroy(Request $request)
    {
        $accountFor = $this->accountForRepository->find($request->id);
        $accountFor->delete();
        return $accountFor;
    }
}
