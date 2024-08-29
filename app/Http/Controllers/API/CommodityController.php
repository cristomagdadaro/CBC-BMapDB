<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Interfaces\BaseControllerInterface;
use App\Http\Requests\CreateCommoditiesRequest;
use App\Http\Requests\DeleteCommoditiesRequest;
use App\Http\Requests\GetCommoditiesRequest;
use App\Http\Requests\UpdateCommoditiesRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\CommodityRepo;
use Illuminate\Support\Collection;

class CommodityController extends BaseController
{
    public function __construct(CommodityRepo $commodityRepository)
    {
        $this->service = $commodityRepository;
    }

    public function index(GetCommoditiesRequest $request, $id = null)
    {
        $this->service->appendWith(['breeder']);
        if ($id) {
            // Set withPagination to false to return the builder instead of the paginator, for the Map search box. By Breeder.
            // $per_page = $request->validated()['per_page'] ?? 10;
            // $page = $request->validated()['page'] ?? 1;
            $data = $this->service->search(new Collection($request->validated()), false);
            // return new BaseCollection($data->where('breeder_id', $id)->orderBy('id', 'asc')->paginate($per_page, ['*'], 'page', $page)->withQueryString());
            return new BaseCollection($data);
        } else {
            $data = $this->service->search(new Collection($request->validated()));
            return new BaseCollection($data);
        }
    }

    public function summary(GetCommoditiesRequest $request)
    {
        $model = $this->service->model;
        $group_by = $request->validated('group_by') ?? 'region';
        $search = $request->validated('search') ?? '';
        $is_exact = $request->validated('is_exact');

        if ($search)
        return response()->json([
            'chart_data' => $model->selectRaw($group_by.' as label, count(*) as total')
                ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                    if ($is_exact == 'true') {
                        return $query->where($group_by, $search);
                    } else {
                        return $query->where($group_by, 'like', '%'.$search.'%');
                    }
                })
                ->groupBy($group_by)
                ->orderBy('total', 'desc')
                ->get(),
            'commodities' => $model->when($search, function ($query) use ($search, $is_exact, $group_by) {
                if ($is_exact == 'true') {
                    return $query->where($group_by, $search);
                } else {
                    return $query->where($group_by, 'like', '%'.$search.'%');
                }
            })
                ->where($group_by, 'like', '%'.$search.'%')
                ->get(),
            'commodities_chart' => $model->selectRaw('name as label, count(*) as total')
                ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                    if ($is_exact == 'true') {
                        return $query->where($group_by, $search);
                    } else {
                        return $query->where($group_by, 'like', '%'.$search.'%');
                    }
                })->groupBy('name')
                ->orderBy('total', 'desc')
                ->get()
        ]);

        return response()->json([
            'chart_data' => $model->selectRaw($group_by.' as label, count(*) as total')
                ->groupBy($group_by)
                ->orderBy('total', 'desc')
                ->get(),
            'commodities' => $model->when($search, function ($query) use ($search, $is_exact, $group_by) {
                if ($is_exact == 'true') {
                    return $query->where($group_by, $search);
                } else {
                    return $query->where($group_by, 'like', '%'.$search.'%');
                }
            })->get(),
            'commodities_chart' => $model->selectRaw('name as label, count(*) as total')
                ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                    if ($is_exact == 'true') {
                        return $query->where($group_by, $search);
                    } else {
                        return $query->where($group_by, 'like', '%'.$search.'%');
                    }
                })
                ->groupBy('name')
                ->orderBy('total', 'desc')
                ->get()
        ]);
    }

    /** API used at Map search box*/
    public function noPage(GetCommoditiesRequest $request)
    {
        // Set withPagination to false to return the builder instead of the paginator, for the Map search box. All Commodities.
        $this->service->appendWith(['breeder']);
        $data = $this->service->search(new Collection($request->validated()), false);
        return new BaseCollection($data);
    }

    public function store(CreateCommoditiesRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function show($id)
    {
        return $this->service->find($id);
    }

    public function update(UpdateCommoditiesRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteCommoditiesRequest $request)
    {
        return $this->service->multiDestroy($request->validated());
    }
}
