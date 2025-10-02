<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;

class DataViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid'             => $this->uuid,
            'user_account_id'  => $this->user_account_id,
            'model'            => $this->model,
            'columns'          => $this->columns,
            'default'          => Schema::getColumnListing($this->model),
            'visibility_guard' => $this->visibility_guard,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
            'deleted_at'       => $this->deleted_at,
        ];
    }
}

