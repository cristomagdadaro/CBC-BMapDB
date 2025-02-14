<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Interfaces\CommodityControllerInterface;
use App\Http\Requests\CreateCommoditiesRequest;
use App\Http\Requests\DeleteCommoditiesRequest;
use App\Http\Requests\GetCommoditiesRequest;
use App\Http\Requests\UpdateCommoditiesRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\CommodityRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CommodityFilters
{
    public string|null $geo_location_value;
    public string|null $geo_location_filter;
    public string|null $filter_by_parent_column;
    public string|null $filter_by_parent_id;
    public string|null $filter;
    public string|null $is_exact;
    public string|null $group_by;
    public string|null $commodities;
    public string|null $search;

    public function __construct($geo_location_value,
                                $geo_location_filter,
                                $filter_by_parent_column,
                                $filter_by_parent_id,
                                $filter,
                                $search,
                                $is_exact,
                                $commodities,
                                $group_by
    )
    {
        $this->geo_location_value = $geo_location_value;
        $this->geo_location_filter = $geo_location_filter;
        $this->filter_by_parent_column = $filter_by_parent_column;
        $this->filter_by_parent_id = $filter_by_parent_id;
        $this->filter = $filter;
        $this->search = $search;
        $this->is_exact = $is_exact;
        $this->commodities = $commodities;
        $this->group_by = $group_by;
    }
}

class CommodityController extends BaseController implements CommodityControllerInterface
{
    public function __construct(CommodityRepo $commodityRepository)
    {
        $this->service = $commodityRepository;
    }

    public function index(GetCommoditiesRequest $request): BaseCollection
    {
        return parent::_index($request);
    }

    public function show(GetCommoditiesRequest $request, int $id): JsonResponse
    {
        return parent::_show($request, $id);
    }

    public function store(CreateCommoditiesRequest $request): JsonResponse
    {
        return parent::_store($request);
    }

    public function update(UpdateCommoditiesRequest $request, int $id): JsonResponse
    {
        return parent::_update($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteCommoditiesRequest $request): JsonResponse
    {
        return parent::_multiDestroy($request);
    }

    public function summary(GetCommoditiesRequest $request): JsonResponse
    {
        $model = $this->service->model;

        $filter = new CommodityFilters(
            $request->validated('geo_location_value'),
            $request->validated('geo_location_filter') ?? 'region',
            $request->validated('filter_by_parent_column'),
            $request->validated('filter_by_parent_id'),
            $request->validated('filter'),
            $request->validated('search') ?? '',
            $request->validated('is_exact'),
            $request->all()['commodity'] ?? null,
            $this->service->determineLocFilterLevel($request->validated('geo_location_filter') ?? 'region'),
        );

        $commodities = $this->service->applyFilters($this->service->checkRole($model), $filter)
            ->select($model->getSearchable())
            ->with(['breeder','location'])
            ->get();
        $chart_data = $this->service->applyFilters($model, $filter)
            ->selectRaw("$filter->group_by as label, count(*) as total")
            ->groupBy($filter->group_by)
            ->orderBy('total', 'desc')
            ->get();
        $commodities_chart = $this->service->applyFilters($model, $filter)
            ->selectRaw('commodities.name as label, count(*) as total')
            ->groupBy('commodities.name')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'params' => [
                'commodity' => $filter->commodities,
                'group_by' => $filter->group_by,
                'geo_location_filter' => $filter->geo_location_filter,
                'geo_location_value' => $filter->geo_location_value,
                'is_exact' => $filter->is_exact,
            ],
            'group_search_institute' => $this->service->getGroupByInstitute($model, $filter->commodities, $filter->geo_location_filter),
            'chart_labels' => $commodities_chart,
            'chart_data' => $chart_data,
            'raw_data' => $commodities,
            'raw_data_labels' => $this->service->getCommodityLabels($model, $filter->geo_location_value, $filter->is_exact, $filter->geo_location_filter),
            'group_search_labels' => $this->service->getGroupByGeoLoc($model, $filter->commodities, $filter->geo_location_filter),
            'linechart_data' => $this->service->linechartData($model, $filter->geo_location_value, $filter->is_exact, $filter->geo_location_filter, $filter->commodities),
        ]);
    }

    /**
     * For Dropdown Menu
    */
    public function priorityCommodities()
    {
        $commodities = config('system_variables.commodities');
        $formatted = [];
        foreach ($commodities as $key => $value) {
            $formatted[] = [
                'id' => $key,
                'label' => $key,
                'sName' => $value
            ];
        }
        return $this->sendResponse($formatted);
    }

    public function noPage(GetCommoditiesRequest $request): BaseCollection
    {
        $this->service->appendWith(['breeder','cityDesc']);
        $data = $this->service->search(new Collection($request->validated()), false);
        return new BaseCollection($data);
    }
}
