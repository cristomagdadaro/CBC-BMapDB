<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCityRequest;
use App\Http\Resources\CityResource;
use App\Repository\API\CityRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CityProvRegController extends BaseController
{
    public function __construct(CityRepo $cityProvRegRepository)
    {
        $this->service = $cityProvRegRepository;
    }

    public function cityIndex(GetCityRequest $request)
    {
        $data = $this->service->search(new Collection($request->validated()));

        return CityResource::collection($data);
    }

    /**
     * Get cities formatted for selection options (public use)
     * Includes province name in parentheses to handle duplicate city names
     */
    public function cityOptions(GetCityRequest $request): JsonResponse
    {
        $data = $this->service->search(new Collection($request->validated()));

        // Transform the data to option format with province in parentheses
        $data->getCollection()->transform(function ($city) {
            return [
                'value' => $city->id,
                'label' => $city->cityDesc . ', ' . $city->provDesc . ', ' . $city->regDesc,
            ];
        });

        return response()->json($data);
    }
}
