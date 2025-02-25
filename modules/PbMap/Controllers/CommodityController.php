<?php

namespace Modules\PbMap\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Resources\BaseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Modules\PbMap\Filters\CommodityFilter;
use Modules\PbMap\Interfaces\CommodityControllerInterface;
use Modules\PbMap\Repositories\CommodityRepo;
use Modules\PbMap\Requests\CreateCommoditiesRequest;
use Modules\PbMap\Requests\DeleteCommoditiesRequest;
use Modules\PbMap\Requests\GetCommoditiesRequest;
use Modules\PbMap\Requests\UpdateCommoditiesRequest;

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
        if (auth()->check())
            return $this->summaryPrivate($request);
        return $this->summaryPublic($request);
    }

    /*public function summaryPublic(GetCommoditiesRequest $request): JsonResponse
    {
        $model = $this->service->model;
        $geo_location_filter = $request->validated('geo_location_filter') ?? null;
        $filter = new CommodityFilter(
            $geo_location_filter ? $request->validated('geo_location_value') : null,
            $geo_location_filter,
            $request->validated('filter_by_parent_column'),
            $request->validated('filter_by_parent_id'),
            $request->validated('filter'),
     $request->validated('search') ?? '',
       $request->validated('with') ?? '',
            $request->validated('is_exact'),
            $request->all()['commodity'] ?? null,
            $this->service->determineLocFilterLevel($geo_location_filter),
        );

        $commodities = $this->service->search($filter->collect(), false)->get();

        $groupBy = $this->service->determineLocFilterLevel($geo_location_filter ?? 'region');
        $temp = $filter->collect()->put('select_raw', "$groupBy as label, count(*) as total");
        $temp =  $temp->put('sort', 'total');
        $temp =  $temp->put('order', 'desc');
        $temp =  $temp->put('group_by', $groupBy);
        $chart_data = $this->service->search($temp, false)->get();

        $temp = $filter->collect()->put('select_raw', 'commodities.name as label, count(*) as total');
        $temp =  $temp->put('group_by', 'commodities.name');
        $temp =  $temp->put('sort', 'total');
        $temp =  $temp->put('order', 'desc');
        $commodities_chart = $this->service->search($temp, false)->get();

        $geo_location_filter = $geo_location_filter ?? 'region';

        return response()->json([
            'params' => [
                'commodity' => $filter->collect()->get('commodities'),
                'group_by' => $filter->collect()->get('group_by'),
                'geo_location_filter' => $geo_location_filter,
                'geo_location_value' => $filter->collect()->get('geo_location_value'),
                'is_exact' => $filter->collect()->get('is_exact'),
            ],
            'group_search_institute' => $this->service->getGroupByInstitute($model, $filter->collect()->get('commodities'), $geo_location_filter),
            'chart_labels' => $commodities_chart,
            'chart_data' => $chart_data,
            'raw_data' => $commodities,
            'raw_data_labels' => $this->service->getCommodityLabels($model, $filter->collect()->get('geo_location_value'), $filter->collect()->get('is_exact'), $geo_location_filter),
            'group_search_labels' => $this->service->getGroupByGeoLoc($model, $filter->collect()->get('commodities'), $geo_location_filter),
            'linechart_data' => $this->service->linechartData($model, $filter->collect()->get('geo_location_value'), $filter->collect()->get('is_exact'), $geo_location_filter, $filter->collect()->get('commodities')),
        ]);
    }*/

    public function summaryPublic(GetCommoditiesRequest $request): JsonResponse
    {
        $model = $this->service->model;

        $filter = new CommodityFilter(
            $request->validated('geo_location_value'),
            $request->validated('geo_location_filter') ?? 'region',
            $request->validated('filter_by_parent_column'),
            $request->validated('filter_by_parent_id'),
            $request->validated('filter'),
            $request->validated('search') ?? '',
            $request->validated('with') ?? '',
            $request->validated('is_exact'),
            $request->all()['commodity'] ?? null,
            $this->service->determineLocFilterLevel($request->validated('geo_location_filter') ?? 'region'),
        );

        $temp = $filter->collect();

        $builder = $model->newModelQuery();
        $this->service->applyParentFilter($builder, $temp);
        $this->service->applyGeoFilters($builder, $temp);
        $this->service->applyAppends($builder, $temp);
        $this->service->applySearchFilters($builder, $temp);
        $builder = $builder->when($filter->commodities, function ($query) use ($filter) {
            return $query->where('name', $filter->commodities);
        });

        $builderA = (clone $builder);

        $commodities = $builderA->select($model->getSearchable())->get();

        $groupBy = $this->service->determineLocFilterLevel($geo_location_filter ?? 'region');
        $temp = $filter->collect()->put('select_raw', "$groupBy as label, count(*) as total");
        $temp =  $temp->put('group_by', $groupBy);
        $temp =  $temp->put('sort', 'total');
        $temp =  $temp->put('order', 'desc');

        $builderB = (clone $builder)->selectRaw("$groupBy as label, count(*) as total");
        $this->service->applySorting($builderB, $temp);
        $chart_data = $builderB->groupBy($groupBy)->get();

        $builderC = (clone $builder)->selectRaw('commodities.name as label, count(*) as total');
        $this->service->applySorting($builderC, $temp);
        $commodities_chart = $builderC->groupBy('commodities.name')->get();

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

    public function summaryPrivate(GetCommoditiesRequest $request): JsonResponse
    {
        $model = $this->service->model;

        $filter = new CommodityFilter(
            $request->validated('geo_location_value'),
            $request->validated('geo_location_filter') ?? 'region',
            $request->validated('filter_by_parent_column'),
            $request->validated('filter_by_parent_id'),
            $request->validated('filter'),
            $request->validated('search') ?? '',
            $request->validated('with') ?? '',
            $request->validated('is_exact'),
            $request->all()['commodity'] ?? null,
            $this->service->determineLocFilterLevel($request->validated('geo_location_filter') ?? 'region'),
        );

        $temp = $filter->collect();

        $builder = $model->newModelQuery();
        $this->service->applyParentFilter($builder, $temp);
        $this->service->applyGeoFilters($builder, $temp);
        $this->service->applyAppends($builder, $temp);
        $this->service->applySearchFilters($builder, $temp);
        $builder = $builder->when($filter->commodities, function ($query) use ($filter) {
            return $query->where('name', $filter->commodities);
        });

        $builderA = (clone $builder);

        $commodities = $builderA->select($model->getSearchable())->get();

        $groupBy = $this->service->determineLocFilterLevel($geo_location_filter ?? 'region');
        $temp = $filter->collect()->put('select_raw', "$groupBy as label, count(*) as total");
        $temp =  $temp->put('group_by', $groupBy);
        $temp =  $temp->put('sort', 'total');
        $temp =  $temp->put('order', 'desc');

        $builderB = (clone $builder)->selectRaw("$groupBy as label, count(*) as total");
        $this->service->applySorting($builderB, $temp);
        $chart_data = $builderB->groupBy($groupBy)->get();

        $builderC = (clone $builder)->selectRaw('commodities.name as label, count(*) as total');
        $this->service->applySorting($builderC, $temp);
        $commodities_chart = $builderC->groupBy('commodities.name')->get();

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
