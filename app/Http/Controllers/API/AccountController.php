<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\GetAccountForRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Repository\API\AccountsRepo;
use Illuminate\Validation\ValidationException;

class AccountController extends BaseController
{
    public function __construct(AccountsRepo $accountRepository)
    {
        $this->service = $accountRepository;
    }

    public function index(GetAccountForRequest $request)
    {
        return parent::_index($request);
    }

    public function show(GetAccountForRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }

    public function store(CreateAccountRequest $request)
    {
        /** @var \App\Repository\API\AccountsRepo $accountsRepo */
        $accountsRepo = $this->service;
        $accountsRepo->assignRoleForUser(\Illuminate\Support\Facades\Auth::user(), $request->validated()['role'] ?? null);

        return parent::_store($request);
    }

    /**
     * @throws ValidationException
     */
    public function update(UpdateAccountRequest $request, $id)
    {
        $validatedData = $request->validated();
        
        /** @var \App\Repository\API\AccountsRepo $accountsRepo */
        $accountsRepo = $this->service;
        
        $data = $accountsRepo->update($id, $validatedData);
        $accountsRepo->updateUserPermissionsAndRoles($validatedData['user_id'], $validatedData);

        return $data;
    }

    public function destroy($id)
    {
        return parent::_destroy($id);
    }
}
