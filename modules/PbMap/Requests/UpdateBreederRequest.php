<?php

namespace Modules\PbMap\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Rules\Password;
use Modules\PbMap\Enums\BreederType;

class UpdateBreederRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if (!auth()->user()->isAdmin())
            $this->merge([
                'institution' => auth()->user()->affiliation,
            ]);

        $this->merge([
            'user_id'  => $this->user_id ?? auth()->user()->id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'mobile_no' =>  ['nullable', 'string', 'max:255', 'unique:breeders,mobile_no,'.$this->id],
            'affiliation' => ['required', 'exists:institutes,id'],
            'photo' => ['nullable', 'string'],
            'breeder_type' => ['required', 'string', "in:".BreederType::PRIVATE->value.",".BreederType::PUBLIC->value.","],
            'email' => [
                'required',
                'email',
                'unique:breeders,email,'.$this->id,
                'unique:breeders,email,'.$this->id,
            ],
            'geolocation' => 'nullable|exists:loc_cities,id',
            //'password' => $this->passwordRules(),
        ];
    }

    protected function passwordRules(): array
    {
        return ['nullable', 'string', new Password, 'confirmed'];
    }
}
