<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\BaseController;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\GetUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Accounts;
use App\Models\User;
use App\Repository\API\UserRepo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;

class AdminController extends BaseController
{
    use PasswordValidationRules;

    public function __construct(UserRepo $adminRepository)
    {
        $this->service = $adminRepository;
    }

    public function index(GetUserRequest $request)
    {
        return parent::_index($request);
    }

    public function store(FormRequest $request)
    {
        Validator::make($request->all(), [
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'mobile_no' =>  ['nullable', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'affiliation' => ['required', 'exists:institutes,id'],
            'account_for' => ['required', 'numeric', 'exists:applications,id'],
            'password' => ['required', 'string','confirmed'],
            'password_confirmation' => ['required', 'string'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? !auth()->user()->isAdmin() ? ['accepted', 'required'] : '' : '',
        ])->validate();

        if ($request['password'])
            $request['password'] = Hash::make($request['password']);

        $data = $this->service->create($request->all());

        if (isset($data->original['data']) && $data->original['data'] instanceof User) {
            Accounts::create([
                'user_id' => $data->original['data']['id'],
                'app_id' => $request['account_for'],
            ]);

            if (!$data->original['data']->hasVerifiedEmail()) {
                $data->original['data']->sendEmailVerificationNotification();
            }
        } else {
            if (isset($data->original['data'])) {
                $this->service->delete($data->original['data']['id']);
            }
        }

        return $data;
    }

    public function show(GetUserRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        return parent::_update($request, $id);
    }

    public function destroy(int $id)
    {
        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteUserRequest $request)
    {
        return parent::_multiDestroy($request);
    }
}
