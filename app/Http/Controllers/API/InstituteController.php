<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetInstituteRequest;
use App\Repository\API\InstituteRepo;

class InstituteController extends BaseController
{
    public function __construct(InstituteRepo $instituteRepository)
    {
        $this->service = $instituteRepository;
    }

    public function index(GetInstituteRequest $request)
    {
        return parent::_index($request);
    }

    public function show(GetInstituteRequest $request, int $id)
    {
        return parent::_show( $request, $id);
    }
}
