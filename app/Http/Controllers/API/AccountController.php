<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\GetAccountForRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountsCollection;
use App\Http\Resources\BaseCollection;
use App\Models\Accounts;
use App\Repository\API\AccountsRepo;
use Faker\Core\Uuid;
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
        $this->service->appendWith(['user', 'application']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show($id)
    {
        $data = $this->service->find($id);
        return $this->sendResponse('Account retrieved successfully.', $data);
    }

    public function store(CreateAccountRequest $request)
    {
        $data = $this->service->create($request->validated());
        return $this->sendResponse('Account created successfully.', $data);
    }

    public function update(UpdateAccountRequest $request, $id)
    {
        $data = $this->service->update($id, $request->validated());
        return $this->sendResponse('Account updated successfully.', $data);
    }

    public function destroy($id)
    {
        $data = $this->service->delete($id);
        return $this->sendResponse('Account deleted successfully.', $data);
    }
}
