<?php

namespace App\Http\Controllers;

use App\Repository\AbstractRepoService;
use Illuminate\Http\JsonResponse;

abstract class BaseController extends Controller
{
    protected AbstractRepoService $service;

    public function sendResponse($message, $data = null): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response);
    }


    public function sendFail($message, $error_code, $data = null): JsonResponse
    {
        $response = [
            'success' => false,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $error_code);
    }
}
