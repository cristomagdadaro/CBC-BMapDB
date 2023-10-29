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
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    /**
     * User Registration endpoint
     */
    public function register(CreateApiUserRequest $request)
    {
        // login user
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        return $this->sendResponse('User registered successfully.', new UserLoginResource($user));
    }

    /**
     * Login
     */
    public function login(LoginApiUserRequest $request)
    {
        $logged_in = Auth::attempt($request->only('email', 'password'));

        if ($logged_in) {
            $user = Auth::user();

            /*if (!$user->isActive()) {
                auth()->logout();

                return $this->sendError('User Deactivated', Response::HTTP_FORBIDDEN, ['error' => 'User Deactivated']);
            }*/

            // invalidate old tokens from the same IP
            DB::table('personal_access_tokens')
                ->where('tokenable_type', "App\Models\User")
                ->where('tokenable_id', $user->id)
                ->delete();

            return $this->sendResponse('User logged in successfully.', new UserLoginResource($user));
        }

        return $this->sendFail('Unauthorized', Response::HTTP_FORBIDDEN, ['error' => 'Unauthorized']);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return $this->sendResponse('User logged out successfully.');
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
        $user = $request->user()->only(['name', 'email']);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
