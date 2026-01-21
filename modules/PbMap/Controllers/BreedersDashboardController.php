<?php

namespace Modules\PbMap\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\PbMap\Repositories\BreedersDashboardRepo;

class BreedersDashboardController extends Controller
{
    public function __construct(private BreedersDashboardRepo $dashboardRepo)
    {
    }

    public function overview(Request $request)
    {
        return response()->json(
            $this->dashboardRepo->overview(
                $request->user(),
                $request->input('scope_by'),
                $request->input('institute_id')
            )
        );
    }

    public function recent(Request $request)
    {
        return response()->json(
            $this->dashboardRepo->recent(
                $request->user(),
                $request->input('scope_by'),
                $request->input('institute_id')
            )
        );
    }

    public function myStats(Request $request)
    {
        return response()->json($this->dashboardRepo->myStats($request->user()));
    }
}

