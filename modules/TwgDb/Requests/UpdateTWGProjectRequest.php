<?php

namespace Modules\TwgDb\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\TwgDb\Models\TWGProject;

class UpdateTWGProjectRequest extends FormRequest
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
            return true;
        }

        $model = TWGProject::find($this->route('id') ?? $this->get('id'));
        if (!$model) {
            return false;
        }

        if ($model->user_id === $user->id) {
            return true;
        }

        if ($user->isTwgManager()) {
            $userAff = (int) ($user->affiliation ?? 0);
            $modelAff = (int) ($model->institution ?? 0);
            if ($userAff && $modelAff && $userAff === $modelAff) {
                return true;
            }
        }

        abort(403, __('You are not authorized to update this project.'));
    }

    protected function prepareForValidation()
    {
        if (!auth()->user()->isAdmin()) {
            $this->merge([
                'institution' => auth()->user()->affiliation,
            ]);
        }
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
