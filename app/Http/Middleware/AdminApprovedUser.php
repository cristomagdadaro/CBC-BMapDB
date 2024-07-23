<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Accounts; // Adjust this import according to your model's namespace

class AdminApprovedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Inertia\Response | Response
     */
    public function handle(Request $request, Closure $next): \Inertia\Response | Response
    {
        if ($request->user() && $request->user()->getRole() == Role::ADMIN) {
            return $next($request);
        }

        // Check if the user is authenticated
        if ($request->user()) {
            // Assuming user has an account relation
            $user = $request->user();

            // Check if the user's account is approved
            if ($user->accounts) {
                // Check if the user's account has an approved_at timestamp
                $temp = 0;
                foreach ($user->accounts as $account) {
                    if ($account->approved_at) {
                        $temp++;
                    }
                }

                if ($temp == 0) {
                    return Inertia::render('Auth/WaitForAdminApproval', ['message' => 'Your Account has not yet been approved by the administrator, please wait patiently. As of the moment you can only manage your account details.']);
                } elseif ($temp >= 1 && $temp <= count($user->accounts)){
                    return $next($request);
                }
            }

            // If the user is not approved, return a 403 response
            return Inertia::render('Auth/WaitForAdminApproval', ['message' => $user->accounts]);
        }

        return Inertia::render('Auth/Login', ['message' => 'You are not authenticated.']);
    }
}
