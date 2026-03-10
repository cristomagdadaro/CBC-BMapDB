<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Dashboard');
    }

    public function updateActivity(Request $request)
    {
        $request->user()->update([
            'last_activity_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
