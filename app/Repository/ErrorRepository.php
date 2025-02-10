<?php

namespace App\Repository;

use Exception;

class ErrorRepository extends Exception
{
    protected Exception $exception;
    protected int $errno;

    public function __construct($e)
    {
        $errno = $e->getCode();
        parent::__construct($this->findErrorCodeMessage($e)['message'], (int) $errno);
        $this->exception = $e;
    }

    public function getErrorMessage(): array
    {
        return $this->findErrorCodeMessage($this->getErrorCode());
    }

    private function findErrorCodeMessage($error): array
    {
        $listOfErrorCodes = config('error_codes');
        //check if the error code exists in the list of error codes
        if (!array_key_exists($error->getCode(), $listOfErrorCodes)) {
            return [
                'errno' => 500,
                'message' =>  $error->getMessage(),
                'title' => 'Internal Server Error',
                'type' => 'failed',
                'timeout' => 10000,
                'show' => true,
            ];
        }
        return [
            'errno' => $error->getCode(),
            'message' => $listOfErrorCodes[$error->getCode()],
            'title' => 'Failed',
            'type' => 'failed',
            'timeout' => 10000,
            'show' => true,
        ];
    }

    public function getErrorCode(): int
    {
        return $this->exception->getCode();
    }

}
