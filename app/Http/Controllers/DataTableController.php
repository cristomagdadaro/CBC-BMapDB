<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataTableRequest;
use Illuminate\Support\Facades\DB;

class DataTableController extends Controller
{
    public function index(DataTableRequest $request)
    {
        if($request->has('filterKey')){
            return $this->indexWithFilter($request);
        }
        $perPage = $request->input('per_page', 10);
        $columns = explode(',', $request->input('columns', '*'));
        return DB::table($request['table'])->select($columns)->paginate($perPage);
    }

    public function indexWithFilter(DataTableRequest $request)
    {
        $perPage = $request->input('per_page', 10);
        $columns = explode(',', $request->input('columns', '*'));
        $filter = $request->input('filterValue', '');
        return DB::table($request['table'])->select($columns)->where($request['table'].'.'.$request['filterKey'], 'like', '%'.$filter.'%')->paginate($perPage);
    }
}
