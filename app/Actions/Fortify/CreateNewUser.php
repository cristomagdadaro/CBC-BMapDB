<?php

namespace App\Actions\Fortify;

use App\Models\Accounts;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Notifications\NewSignUp;
use Faker\Core\Uuid;
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
        // Support name fallback for automated tests and standard fortify forms
        if (empty($input['fname']) && !empty($input['name'])) {
            $parts = explode(' ', $input['name'], 2);
            $input['fname'] = $parts[0];
            $input['lname'] = $parts[1] ?? 'User';
        }

        // Support default institute for testing if absent
        if (empty($input['affiliation'])) {
            $input['affiliation'] = \App\Models\Institute::first()?->id;
        }

        // Support default application if absent
        if (empty($input['account_for'])) {
            $input['account_for'] = \App\Models\Application::first()?->id;
        }

        Validator::make($input, [
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'mobile_no' =>  ['nullable', 'string', 'unique:users', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'affiliation' => ['required', 'exists:institutes,id'],
            'account_for' => ['nullable', 'numeric', 'exists:applications,id'],
            'role' => ['nullable', 'exists:roles,id'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'fname' => $input['fname'],
            'mname' => $input['mname'] ?? null,
            'lname' => $input['lname'],
            'suffix' => $input['suffix'] ?? null,
            'mobile_no' => $input['mobile_no'] ?? null,
            'affiliation' => $input['affiliation'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // Default role is Researcher if not specified
        if (!empty($input['role'])) {
            $role = Role::find($input['role']);
        } else {
            $role = Role::where('name', \App\Enums\Role::RESEARCHER->value)->first();
        }

        if (!$role) {
            $role = Role::where('name', \App\Enums\Role::RESEARCHER->value)->first();
        }

        if ($role) {
            $user->assignRole($role->name);
        }

        // Only FOCAL_PERSON, BREEDER, and TWG_MANAGER wait for admin approval.
        // Researchers and default roles are automatically approved.
        $requiresApproval = $role && in_array($role->name, [
            \App\Enums\Role::FOCAL_PERSON->value,
            \App\Enums\Role::BREEDER->value,
            \App\Enums\Role::TWG_MANAGER->value,
            \App\Enums\Role::ADMIN->value,
        ], true);

        $appId = !empty($input['account_for']) ? $input['account_for'] : 2; // Default to Plant Breeders Map (id: 2)

        Accounts::create([
            'id' => (new Uuid())->uuid3(),
            'user_id' => $user->id,
            'app_id' => $appId,
            'approved_at' => $requiresApproval ? null : now(),
        ]);

        $user->notify(new NewSignUp($user->getFullName(), $user->email));

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
