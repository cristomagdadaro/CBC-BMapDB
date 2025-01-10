<?php

namespace App\Actions\Fortify;

use App\Models\Accounts;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Faker\Core\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'mobile_no' =>  ['nullable', 'string', 'unique:users', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'affiliation' => ['required', 'exists:institutes,id'],
            'account_for' => ['required', 'numeric', 'exists:applications,id'],
            'role' => ['required', 'exists:roles,id'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'fname' => $input['fname'],
            'mname' => $input['mname'],
            'lname' => $input['lname'],
            'suffix' => $input['suffix'],
            'mobile_no' => $input['mobile_no'],
            'affiliation' => $input['affiliation'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $user->assignRole(Role::findOrFail($input['role'])->name);

        Accounts::create([
            'id' => (new Uuid())->uuid3(),
            'user_id' => $user->id,
            'app_id' => $input['account_for'],
        ]);

        $this->createTeam($user);

        return $user;
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->lname, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
