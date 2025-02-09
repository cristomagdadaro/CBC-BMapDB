<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'fname' => $googleUser->user['given_name'],
                    'lname' => $googleUser->user['family_name'],
                    'affiliation' => 1,
                    'profile_photo_path' => $googleUser->user['picture'],
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(uniqid()), // Generate a random password
                    'email_verified_at' => $googleUser->user['email_verified'] ? now() : null,
                ]
            );
            // what happen when user who registered with google has already an account?
            $user->assignRole(rand(2, 5));

            Auth::login($user);
            return redirect('/dashboard'); // Change this to your desired route

        } catch (\Exception $e) {
            return redirect('/login');
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
