<?php

namespace Modules\TwgDb\Controllers;

use App\Http\Controllers\BaseController;
use Modules\TwgDb\Repositories\TWGProductRepo;
use Modules\TwgDb\Requests\CreateTWGProductRequest;
use Modules\TwgDb\Requests\DeleteTWGProductRequest;
use Modules\TwgDb\Requests\GetTWGProductRequest;
use Modules\TwgDb\Requests\UpdateTWGProductRequest;

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
