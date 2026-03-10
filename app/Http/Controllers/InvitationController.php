<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptBreederRoleRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
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

    public function regenerateBreederInvite(Request $request, User $user): JsonResponse
    {
        $actor = $request->user();
        if (!$actor || (!$actor->isAdmin() && !$actor->isFocalPerson())) {
            abort(403, __('You are not authorized to regenerate breeder invitations.'));
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'User already verified.',
            ], 422);
        }

        $expires = (int) $request->input('expires', 60);
        if ($expires < 5) {
            $expires = 5;
        }
        if ($expires > 1440) {
            $expires = 1440;
        }

        $url = URL::temporarySignedRoute(
            'accept.breeder.role',
            now()->addMinutes($expires),
            ['user' => $user->id]
        );

        $user->sendEmailVerificationViaFocalPersonNotification();

        return response()->json([
            'success' => true,
            'data' => [
                'accept_url' => $url,
                'expires_in_minutes' => $expires,
                'email_sent' => true,
            ],
        ]);
    }
}
