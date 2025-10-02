<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptBreederRoleRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InvitationController extends Controller
{
    public function acceptBreederRole(AcceptBreederRoleRequest $request, User $user)
    {
        // Verify the email
        $user->markEmailAsVerified();

        // Log in the user
        Auth::login($user);

        // Redirect to the intended location
        return Inertia::render('Dashboard', ['acceptedBreederRole' => 'You have successfully accepted the invitation', 'tempPasswordAlert' => 'Please replace your temporary password!']);
    }
}
