<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TWGProjectRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'twg_expert_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'objective' => ['required', 'string'],
            'expected_output' => ['nullable'],
            'project_leader' => ['nullable', 'string', 'max:255'],
            'funding_agency' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable'],
            'status' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'twg_expert_id.required' => 'The TWG Expert ID is required.',
            'twg_expert_id.integer' => 'The TWG Expert ID must be an integer.',
            'twg_expert_id.exists' => 'The TWG Expert ID must exist in the database.',
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title must not be greater than 255 characters.',
            'objective.required' => 'The objective is required.',
            'objective.string' => 'The objective must be a string.',
            'expected_output.required' => 'The expected output is required.',
            'project_leader.string' => 'The project leader must be a string.',
            'project_leader.max' => 'The project leader must not be greater than 255 characters.',
            'funding_agency.string' => 'The funding agency must be a string.',
            'funding_agency.max' => 'The funding agency must not be greater than 255 characters.',
            'duration.required' => 'The duration is required.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
            'status.max' => 'The status must not be greater than 255 characters.',
        ];
    }
}
