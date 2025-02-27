<?php

namespace Modules\PbMap\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\PbMap\Models\Breeder;

class DeleteBreederRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $id = $this->route('id'); // Get the ID from route parameter
        $model = Breeder::find($id);

        if (auth()->user()->isAdmin() || auth()->user()->isFocalPerson()) {
            return true; // Allow admins
        }

        if ($model && $model->user_id === auth()->id()) {
            return true; // Allow owner
        }

        abort(403, __('You are not authorized to delete this breeder.'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:breeders,id',
        ];
    }
}
