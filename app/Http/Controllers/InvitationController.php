<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return redirect()->route('dashboard')->with('acceptedBreederRole', 'Welcome! You have accepted the Breeder role.');
    }
}
