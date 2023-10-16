<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionCollection;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return new PermissionCollection(Permission::select()->get());
    }
}
