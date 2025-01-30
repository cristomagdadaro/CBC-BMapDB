<?php
use Symfony\Component\HttpFoundation\Response;

return [
    'success' => [
        'message' => 'Resource retrieved successfully',
        'show' => true,
        'title' => 'Success',
        'type' => 'success',
        'timeout' => 10000,
        'statusCode' => Response::HTTP_OK,
    ],
    'created' => [
        'message' => 'Resource created successfully',
        'show' => true,
        'title' => 'Success',
        'type' => 'success',
        'timeout' => 10000,
        'statusCode' => Response::HTTP_CREATED,
    ],
    'updated' => [
        'message' => 'Resource updated successfully',
        'show' => true,
        'title' => 'Success',
        'type' => 'success',
        'timeout' => 10000,
        'statusCode' => Response::HTTP_OK,
    ],
    'deleted' => [
        'message' => 'Resource deleted successfully',
        'show' => true,
        'title' => 'Success',
        'type' => 'success',
        'timeout' => 10000,
        'statusCode' => Response::HTTP_OK,
    ],
    'failure' => [
        'message' => 'Failed to process the request',
        'show' => true,
        'title' => 'Error',
        'type' => 'error',
        'timeout' => 10000,
        'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
    ],
    'error' => [
        'message' => 'Failed to execute action',
        'show' => true,
        'title' => 'Failed',
        'type' => 'failed',
        'timeout' => 10000,
        'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
    ],

];
