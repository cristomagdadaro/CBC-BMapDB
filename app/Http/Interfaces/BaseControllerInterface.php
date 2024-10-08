<?php

namespace App\Http\Interfaces;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

interface BaseControllerInterface
{
    public function index(BaseRequest $request);

    public function store(FormRequest $request);

    public function show(int $id);

    public function update(FormRequest $request, int $id);

    public function destroy(int $id);

    public function multiDestroy(FormRequest $request);
}
