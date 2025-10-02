<?php

namespace App\Http\Controllers;

use App\Models\CBCTourVisitor;
use Illuminate\Http\Request;

class CBCTourController extends Controller
{

    public function storeVisitor(Request $request)
    {
        CBCTourVisitor::create([
            'ip_address' => $request->ip(),
            'method' => $request->method(),
            'data' => $request->all(),
        ]);

        return response()->json(['visitorCount' => CBCTourVisitor::count()]);
    }

}
