<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class GoogleController extends Controller
{
    public function redirectToGoogle(): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect'))
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        if (!$request->has('code')) {
            return Redirect::route('login')->with('error', 'Authorization code parameter missing');
        }

        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            // Check if the email exists but was NOT registered with Google
            if ($existingUser && is_null($existingUser->google_id)) {
                return redirect('/login')->with('error', 'This email is already registered. Please log in using your password.');
            }

            // If the user exists and has a Google ID, log them in
            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                // Otherwise, create a new user
                $user = User::create([
                    'fname' => $googleUser->user['given_name'],
                    'lname' => $googleUser->user['family_name'],
                    'email' => $googleUser->getEmail(),
                    'affiliation' => 1,
                    'profile_photo_path' => $googleUser->user['picture'],
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(uniqid()), // Random password to prevent direct login via email
                    'email_verified_at' => $googleUser->user['email_verified'] ? now() : null,
                ]);

                // Assign a random role (you may want to refine this logic)
                $user->assignRole(rand(2, 5));

                Auth::login($user);
            }

            return Redirect::route('dashboard')->with('message', 'Successful authenticated thru Google Account');
        } catch (\Exception $e) {
            return Redirect::route('login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }

}

/*{
    "id": "105442417288189291410",
  "nickname": null,
  "name": "Cristo Rey Magdadaro",
  "email": "cristoreymagdadaro20@gmail.com",
  "avatar": "https://lh3.googleusercontent.com/a/ACg8ocLfzEEcbJ4jeW8OYMmn-2pPitCiJvg-zkHVygHhISEMK-LB09FX=s96-c",
  "user": {
    "sub": "105442417288189291410",
    "name": "Cristo Rey Magdadaro",
    "given_name": "Cristo Rey",
    "family_name": "Magdadaro",
    "picture": "https://lh3.googleusercontent.com/a/ACg8ocLfzEEcbJ4jeW8OYMmn-2pPitCiJvg-zkHVygHhISEMK-LB09FX=s96-c",
    "email": "cristoreymagdadaro20@gmail.com",
    "email_verified": true,
    "id": "105442417288189291410",
    "verified_email": true,
    "link": null
  },
  "attributes": {
    "id": "105442417288189291410",
    "nickname": null,
    "name": "Cristo Rey Magdadaro",
    "email": "cristoreymagdadaro20@gmail.com",
    "avatar": "https://lh3.googleusercontent.com/a/ACg8ocLfzEEcbJ4jeW8OYMmn-2pPitCiJvg-zkHVygHhISEMK-LB09FX=s96-c",
    "avatar_original": "https://lh3.googleusercontent.com/a/ACg8ocLfzEEcbJ4jeW8OYMmn-2pPitCiJvg-zkHVygHhISEMK-LB09FX=s96-c"
  },
  "token": "ya29.a0AXeO80TBFsYP2PeFStzniR3qlA5hZGadewkWhvnxWecOqHfXXH8qdY2z8_oXuK_p0n0NqnNyNKetoyarVinEpJUGcEk1ernRhWeILt0x3jH79q_VqeTbIxtsbjfjJBAlVeWZmLUwrRwVexiHGiiWN846pg7ztFzFMig9xripaCgYKAckSARMSFQHGX2MiLqXpoVpyJ5H8BLZAvedaTw0175",
  "refreshToken": null,
  "expiresIn": 3598,
  "approvedScopes": [
    "openid",
    "https://www.googleapis.com/auth/userinfo.email",
    "https://www.googleapis.com/auth/userinfo.profile"
]
}*/
