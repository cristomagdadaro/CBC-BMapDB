<?php

namespace App\Http\Controllers;

use App\Repository\AbstractRepoService;
use Illuminate\Http\JsonResponse;

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


    public function sendFail($message, $error_code, $data = null): JsonResponse
    {
        $response = [
            'data' => $data,
        ];

        return response()->json($response, $error_code);
    }
}
