<?php

namespace Modules\TwgDb\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\TwgDb\Models\TWGService;

class UpdateTWGServiceRequest extends FormRequest
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

        $model = TWGService::find($this->route('id') ?? $this->get('id'));
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

        abort(403, __('You are not authorized to update this service.'));
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
            'type' => ['required', 'string'],
            'purpose' => ['required', 'string'],
            'direct_beneficiaries' => ['nullable', 'string'],
            'indirect_beneficiaries' => ['nullable', 'string'],
            'officer_in_charge' => ['required', 'exists:twg_expert,id'],
            'cost' => ['required'],
        ];
    }
}
