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
use Modules\TwgDb\Services\TWGDashboardService;

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

    public function summary(TWGDashboardService $dashboardService)
    {
        try {
            $data = $dashboardService->getSummaryData();
            if (empty($data)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            if (auth()->user()->hasRole(\App\Enums\Role::ADMIN->value)) {
                return response()->json(['data' => $data]);
            }
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
