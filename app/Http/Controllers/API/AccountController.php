<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetAccountForRequest;
use App\Http\Resources\AccountsCollection;
use App\Models\Accounts;
use App\Repository\API\AccountsRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class AccountController extends BaseController
{
    protected AccountsRepository $accountForRepository;

    public function __construct(AccountsRepository $accountForRepository)
    {
        $this->accountForRepository = $accountForRepository;
    }

    public function index(GetAccountForRequest $request, $user_id)
    {
        $request->merge(['user_id' => $user_id]);

        $data = $this->accountForRepository->search($request->collect());
        return new AccountsCollection($data);
    }

    public function destroy(Request $request)
    {
        $accountFor = $this->accountForRepository->find($request->id);
        $accountFor->delete();
        return $accountFor;
    }
}
