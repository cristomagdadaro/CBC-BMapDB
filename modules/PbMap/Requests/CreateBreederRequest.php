<?php

namespace Modules\PbMap\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Rules\Password;
use Modules\PbMap\Enums\BreederType;

class CreateBreederRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules($id = null): array
    {
        return [
            //'user_id' => 'required|exists:users,id',
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'mobile_no' =>  ['nullable', 'string', 'max:255', 'unique:breeders,mobile_no', 'regex:/^09\d{9}$/'],
            'email' => [
                'required',
                'email',
                'unique:breeders,email,'. $id ?? $this->id,
                'unique:users,email,'. $id ?? $this->id,
            ],
            'photo' => ['nullable', 'string'],
            'breeder_type' => ['required', 'string', "in:".BreederType::PRIVATE->value.",".BreederType::PUBLIC->value.","],
            'affiliation' => ['required', 'exists:institutes,id'],
            'position' => ['required', 'string'],
            'expertise' => ['nullable', 'string'],
            'research_interest' => ['nullable', 'string'],
            'educ_level' => ['nullable', 'string'],
            'geolocation' => 'required|exists:loc_cities,id',
        ];
    }

    public function messages()
    {
        return [
            'fname.required' => 'First name is required',
            'lname.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email already exists',
            'mobile_no.regex' => 'Invalid format. Format is 09XXXXXXXXX',
            'mobile_no.unique' => 'Mobile number already exists',
            'photo.string' => 'Photo must be a string',
            'breeder_type.required' => 'Breeder type is required',
            'breeder_type.in' => 'Breeder type must be either private or public',
            'affiliation.exists' => 'Affiliation must exist in the institutes table',
            'geolocation.exists' => 'Unknown Geolocation'
        ];
    }
}
