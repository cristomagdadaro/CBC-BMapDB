<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\GetUnverifiedUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\BaseCollection;
use App\Models\Accounts;
use App\Repository\API\UserRepo;
use Illuminate\Support\Collection;

class AdminController extends BaseController
{
    public function __construct(UserRepo $adminRepository)
    {
        $this->service = $adminRepository;
    }

    public function index(GetUnverifiedUserRequest $request)
    {
        $this->service->appendWith(['accounts']);

        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show($id)
    {
        $this->service->appendWith(['accounts']);

        return $this->service->find($id);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteUserRequest $request)
    {
        return $this->service->multiDestroy($request->validated());
    }
}
