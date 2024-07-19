<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\GetUserRequest;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Repository\API\UserRepo;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    protected UserRepo $userRepository;

    public function __construct(UserRepo $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetUserRequest $request)
    {
        $data = $this->userRepository->search($request->collect());
        return new UserCollection($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
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
