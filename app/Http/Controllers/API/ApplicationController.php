<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateApplicationRequest;
use App\Http\Requests\GetApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\ApplicationRepo;
use Illuminate\Http\JsonResponse;
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

    public function update(UpdateApplicationRequest $request, int $id)
    {
        return parent::_update($request, $id);
    }

    public function destroy($id)
    {
        return parent::_destroy($id);
    }

    /**
     * Get applications formatted for selection options (public use)
     */
    public function options(GetApplicationRequest $request): JsonResponse
    {
        $data = $this->service->search(new Collection($request->validated()));

        // Transform the data to option format
        $data->getCollection()->transform(function ($application) {
            return [
                'value' => $application->id,
                'label' => $application->name,
            ];
        });

        return response()->json($data);
    }
}
