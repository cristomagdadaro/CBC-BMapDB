<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Interfaces\BaseControllerInterface;
use App\Http\Requests\CreateCommoditiesRequest;
use App\Http\Requests\DeleteCommoditiesRequest;
use App\Http\Requests\GetCommoditiesRequest;
use App\Http\Requests\UpdateCommoditiesRequest;
use App\Http\Resources\BaseCollection;
use App\Models\Commodity;
use App\Models\Location\City;
use App\Models\Location\Province;
use App\Models\Location\Region;
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

        $commodities = $this->applyFilters($model, $commodity, $search, $is_exact, $group_by)->get();
        $chart_data = $this->applyFilters($model->selectRaw("$group_by as label, count(*) as total"), $commodity, $search, $is_exact, $group_by)
            ->groupBy($group_by)
            ->orderBy('total', 'desc')
            ->get();
        $commodities_chart = $this->applyFilters($model->selectRaw('name as label, count(*) as total'), $commodity, $search, $is_exact, $group_by)
            ->groupBy('name')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'commodity_labels' => $this->getCommodityLabels($model, $search, $is_exact, $group_by),
            'group_search_labels' => $this->getGroupByLabels($model, $commodity, $group_by),
            'chart_data' => $chart_data,
            'commodities_chart' => $commodities_chart,
            'commodities_linechart' => $this->linechartData($model, $search, $is_exact, $group_by, $commodity),
            'commodities' => $commodities,
        ]);
    }

    private function applyFilters($model, $commodity, $search, $is_exact, $group_by) {
        return $model->when($commodity, function ($query) use ($commodity) {
            return $query->where('name', $commodity);
        })
            ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                if ($is_exact === 'true') {
                    return $query->where($group_by, $search);
                } else {
                    return $query->where($group_by, 'like', '%'.$search.'%');
                }
            })->with(['breeder', 'cityDesc']);
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
                'fill' => false
            ];
            $datasets[] = $dataset;
        }

        return [
            'labels' => $results->pluck('full_name')->unique()->values()->toArray(),
            'datasets' => $datasets
        ];
    }

    private function getGroupByLabels($model, $commodity, $group_by)
    {
        return $model->select($group_by)
            ->where('population', '>', 0)
            ->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            })
            ->groupBy($group_by)
            ->get()
            ->pluck($group_by)
            ->unique()
            ->sort()
            ->values();
    }

    private function getCommodityLabels($model, $search, $is_exact, $group_by)
    {
        return $model->where('population', '>', 0)
            ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                if ($is_exact === 'true') {
                    return $query->where($group_by, $search);
                } else {
                    return $query->where($group_by, 'like', '%'.$search.'%');
                }
            })
            ->get()
            ->pluck('name')
            ->unique()
            ->sort()
            ->values();
    }


    /** API used at Map search box*/
    public function noPage(GetCommoditiesRequest $request)
    {
        $this->service->appendWith(['breeder','cityDesc']);
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
