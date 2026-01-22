<?php

namespace Modules\TwgDb\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\TwgDb\Models\TWGProject;

class DeleteTWGProjectRequest extends FormRequest
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

        if (!$user->isTwgManager()) {
            abort(403, __('You are not authorized to delete projects.'));
        }

        $userAff = (int) ($user->affiliation ?? 0);
        if (!$userAff) {
            abort(403, __('You are not authorized to delete projects.'));
        }

        $ids = $this->input('ids', []);
        $count = TWGProject::whereIn('id', $ids)
            ->where('institution', $userAff)
            ->count();

        if ($count !== count($ids)) {
            abort(403, __('You are not authorized to delete projects outside your institution.'));
        }

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
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:twg_project,id',
        ];
    }
}
