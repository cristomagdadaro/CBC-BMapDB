<?php

namespace Modules\TwgDb\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetUserRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\UserRepo;
use App\Traits\BuildsTwgQueries;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\TwgDb\Models\TWGExpert;
use Modules\TwgDb\Models\TWGProduct;
use Modules\TwgDb\Models\TWGProject;
use Modules\TwgDb\Models\TWGService;

class TWGController extends BaseController
{
    use BuildsTwgQueries;

    public function __construct(UserRepo $userRepo)
    {
        $this->service = $userRepo;
    }

    public function index(GetUserRequest $request){
        $query = $this->service->model->query();
        $data = $this->buildTwgSummaryQuery($query)->get();

        return new BaseCollection($data);

    }

    public function summary()
    {
        try {
            if (auth()->user()->isAdmin())
                return response()->json( ['data' => [
                    'totalExperts' => TWGExpert::all()->count(),
                    'totalProjects' => TWGProject::all()->count(),
                    'totalProducts' => TWGProduct::all()->count(),
                    'totalServices' => TWGService::all()->count(),
                    'typeServices' => TWGService::select('type', DB::raw('count(*) as total'))->groupBy('type')->get()->pluck('total', 'type'),
                    'topExperts' => TWGExpert::select('twg_expert.id', 'twg_expert.name', DB::raw('COUNT(twg_project.id) as project_count'))
                        ->join('twg_project', 'twg_expert.institution', '=', 'twg_project.institution')
                        ->groupBy('twg_expert.id', 'twg_expert.name')
                        ->orderByDesc('project_count')
                        ->limit(5)
                        ->get()
                        ->pluck('project_count', 'name'),
                    'totalOnGoingProjects' => TWGProject::select('status', DB::raw('count(*) as total'))->groupBy('status')->get()->pluck('total', 'status'),
                ]]);
            else {
                // Filter experts by the authenticated user's ID
                $user = auth()->user();
                $totalExperts = TWGExpert::ownedByUser($user)->ownedByAffiliation($user)->get();
                $totalProjects = TWGProject::ownedByUser($user)->ownedByAffiliation($user)->get();
                $totalProducts = TWGProduct::ownedByUser($user)->ownedByAffiliation($user)->get();
                $totalServices = TWGService::ownedByUser($user)->ownedByAffiliation($user)->get();

                // Get top 5 experts based on project count
                $topExperts = TWGExpert::select('twg_expert.id', 'twg_expert.name', DB::raw('COUNT(twg_project.id) as project_count'))
                    ->join('twg_project', 'twg_expert.institution', '=', 'twg_project.institution')
                    ->groupBy('twg_expert.id', 'twg_expert.name')
                    ->orderByDesc('project_count')
                    ->limit(5)
                    ->pluck('project_count', 'name');

                // Group projects by status and count them
                $totalOnGoingProjects = TWGProject::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->pluck('total', 'status');

                // Return the response as JSON
                return response()->json([
                    'totalExperts' => $totalExperts->count(),
                    'totalProjects' => $totalProjects->count(),
                    'totalProducts' => $totalProducts->count(),
                    'totalServices' => $totalServices->count(),
                    'typeServices' => TWGService::select('type', DB::raw('count(*) as total'))
                        ->ownedByUser($user)
                        ->ownedByAffiliation($user)
                        ->groupBy('type')
                        ->get()
                        ->pluck('total', 'type'),
                    'topExperts' => $topExperts,
                    'totalOnGoingProjects' => $totalOnGoingProjects,
                ]);

            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
