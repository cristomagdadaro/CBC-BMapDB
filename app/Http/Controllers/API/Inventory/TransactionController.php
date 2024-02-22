<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Requests\DeleteTransactionRequest;
use App\Http\Requests\Inventory\CreateTransactionRequest;
use App\Http\Requests\Inventory\GetTransactionsRequest;
use App\Http\Requests\Inventory\UpdateTransactionRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\Inventory\TransactionRepository;
use Illuminate\Support\Collection;
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
        $data = $this->repository->search(new Collection($request->validated()));
        return new BaseCollection($data);
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

    public function multiDestroy(DeleteTransactionRequest $request)
    {
        return $this->repository->multiDelete($request->validated());
    }

    public function stockIndex(GetTransactionsRequest $request)
    {
        $parameters = new Collection($request->validated());
        $perPage = $parameters->get('per_page', 10);
        $page = $parameters->get('page', 1);
        $orderBy = $parameters->get('sort', 'name');
        $result = $this->repository->model
            ->select('items.id', 'items.name', 'items.brand', 'unit',
                DB::raw('SUM(CASE WHEN transac_type = "in" THEN quantity ELSE -quantity END) AS remaining_qnty'),
                DB::raw('SUM(quantity) AS total_qnty'))
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->groupBy('items.id', 'items.name', 'items.brand', 'unit')
            ->orderBy($orderBy)
            ->paginate($perPage, ['*'], 'page', $page)->withQueryString();

        return new BaseCollection($result);
    }
}
