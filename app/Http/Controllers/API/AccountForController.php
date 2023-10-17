<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountForCollection;
use App\Models\AccountFor;

class AccountForController extends Controller
{

    public function index()
    {
        return new AccountForCollection(AccountFor::select()->get());
    }
}
