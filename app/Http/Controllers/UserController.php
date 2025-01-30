<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUserRequest;
use App\Http\Resources\BaseCollection;
use App\Models\User;
use App\Repository\API\UserRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends BaseController
{
    public function __construct(UserRepo $userRepository)
    {
        $this->service = $userRepository;
    }

    public function index(GetUserRequest $request)
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show(int $id): JsonResponse
    {
        $data = $this->service->find($id);
        return $this->sendResponse('User retrieved successfully.', $data);
    }

}
