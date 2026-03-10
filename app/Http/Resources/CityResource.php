<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'id' => $this->id,
            'name' => "{$this->cityDesc}, {$this->provDesc}, {$this->regDesc}",
            'cityDesc' => $this->cityDesc,
            'provDesc' => $this->provDesc,
            'regDesc' => $this->regDesc,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}

