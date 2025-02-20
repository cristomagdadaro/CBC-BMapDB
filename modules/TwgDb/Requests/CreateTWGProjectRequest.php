<?php

namespace Modules\TwgDb\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTWGProjectRequest extends FormRequest
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
        $this->merge([
            'institution' => auth()->user()->affiliation,
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
            'institution' => ['required', 'exists:institutes,id'],
            'title' => ['required', 'string', 'max:255'],
            'objective' => ['required', 'string'],
            'expected_output' => ['required', 'string'],
            'project_leader' => ['required', 'exists:twg_expert,id'],
            'funding_agency' => ['required', 'string'],
            'duration' => ['required', 'string'],
            'status' => ['required', 'string'],
        ];
    }
}
