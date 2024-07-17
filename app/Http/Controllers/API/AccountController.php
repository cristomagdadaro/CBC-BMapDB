<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\GetAccountForRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountsCollection;
use App\Models\Accounts;
use App\Repository\API\AccountsRepo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class AccountController extends BaseController
{

    public function __construct(AccountsRepo $accountRepository)
    {
        $this->service = $accountRepository;
    }

    public function index(GetAccountForRequest $request)
    {
        $data = $this->service->search($request->collect());
        return new AccountsCollection($data);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function store(CreateAccountRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(UpdateAccountRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
