<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleCollection;
use App\Models\Role;

class RolesController extends Controller
{
    public function index()
    {
        return new RoleCollection(Role::select()->get());
    }
}
