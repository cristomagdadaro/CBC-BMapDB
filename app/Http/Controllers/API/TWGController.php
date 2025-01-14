<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetUserRequest;
use App\Http\Resources\BaseCollection;
use App\Models\TWGExpert;
use App\Models\TWGProduct;
use App\Models\TWGProject;
use App\Models\TWGService;
use App\Repository\API\UserRepo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TWGController extends BaseController
{
    public function __construct(UserRepo $userRepo)
    {
        $this->service = $userRepo;
    }

    public function index(GetUserRequest $request){
        $data = $this->service->model
            ->leftJoin('twg_expert', function($join) {
                $join->on('users.id', '=', 'twg_expert.user_id')
                    ->whereNull('twg_expert.deleted_at');
            })
            ->leftJoin('twg_product', function($join) {
                $join->on('twg_expert.id', '=', 'twg_product.twg_expert_id')
                    ->whereNull('twg_product.deleted_at');
            })
            ->leftJoin('twg_service', function($join) {
                $join->on('twg_expert.id', '=', 'twg_service.twg_expert_id')
                    ->whereNull('twg_service.deleted_at');
            })
            ->leftJoin('twg_project', function($join) {
                $join->on('twg_expert.id', '=', 'twg_project.twg_expert_id')
                    ->whereNull('twg_project.deleted_at');
            })
            ->groupBy('users.affiliation')
            ->selectRaw('users.affiliation, COUNT(DISTINCT twg_expert.id) as experts, COUNT(DISTINCT twg_product.id) as products, COUNT(DISTINCT twg_project.id) as projects, COUNT(DISTINCT twg_service.id) as services')
            ->get();

        return new BaseCollection($data);

    }

    public function summary()
    {
        try {
            return response()->json([
                'totalExperts' => TWGExpert::all()->count(),
                'totalProjects' => TWGProject::all()->count(),
                'totalProducts' => TWGProduct::all()->count(),
                'totalServices' => TWGService::all()->count(),
                'totalOnGoingProjects' => TWGProject::select('status', DB::raw('count(*) as total'))->groupBy('status')->get()->pluck('total', 'status'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
