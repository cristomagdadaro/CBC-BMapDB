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
            'mobile_no' =>  ['nullable', 'string', 'max:255', 'unique:breeders,mobile_no'],
            'email' => [
                'required',
                'email',
                'unique:breeders,email,'. $id ?? $this->id,
                'unique:users,email,'. $id ?? $this->id,
            ],
            'photo' => ['nullable', 'string'],
            'breeder_type' => ['required', 'string', "in:".BreederType::PRIVATE->value.",".BreederType::PUBLIC->value.","],
            'affiliation' => ['required', 'exists:institutes,id'],
            'geolocation' => 'nullable|exists:loc_cities,id',
        ];
    }
}
