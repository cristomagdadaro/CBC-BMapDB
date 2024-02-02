<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\CreateTransactionRequest;
use App\Http\Requests\Inventory\GetTransactionsRequest;
use App\Http\Requests\Inventory\UpdateTransactionRequest;
use App\Repository\API\Inventory\TransactionRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends BaseController
{

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->repository = $transactionRepository;
    }

    public function index(GetTransactionsRequest $request)
    {
        $data = $this->repository->search($request->collect());
        return new ResourceCollection($data);
    }

    public function store(CreateTransactionRequest $request)
    {
        $request = $request->validated();
        $uuid = Str::uuid()->toString();
        while (DB::table('transactions')->where('id', $uuid)->exists()) {
            $uuid = Str::uuid()->toString();
        }
        $request['id'] = $uuid;
        return $this->repository->create($request);
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function update(UpdateTransactionRequest $request, $id)
    {
        return $this->repository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
