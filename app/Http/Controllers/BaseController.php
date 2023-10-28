<?php

namespace App\Http\Controllers;

use App\Repository\BaseRepository;
use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Repository\ErrorRepository;
use Illuminate\Support\Collection;

abstract class BaseController extends Controller
{
    protected $repository;

    public function sendResponse($message, $data = null)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];

        return $response;
    }

    public function sendError(\Exception $error)
    {
        $error = new ErrorRepository($error);
        $response = [
            'success' => false,
            'message' => $error->getErrorMessage(),
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $error->getErrorMessage();
        }

        return response()->json($response, 500);
    }
}
