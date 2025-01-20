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
use Illuminate\Support\Facades\Password;
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

        return $this->sendResponse(new UserLoginResource($user));
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
        auth()->user()->tokens()->delete();

        return $this->sendResponse('User logged out successfully.');
    }

    /**
     * Request forget password reset link
     */
    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->sendResponse(['message' => __($status)])
            : $this->sendError(['email' => __($status)], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Retrieves user information
     */
    public function user(Request $request)
    {
        $user = $request->user()->only(['name', 'email']);

        return $this->sendResponse($user);
    }
}
