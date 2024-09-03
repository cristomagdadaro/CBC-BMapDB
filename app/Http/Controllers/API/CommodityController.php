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
        $commodity = $request->all()['commodity'] ?? null;

        if ($search)
        return response()->json([
            'chart_data' => $model->selectRaw($group_by.' as label, count(*) as total')
                ->when($search, function ($query) use ($commodity, $search, $is_exact, $group_by) {
                    if ($commodity)
                        return $query->where('name', $commodity);
                    else if ($is_exact == 'true') {
                        return $query->where($group_by, $search);
                    } else {
                        return $query->where($group_by, 'like', '%'.$search.'%');
                    }
                })
                ->groupBy($group_by)
                ->orderBy('total', 'desc')
                ->get(),
            'commodities' => $model->when($search, function ($query) use ($commodity, $search, $is_exact, $group_by) {
                if ($commodity)
                    return $query->where('name', $commodity);
                else if ($is_exact == 'true') {
                    return $query->where($group_by, $search);
                } else {
                    return $query->where($group_by, 'like', '%'.$search.'%');
                }
            })
                ->where($group_by, 'like', '%'.$search.'%')
                ->get(),
            'commodities_chart' => $model->selectRaw('name as label, count(*) as total')
                ->when($search, function ($query) use ($commodity, $search, $is_exact, $group_by) {
                    if ($commodity)
                        return $query->where('name', $commodity);
                    else if ($is_exact == 'true') {
                        return $query->where($group_by, $search);
                    } else {
                        return $query->where($group_by, 'like', '%'.$search.'%');
                    }
                })
                ->groupBy('name')
                ->orderBy('total', 'desc')
                ->get(),
            // graph the yield of the commodities
            'commodities_linechart' => $this->linechartData($model, $search, $is_exact, $group_by, $commodity)
        ]);

        return response()->json([
            'request' => $request->all(),
            'chart_data' => $model->selectRaw("$group_by as label, count(*) as total")
                ->when($commodity, function ($query) use ($commodity) {
                    return $query->where('name', $commodity);
                })
                ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                    if ($is_exact === 'true') {
                        return $query->where($group_by, $search);
                    } else {
                        return $query->where($group_by, 'like', '%'.$search.'%');
                    }
                })
                ->groupBy($group_by)
                ->orderBy('total', 'desc')
                ->get(),
            'commodities' => $model->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            })
                ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                    if ($is_exact === 'true') {
                        return $query->where($group_by, $search);
                    } else {
                        return $query->where($group_by, 'like', '%'.$search.'%');
                    }
                })
                ->get(),
            'commodities_chart' => $model->selectRaw('name as label, count(*) as total')
                ->when($commodity, function ($query) use ($commodity) {
                    return $query->where('name', $commodity);
                })
                ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                    if ($is_exact === 'true') {
                        return $query->where($group_by, $search);
                    } else {
                        return $query->where($group_by, 'like', '%'.$search.'%');
                    }
                })
                ->groupBy('name')
                ->orderBy('total', 'desc')
                ->get(),
            'commodities_linechart' => $this->linechartData($model, $search, $is_exact, $group_by, $commodity)
        ]);
    }

    private function linechartData($model, $search = null, $is_exact = false, $group_by = 'name', $commodity = null) {
        // Apply filters based on search criteria and commodity
        if ($search) {
            $model = $model->where($group_by, $search);
        }

        if ($commodity) {
            $model = $model->where('name', $commodity);
        }

        // Fetch results
        $results = $model->selectRaw('name, CONCAT(name, "-", variety) as full_name, variety, population as total')
            ->whereNotNull('population')
            ->orderBy('name', 'asc')
            ->orderBy('variety', 'asc')
            ->get();

        $datasets = [];
        foreach ($results->groupBy('name') as $name => $dataGroup) {
            $dataset = [
                'label' => $name,
                'data' => $dataGroup->pluck('total')->toArray(),
                'borderColor' => 'rgba('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).', 1)',
                'fill' => true
            ];
            $datasets[] = $dataset;
        }

        // Return the formatted data
        return [
            'labels' => $results->pluck('full_name')->unique()->values()->toArray(),
            'datasets' => $datasets
        ];
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
