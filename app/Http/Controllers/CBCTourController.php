<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CBCTourController extends Controller
{

    public function storeVisitor(Request $request)
    {
        DB::table('cbctour_visitors')->insert([
            'ip_address' => $request->ip(),
            'method' => $request->method(),
            'data' => json_encode($request->all()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['visitorCount' => DB::table('cbctour_visitors')->count()]);
    }

}
