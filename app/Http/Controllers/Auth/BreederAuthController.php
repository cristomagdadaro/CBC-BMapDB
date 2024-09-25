<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BreederAuthController extends AuthController
{
    protected $redirectTo = '/projects/breedersmap';

    public function __construct()
    {
        $this->middleware('guest:breeder')->except('logout');
    }

    public function username()
    {
        return 'breeder_id';
    }

    protected function guard()
    {
        return Auth::guard('breeder');
    }

}
