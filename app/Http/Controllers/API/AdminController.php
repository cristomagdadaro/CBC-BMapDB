<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\GetUnverifiedUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\BaseCollection;
use App\Models\Accounts;
use App\Models\User;
use App\Repository\API\UserRepo;
use Faker\Core\Uuid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
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

    public function index(GetUnverifiedUserRequest $request)
    {
        $this->service->appendWith(['roles','affiliated','accountFor']);
        $this->service->appendCount(['accounts']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
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

    public function show($id)
    {
        $this->service->appendWith(['accounts']);

        return $this->service->find($id);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteUserRequest $request)
    {
        return $this->service->multiDestroy($request->validated());
    }
}
