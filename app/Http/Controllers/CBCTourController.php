<?php

namespace App\Http\Controllers;

use App\Repository\API\CBCTourVisitorRepo;
use Illuminate\Http\Request;

class CBCTourController extends Controller
{
    protected CBCTourVisitorRepo $visitorRepo;

    public function __construct(CBCTourVisitorRepo $visitorRepo)
    {
        $this->visitorRepo = $visitorRepo;
    }

    public function storeVisitor(Request $request)
    {
        $this->visitorRepo->createVisitor([
            'ip_address' => $request->ip(),
            'method' => $request->method(),
            'data' => $request->all(),
        ]);

        return response()->json(['visitorCount' => $this->visitorRepo->getVisitorCount()]);
    }

}
