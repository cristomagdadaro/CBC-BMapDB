<?php

namespace Modules\PbMap\Requests;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\PbMap\Models\Commodity;

class DeleteCommoditiesRequest extends FormRequest
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
            return true; // Allow admins (including bulk deletes)
        }

        $id = $this->route('id'); // Get the ID from route parameter
        if (!$id) {
            return false;
        }

        $model = Commodity::find($id);

        if (!$model) {
            return false;
        }

        if ($model && $model->user_id === $user->id) {
            return true; // Allow owner
        }

        if ($this->canManageAffiliatedRecord($user, $this->getBreederAffiliation($model))) {
            return true;
        }

        abort(403, __('You are not authorized to delete this commodity.'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:commodities,id',
            'ids' => 'sometimes|array|min:1',
            'ids.*' => 'integer|exists:commodities,id',
        ];
    }

    private function canManageAffiliatedRecord(User $user, ?int $affiliation): bool
    {
        if (!$this->isOrganizationLead($user)) {
            return false;
        }

        $userAff = (int) ($user->affiliation ?? 0);
        $targetAff = (int) ($affiliation ?? 0);

        return $userAff && $targetAff && $userAff === $targetAff;
    }

    private function isOrganizationLead(User $user): bool
    {
        return $user->isFocalPerson() || $user->isTwgManager();
    }

    private function getBreederAffiliation(Commodity $commodity): ?int
    {
        if ($commodity->relationLoaded('breeder')) {
            return optional($commodity->breeder)->affiliation;
        }

        return $commodity->breeder()->value('affiliation');
    }
}
