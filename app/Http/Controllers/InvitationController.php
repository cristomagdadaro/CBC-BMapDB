<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class InvitationController extends Controller
{
    public function acceptBreederRole(Request $request, User $user)
    {
        // Verify that the signed URL matches the user
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired link.');
        }

        // Verify the email
        $user->update(['email_verified_at' => now()]);

        // Log in the user
        Auth::login($user);

        // Redirect to the intended location
        return Inertia::render('Dashboard', ['acceptedBreederRole' => 'You have successfully accepted the invitation', 'tempPasswordAlert' => 'Please replace your temporary password!']);
    }
}
