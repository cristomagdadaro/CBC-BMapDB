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
    protected AccountsRepository $accountRepository;

    public function __construct(AccountsRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function index(GetAccountForRequest $request)
    {
        $data = $this->accountRepository->search($request->collect());
        return new AccountsCollection($data);
    }

    public function show($id)
    {
        return $this->accountRepository->find($id);
    }

    public function store(CreateAccountRequest $request, $user_id)
    {
        $request->validated()->merge(['user_id' => $user_id]);

        $accountFor = $this->accountRepository->create($request->validated());
        return $this->accountRepository->save($accountFor);
    }

    public function update(UpdateAccountRequest $request, $id)
    {
        $accountFor = $this->accountRepository->find($id);
        return $this->accountRepository->update($accountFor, $request->validated());
    }

    public function destroy($id)
    {
        $accountFor = $this->accountRepository->find($id);
        return $this->accountRepository->delete($accountFor);
    }
}
