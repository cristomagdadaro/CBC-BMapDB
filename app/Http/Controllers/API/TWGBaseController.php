<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataTable\DataTableController;
use App\Http\Requests\DataTableRequest;
use App\Http\Resources\TWGResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class TWGBaseController extends Controller
{
    protected DataTableController $datatable;

    /**
     *
    */
    public function __construct()
    {
        $this->datatable = new DataTableController();
    }

    /**
     * Retrieve all data
    */
    public function index(Model $model): mixed
    {
        return TWGResource::collection($model::all());
    }

    /**
     * Retrieve the specified resource from storage, compatible with datatable.
     * @param DataTableRequest $request
     * @return LengthAwarePaginator
     */
    public function dataTable(DataTableRequest $request): LengthAwarePaginator
    {
        return $this->datatable->index($request);
    }
}
