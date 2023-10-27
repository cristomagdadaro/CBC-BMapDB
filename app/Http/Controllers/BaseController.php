<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\ErrorRepository;

class BaseController extends Controller
{
    public function sendReponse($message, $data = null)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];

        return $response()->json($response, 200);
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
