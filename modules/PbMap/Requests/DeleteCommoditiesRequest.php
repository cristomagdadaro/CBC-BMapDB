<?php

namespace Modules\PbMap\Requests;

use App\Enums\Permission;
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
        $id = $this->route('id'); // Get the ID from route parameter
        $model = Commodity::find($id);

        if (!$model) {
            return false;
        }

        if (auth()->user()->isAdmin()) {
            return true; // Allow admins
        }

        if ($model && $model->user_id === auth()->id()) {
            return true; // Allow owner
        }

        if (auth()->user()->isFocalPerson()) {
            $userAff = (int) (auth()->user()->affiliation ?? 0);
            $commodityAff = (int) ($model->relationLoaded('breeder') ? optional($model->breeder)->affiliation : $model->breeder()->value('affiliation'));
            return $userAff && $commodityAff && $userAff === $commodityAff;
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
        ];
    }
}
