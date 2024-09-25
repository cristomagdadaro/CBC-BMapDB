<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CommodityController extends BaseController
{
    public function __construct(CommodityRepo $commodityRepository)
    {
        $this->service = $commodityRepository;
    }

    public function index(GetCommoditiesRequest $request, int|null $parent_id = null)
    {
        $this->service->appendWith(['breeder', 'location']);
        $this->service->filterByParent(['column' => 'breeder_id', 'value' => $parent_id]);
        /*if (auth()->user()->getRole() !== Role::ADMIN->value) {
            //cant filter commodity by breeder_id under the current user
            //$this->service->appendCondition(auth()->id());
        }*/
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function summary(GetCommoditiesRequest $request)
    {
        $model = $this->service->model;
        $geo_location_filter = $request->validated('geo_location_filter') ?? 'region';
        $geo_location_value = $request->validated('geo_location_value');
        $is_exact = $request->validated('is_exact');
        $commodity = $request->all()['commodity'] ?? null;
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        $commodities = $this->applyFilters($model, $commodity, $geo_location_value, $geo_location_filter)
            ->select($model->getSearchable())
            ->with(['breeder','location'])
            ->get();
        $chart_data = $this->applyFilters($model, $commodity, $geo_location_value, $geo_location_filter)
            ->selectRaw("$group_by as label, count(*) as total")
            ->groupBy($group_by)
            ->orderBy('total', 'desc')
            ->get();
        $commodities_chart = $this->applyFilters($model, $commodity, $geo_location_value, $geo_location_filter)
            ->selectRaw('name as label, count(*) as total')
            ->groupBy('name')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'params' => [
                'commodity' => $commodity,
                'group_by' => $group_by,
                'geo_location_filter' => $geo_location_filter,
                'geo_location_value' => $geo_location_value,
                'is_exact' => $is_exact,
            ],
            'chart_labels' => $commodities_chart,
            'chart_data' => $chart_data,
            'raw_data' => $commodities,
            'raw_data_labels' => $this->getCommodityLabels($model, $geo_location_value, $is_exact, $geo_location_filter),
            'group_search_labels' => $this->getGroupByGeoLoc($model, $commodity, $geo_location_filter),
            'linechart_data' => $this->linechartData($model, $geo_location_value, $is_exact, $geo_location_filter, $commodity),
        ]);
    }

    private function applyFilters($model, $commodity, $geo_location_value, $geo_location_filter) {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        $model = $model->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation');

        $model = $model
            ->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            });

        if ($geo_location_value) {
            $model = $model->where('loc_cities.' . $group_by, $geo_location_value);
        }

        return $model;
    }

    private function linechartData($model, $search = null, $is_exact = false, $group_by = 'name', $commodity = null) {
        $group_by = $this->determineLocFilterLevel($group_by);


        $model = $model->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation');

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

    private function getGroupByGeoLoc($model, $commodity, $geo_location_filter)
    {
        $pluck_name = $this->determineLocFilterLevel($geo_location_filter);

        return $model
            ->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            })
            ->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation')
            ->groupBy('loc_cities.' . $pluck_name)
            ->get($pluck_name)
            ->pluck($pluck_name)
            ->sort()
            ->values();
    }

    private function getCommodityLabels($model, $geo_location_value, $is_exact, $geo_location_filter)
    {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        return $model
            ->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation')
            ->when($geo_location_value, function ($query) use ($geo_location_value, $is_exact, $group_by) {
                if ($is_exact === 'true') {
                    return $query->where($group_by, $geo_location_value);
                } else {
                    return $query->where($group_by, 'like', '%'.$geo_location_value.'%');
                }
            })
            ->get()
            ->pluck('name')
            ->unique()
            ->sort()
            ->values();
    }

    private function determineLocFilterLevel($geo_location_filter): string
    {
        return match ($geo_location_filter) {
            'province' => 'provDesc',
            'region' => 'regDesc',
            default => 'cityDesc',
        };
    }


    /** API used at Map search box*/
    public function noPage(GetCommoditiesRequest $request)
    {
        $this->service->appendWith(['breeder','cityDesc']);
        $data = $this->service->search(new Collection($request->validated()), false);
        return new BaseCollection($data);
    }

    public function store(CreateCommoditiesRequest $request): JsonResponse
    {
        return $this->service->create($request->validated());
    }

    public function show(GetCommoditiesRequest $request, int $id)
    {
        $this->service->appendWith(['breeder', 'location']);

        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function update(UpdateCommoditiesRequest $request, int $id): JsonResponse
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id): JsonResponse
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteCommoditiesRequest $request): JsonResponse
    {
        return $this->service->multiDestroy($request->validated());
    }
}
