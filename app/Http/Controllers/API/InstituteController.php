<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetInstituteRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\InstituteRepo;
use Illuminate\Support\Collection;

class InstituteController extends BaseController
{
    public function __construct(InstituteRepo $instituteRepository)
    {
        $this->service = $instituteRepository;
    }

    public function index(GetInstituteRequest $request)
    {
        $data = $this->service->search(new Collection($request->validated()));
        //make the name column as the id
        $data = $data->map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'inst_type' => $item['inst_type'],
                'city' => $item['city'],
                'province' => $item['province'],
                'region' => $item['region'],
                'website' => $item['website'],
                'email' => $item['email'],
                'phone' => $item['phone'],
            ];
        });
        return [
            'data' => $data,
            'meta' => [
                'total' => $data->count(),
            ],
        ];
    }

    public function show($id)
    {
        return $this->service->find($id);
    }
}
