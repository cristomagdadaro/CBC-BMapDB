<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetInstituteRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\InstituteRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

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

    /**
     * Get institutes formatted for selection options (public use)
     */
    public function options(GetInstituteRequest $request): JsonResponse
    {
        $data = $this->service->search(new Collection($request->validated()));

        // Transform the data to option format
        $data->getCollection()->transform(function ($institute) {
            return [
                'value' => $institute->id,
                'label' => $institute->name,
            ];
        });

        return response()->json($data);
    }
}
