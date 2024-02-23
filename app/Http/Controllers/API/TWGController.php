<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\TWGExpert;
use App\Models\TWGProduct;
use App\Models\TWGProject;
use App\Models\TWGService;
use Illuminate\Support\Facades\DB;

class TWGController extends BaseController
{

    public function summary()
    {
        return response()->json([
            'totalExperts' => TWGExpert::all()->count(),
            'totalProjects' => TWGProject::all()->count(),
            'totalProducts' => TWGProduct::all()->count(),
            'totalServices' => TWGService::all()->count(),
            'totalOnGoingProjects' => TWGProject::select('status', DB::raw('count(*) as total'))->groupBy('status')->get()->pluck('total', 'status'),
        ]);
    }

}
