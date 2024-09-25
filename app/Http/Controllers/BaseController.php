<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\BaseControllerInterface;
use App\Http\Resources\BaseCollection;
use App\Repository\AbstractRepoService;
use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repository\ErrorRepository;
use Illuminate\Support\Collection;

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
