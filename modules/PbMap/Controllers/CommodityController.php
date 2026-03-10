<?php

namespace Modules\PbMap\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Resources\BaseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Modules\PbMap\Interfaces\CommodityControllerInterface;
use Modules\PbMap\Repositories\CommodityRepo;
use Modules\PbMap\Requests\CreateCommoditiesRequest;
use Modules\PbMap\Requests\DeleteCommoditiesRequest;
use Modules\PbMap\Requests\GetCommoditiesRequest;
use Modules\PbMap\Requests\UpdateCommoditiesRequest;
use Modules\PbMap\Requests\ApproveCommoditiesRequest;

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

    public function selection(GetCommoditiesRequest $request): BaseCollection
    {
        return parent::_selection($request);
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

    public function destroy(DeleteCommoditiesRequest $request, int $id): JsonResponse
    {
        return parent::_destroy($id);
    }

    public function multiDestroy(DeleteCommoditiesRequest $request): JsonResponse
    {
        return parent::_multiDestroy($request);
    }

    public function summary(GetCommoditiesRequest $request): JsonResponse
    {
        if (auth()->check())
            return $this->summaryPrivate($request);
        return $this->summaryPublic($request);
    }

    private function summaryPublic(GetCommoditiesRequest $request): JsonResponse
    {
        /** @var CommodityRepo $repo */
        $repo = $this->service;
        $model = $repo->model;

        $geoLocationFilter = $request->validated('geo_location_filter') ?? 'region';
        $commodity = $request->input('commodity');

        $baseParams = collect([
            'geo_location_value' => $request->validated('geo_location_value'),
            'geo_location_filter' => $geoLocationFilter,
            'filter_by_parent_column' => $request->validated('filter_by_parent_column'),
            'filter_by_parent_id' => $request->validated('filter_by_parent_id'),
            'filter' => $request->validated('filter'),
            'search' => $request->validated('search') ?? '',
            'with' => $request->validated('with') ?? '',
            'is_exact' => $request->validated('is_exact'),
            'commodity' => $commodity,
            'paginate' => false,
        ]);

        $commodities = $repo->search(
            $baseParams->put('select', implode(',', $model->getSearchable())),
            false
        );

        $groupBy = $repo->determineLocFilterLevel($geoLocationFilter ?? 'region');

        $chart_data = $repo->search(
            $baseParams
                ->put('select_raw', "$groupBy as label, count(*) as total")
                ->put('group_by', $groupBy)
                ->put('sort', 'total')
                ->put('order', 'desc'),
            false
        );

        $commodities_chart = $repo->search(
            $baseParams
                ->put('select_raw', 'commodities.name as label, count(*) as total')
                ->put('group_by', 'commodities.name')
                ->put('sort', 'total')
                ->put('order', 'desc'),
            false
        );

        return response()->json([
            'params' => [
                'commodity' => $commodity,
                'group_by' => $groupBy,
                'geo_location_filter' => $geoLocationFilter,
                'geo_location_value' => $request->validated('geo_location_value'),
                'is_exact' => $request->validated('is_exact'),
            ],
            'group_search_institute' => $repo->getGroupByInstitute($model, $commodity, $geoLocationFilter),
            'chart_labels' => $commodities_chart,
            'chart_data' => $chart_data,
            'raw_data' => $commodities,
            'raw_data_labels' => $repo->getCommodityLabels($model, $request->validated('geo_location_value'), $request->validated('is_exact'), $geoLocationFilter),
            'group_search_labels' => $repo->getGroupByGeoLoc($model, $commodity, $geoLocationFilter),
            'linechart_data' => $repo->linechartData($model, $request->validated('geo_location_value'), $request->validated('is_exact'), $geoLocationFilter, $commodity),
        ]);
    }

    private function summaryPrivate(GetCommoditiesRequest $request): JsonResponse
    {
        /** @var CommodityRepo $repo */
        $repo = $this->service;
        $model = $repo->model;

        $geoLocationFilter = $request->validated('geo_location_filter') ?? 'region';
        $groupBy = $repo->determineLocFilterLevel($geoLocationFilter);
        $commodity = $request->input('commodity');

        $baseParams = collect([
            'geo_location_value' => $request->validated('geo_location_value'),
            'geo_location_filter' => $geoLocationFilter,
            'filter_by_parent_column' => $request->validated('filter_by_parent_column'),
            'filter_by_parent_id' => $request->validated('filter_by_parent_id'),
            'filter' => $request->validated('filter'),
            'search' => $request->validated('search') ?? '',
            'with' => $request->validated('with') ?? '',
            'is_exact' => $request->validated('is_exact'),
            'commodity' => $commodity,
            'paginate' => false,
        ]);

        $commodities = $repo->search(
            $baseParams->put('select', implode(',', $model->getSearchable())),
            false
        );

        $chart_data = $repo->search(
            $baseParams
                ->put('select_raw', "$groupBy as label, count(*) as total")
                ->put('group_by', $groupBy)
                ->put('sort', 'total')
                ->put('order', 'desc'),
            false
        );

        $commodities_chart = $repo->search(
            $baseParams
                ->put('select_raw', 'commodities.name as label, count(*) as total')
                ->put('group_by', 'commodities.name')
                ->put('sort', 'total')
                ->put('order', 'desc'),
            false
        );

        return response()->json([
            'params' => [
                'commodity' => $commodity,
                'group_by' => $groupBy,
                'geo_location_filter' => $geoLocationFilter,
                'geo_location_value' => $request->validated('geo_location_value'),
                'is_exact' => $request->validated('is_exact'),
            ],
            'group_search_institute' => $repo->getGroupByInstitute($model, $commodity, $geoLocationFilter),
            'chart_labels' => $commodities_chart,
            'chart_data' => $chart_data,
            'raw_data' => $commodities,
            'raw_data_labels' => $repo->getCommodityLabels($model, $request->validated('geo_location_value'), $request->validated('is_exact'), $geoLocationFilter),
            'group_search_labels' => $repo->getGroupByGeoLoc($model, $commodity, $geoLocationFilter),
            'linechart_data' => $repo->linechartData($model, $request->validated('geo_location_value'), $request->validated('is_exact'), $geoLocationFilter, $commodity),
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
                'value' => $key,
                'label' => $key. ' (' . $value . ')',
                'sName' => $value
            ];
        }
        return $this->sendResponse($formatted);
    }

    public function approve(ApproveCommoditiesRequest $request, int $id): JsonResponse
    {
        // Authorization is handled by the FormRequest
        return $this->service->update($id, ['approved_at' => now()]);
    }

    public function disapprove(ApproveCommoditiesRequest $request, int $id): JsonResponse
    {
        // Authorization is handled by the FormRequest
        return $this->service->update($id, ['approved_at' => null]);
    }
}
