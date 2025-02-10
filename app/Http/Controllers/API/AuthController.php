<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateApiUserRequest;
use App\Http\Requests\LoginApiUserRequest;
use App\Http\Resources\UserLoginResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseController
{
    /**
     * User Registration endpoint
     */
    public function register(CreateApiUserRequest $request)
    {
        /*$input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        return $this->sendResponse(new UserLoginResource($user));*/
        return null;
    }

    /**
     * Login
     */
    public function login(LoginApiUserRequest $request)
    {
        $logged_in = Auth::attempt($request->only('email', 'password'));

        if ($logged_in) {
            $user = Auth::user();

            // invalidate old tokens from the same IP
            DB::table('personal_access_tokens')
                ->where('tokenable_type', "App\Models\User")
                ->where('tokenable_id', $user->id)
                ->delete();

            return $this->sendResponse(new UserLoginResource($user));
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        if (auth()->user()) {
            auth()->user()->tokens()->delete();
            return $this->sendResponse(['message' => 'User logged out successfully.']);
        }
        return $this->sendResponse(['message' => 'User currently not logged in']);
    }

    /**
     * Request forget password reset link
     */
    public function forgetPassword(Request $request)
    {
        // TODO
    }

    /**
     * Retrieves user information
     */
    public function user(Request $request)
    {
        return $this->sendResponse($request->user()->load(['accounts:id,user_id,app_id','roles:id,name']));
    }
}
