<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Repository\API\UserRepo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class GoogleController extends Controller
{
    protected UserRepo $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

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
            $existingUser = $this->userRepo->findByEmail($googleUser->getEmail());

            // Check if the email exists but was NOT registered with Google
            if ($existingUser && is_null($existingUser->google_id)) {
                $existingUser->update(['google_id' => $googleUser->getId()]);
                $existingUser->save();
                //return Redirect::route('login')->with('error', 'This email is already registered. Please log in using your password.');
            }

            // If the user exists and has a Google ID, log them in
            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                // Otherwise, create a new user
                $user = $this->userRepo->createUser([
                    'fname' => $googleUser->user['given_name'],
                    'lname' => $googleUser->user['family_name'],
                    'email' => $googleUser->getEmail(),
                    'affiliation' => 1,
                    'profile_photo_path' => $googleUser->user['picture'],
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(uniqid()), // Random password to prevent direct login via email
                    'email_verified_at' => $googleUser->user['email_verified'] ? now() : null,
                ]);

                $user->assignRole(Role::RESEARCHER->value);

                Auth::login($user);
            }

            return Redirect::route('dashboard')->with('message', 'Successful authenticated thru Google Account');
        } catch (\Exception $e) {
            return view('errors.googleauth')->with('message', $e->getTraceAsString());
        }
    }

}
