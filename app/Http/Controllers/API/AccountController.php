<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\GetAccountForRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountsCollection;
use App\Models\Accounts;
use App\Repository\API\AccountsRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class AccountController extends BaseController
{

    public function __construct(AccountsRepository $accountRepository)
    {
        $this->repository = $accountRepository;
    }

    public function index(GetAccountForRequest $request)
    {
        $data = $this->repository->search($request->collect());
        return new AccountsCollection($data);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function store(CreateAccountRequest $request)
    {
        return $this->repository->create($request->validated());
    }

    public function update(UpdateAccountRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
