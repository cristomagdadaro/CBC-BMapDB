<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateTWGProductRequest;
use App\Http\Requests\DeleteTWGProductRequest;
use App\Http\Requests\GetTWGProductRequest;
use App\Http\Requests\UpdateTWGProductRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\TWGProductRepo;
use Illuminate\Support\Collection;

class TWGProductController extends BaseController
{
    public function __construct(TWGProductRepo $productRepository)
    {
        $this->service = $productRepository;
    }

    public function index(GetTWGProductRequest $request)
    {
        return parent::_index($request);
    }

    public function show(GetTWGProductRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }

    public function store(CreateTWGProductRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdateTWGProductRequest $request, int  $id)
    {
        return parent::_update($request, $id);
    }

    public function destroy(int $id)
    {
        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteTWGProductRequest $request)
    {
        return parent::_multiDestroy($request);
    }
}
