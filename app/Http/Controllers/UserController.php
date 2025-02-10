<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUserRequest;
use App\Repository\API\UserRepo;

class UserController extends BaseController
{
    public function __construct(UserRepo $userRepository)
    {
        $this->service = $userRepository;
    }

    public function index(GetUserRequest $request)
    {
        return parent::_index($request);
    }

    public function show(GetUserRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }
}
