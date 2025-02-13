<?php

namespace App\Actions\Fortify;

use App\Enums\DefaultPassword;
use App\Http\Requests\CreateBreederRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'mobile_no' =>  ['required', 'string', 'max:255'],
            'affiliation' => ['required', 'exists:institutes,id'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // when user changes affiliation, add them as a breeder to the new agency. Don't remove them from the previous agency since it will make the data inconsistent.
        if ($user->isBreeder()) {
            // get the breeder model
            $model = $user->breeder()->getModel();
            // check if the user is already a breeder in the institution
            $temp = $model->where('user_id', $user->id)->where('affiliation', $input['affiliation']);

            if (!$temp->count()) {
                Validator::make($input, (new CreateBreederRequest())->rules())->validateWithBag('updateProfileInformation');
                $user->makeBreeder($input->all(), $user->breeder()->first());
            }
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'fname' => $input['fname'],
                'mname' => $input['mname'],
                'lname' => $input['lname'],
                'suffix' => $input['suffix'],
                'mobile_no' => $input['mobile_no'],
                'email' => $input['email'],
                'affiliation' => $input['affiliation'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'fname' => $input['fname'],
            'mname' => $input['mname'],
            'lname' => $input['lname'],
            'suffix' => $input['suffix'],
            'mobile_no' => $input['mobile_no'],
            'affiliation' => $input['affiliation'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
