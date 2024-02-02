<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\CreateItemRequest;
use App\Http\Requests\Inventory\GetItemsRequest;
use App\Http\Requests\Inventory\UpdateItemRequest;
use App\Http\Resources\BaseCollection;
use App\Models\Inventory\Item;
use App\Repository\API\Inventory\ItemRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemController extends BaseController
{
    public function __construct(ItemRepository $itemRepository)
    {
        $this->repository = $itemRepository;
    }

    public function index(GetItemsRequest $request)
    {
        $data = $this->repository->search(new Collection($request->validated()));
        return new ResourceCollection($data);
    }

    public function store(CreateItemRequest $request)
    {
        $request = $request->validated();
        $uuid = Str::uuid()->toString();
        while (DB::table('items')->where('id', $uuid)->exists()) {
            $uuid = Str::uuid()->toString();
        }
        $request['id'] = $uuid;

        return $this->repository->create($request);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function update(UpdateItemRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
