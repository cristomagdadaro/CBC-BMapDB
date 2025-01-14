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
        $this->service->appendWith(['city']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show($id)
    {
        return $this->service->find($id);
    }
}
