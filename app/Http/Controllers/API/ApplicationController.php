<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateApplicationRequest;
use App\Http\Requests\GetApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Resources\ApplicationCollection;
use App\Http\Resources\BaseCollection;
use App\Models\Application;
use App\Repository\API\ApplicationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ApplicationController extends BaseController
{

    public function __construct(ApplicationRepo $applicationRepository)
    {
        $this->service = $applicationRepository;
    }

    public function index(GetApplicationRequest $request)
    {
        return parent::_index($request);
    }

    public function show(GetApplicationRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }

    public function store(CreateApplicationRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdateApplicationRequest $request, $id)
    {
        return parent::_update($request, $id);
    }

    public function destroy($id)
    {
        return parent::_destroy($id);
    }
}
