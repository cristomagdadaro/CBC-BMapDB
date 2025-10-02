<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCityRequest;
use App\Http\Resources\CityResource;
use App\Repository\API\CityRepo;
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



}
