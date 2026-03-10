<?php

namespace Modules\TwgDb\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\TwgDb\Models\TWGExpert;

class UpdateTWGExpertRequest extends FormRequest
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

        $model = TWGExpert::find($this->route('id') ?? $this->get('id'));
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

        abort(403, __('You are not authorized to update this expert.'));
    }

    protected function prepareForValidation()
    {
        if (!auth()->user()->isAdmin()) {
            $this->merge([
                'institution' => auth()->user()->affiliation,
            ]);
        }

        $this->merge([
            'user_id'  => auth()->user()->id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('id') ?? $this->get('id');
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255', 'unique:twg_expert,name,' . $id],
            'position' => ['required', 'string', 'max:255'],
            'educ_level' => ['required', 'string', "in:Doctoral,Master's,Bachelor's"],
            'expertise' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'exists:institutes,id'],
            'research_interest' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'unique:twg_expert,mobile,' . $id, 'regex:/^09[0-9]{9}$/', 'max:11', 'min:11'],
            'email' => ['required', 'string','email', 'unique:twg_expert,email,' . $id],
        ];
    }
}
