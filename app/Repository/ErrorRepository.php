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
                'errno' => 500,
                'message' => 'Unknown error, action failed.',
            ];
        }
        return [
            'errno' => $code,
            'message' => $listOfErrorCodes[$code],
        ];
    }

    public function getErrorCode()
    {
        return $this->exception->getCode();
    }

}
