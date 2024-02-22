<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\TWGExpert;
use App\Models\TWGProduct;
use App\Models\TWGProject;
use App\Models\TWGService;

class TWGController extends BaseController
{

    public function summary()
    {
        return response()->json([
            'totalExperts' => TWGExpert::all()->count(),
            'totalProjects' => TWGProject::all()->count(),
            'totalProducts' => TWGProduct::all()->count(),
            'totalServices' => TWGService::all()->count(),
        ]);
    }

}
