<?php

namespace Modules\PbMap\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\PbMap\Models\Commodity;

class ApproveCommoditiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $id = (int) $this->route('id');
        $model = Commodity::find($id);
        if (!$model) {
            return false;
        }

        $user = auth()->user();
        if ($user->isAdmin()) {
            return true;
        }

        // Focal person can approve within same institute as breeder's affiliation
        if (method_exists($user, 'isFocalPerson') && $user->isFocalPerson()) {
            $userAff = (int) ($user->affiliation ?? 0);
            $commodityAff = (int) ($model->relationLoaded('breeder') ? optional($model->breeder)->affiliation : $model->breeder()->value('affiliation'));
            return $userAff && $commodityAff && $userAff === $commodityAff;
        }

        return false;
    }

    public function rules(): array
    {
        return [];
    }
}

