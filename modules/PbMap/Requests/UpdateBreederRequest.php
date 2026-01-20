<?php

namespace Modules\PbMap\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Rules\Password;
use Modules\PbMap\Enums\BreederType;
use Modules\PbMap\Models\Breeder;

class UpdateBreederRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true; // Allow admins
        }

        $model = Breeder::find($this->id);
        if (!$model) {
            return false;
        }

        if ($model->user_id === $user->id) {
            return true; // Allow owner
        }

        if ($user->isFocalPerson()) {
            $userAff = (int) ($user->affiliation ?? 0);
            $breederAff = (int) ($model->affiliation ?? 0);
            if ($userAff && $breederAff && $userAff === $breederAff) {
                return true;
            }
        }

        abort(403, __('You are not authorized to update this breeder.'));
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
            'geolocation' => 'required|exists:loc_cities,id',
            'position' => ['required', 'string'],
            'expertise' => ['nullable', 'string'],
            'research_interest' => ['nullable', 'string'],
            'educ_level' => ['nullable', 'string'],
        ];
    }

    protected function passwordRules(): array
    {
        return ['nullable', 'string', new Password, 'confirmed'];
    }
}
