<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\GetUserRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\UserCollection;
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

    /**
     * Display a listing of the resource.
     */
    public function index(GetUserRequest $request)
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $data = $this->service->find($id);
        return $this->sendResponse('User retrieved successfully.', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @method destroy
     */
    public function destroy($id)
    {
        return 0;
    }
}
