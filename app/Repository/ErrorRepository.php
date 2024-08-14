<?php

namespace App\Repository;

class ErrorRepository
{
    protected \Exception $exception;

    public function __construct(\Exception $e)
    {
        $this->exception = $e;
    }

    public function getErrorMessage(): array
    {
        return $this->findErrorCodeMessage($this->getErrorCode());
    }

    private function findErrorCodeMessage($code): array
    {
        $listOfErrorCodes = config('error_codes');
        //check if the error code exists in the list of error codes
        if (!array_key_exists($code, $listOfErrorCodes)) {
            return [
                'errno' => $this->exception->getCode(),
                'message' =>  $this->exception->getMessage(),
                'title' => 'Internal Server Error',
                'type' => 'failed',
                'timeout' => 10000,
                'show' => true,
            ];
        }
        return [
            'errno' => $code,
            'message' => $listOfErrorCodes[$code],
            'title' => 'Failed',
            'type' => 'failed',
            'timeout' => 10000,
            'show' => true,
        ];
    }

    public function getErrorCode()
    {
        return $this->exception->getCode();
    }

}
