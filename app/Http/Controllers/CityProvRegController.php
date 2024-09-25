<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCityRequest;
use App\Repository\API\CityRepo;
use Illuminate\Http\Request;
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

        $data = $data->map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => "{$item['cityDesc']}, {$item['provDesc']}, {$item['regDesc']}",
                'cityDesc' => $item['cityDesc'],
                'provDesc' => $item['provDesc'],
                'regDesc' => $item['regDesc'],
                'latitude' => $item['latitude'],
                'longitude' => $item['longitude'],
            ];
        });
        return [
            'data' => $data,
            'meta' => [
                'total' => $data->count(),
            ],
        ];
    }



}

