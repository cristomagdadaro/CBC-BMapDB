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
        return $this->findErrorCode($this->exception->getCode());
    }

    private function findErrorCode($code)
    {
        $codes = config('error_codes');

        return $codes[$code] ?? 'Unknown error';
    }

}
