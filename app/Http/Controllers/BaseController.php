<?php

namespace App\Http\Controllers;

use App\Repository\AbstractRepoService;
use App\Repository\ErrorRepository;
use Illuminate\Http\JsonResponse;
use Exception;

abstract class BaseController extends Controller
{
    protected AbstractRepoService $service;

    public function sendResponse($data = null): JsonResponse
    {
        $response = [
            'data' => $data,
        ];

        return response()->json($response);
    }
}
