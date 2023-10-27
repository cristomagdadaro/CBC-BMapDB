<?php

namespace App\Repository;

class ErrorRepository
{
    protected \Exception $exception;

    public function __construct(\Exception $e)
    {
        $this->exception = $e;
    }

    public function getErrorMessage()
    {
        return $this->findErrorCodeMessage($this->exception->getCode());
    }

    private function findErrorCodeMessage($code)
    {
        $codes = config('error_codes');

        return $codes[$code] ?? 'Unknown error';
    }

    public function getErrorCode()
    {
        return $this->exception->getCode();
    }

}
