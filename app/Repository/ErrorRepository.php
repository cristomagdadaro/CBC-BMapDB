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
