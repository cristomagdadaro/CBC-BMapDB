<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetApplicationRequest;
use App\Http\Resources\ApplicationCollection;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends BaseController
{
    /**
     * List of all applications a user has access to.
     * @var Application
     */
    protected $applications;

    /**
     * Create a new controller instance.
     */
    public function __construct(Request $request)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetApplicationRequest $request)
    {
        return new ApplicationCollection(Application::select()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        //
    }
}
