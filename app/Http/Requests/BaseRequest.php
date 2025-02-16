<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    public function append($key, $value): void
    {
        $this->merge([$key => $value]);
    }
}
