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

class CommodityController extends BaseController implements CommodityControllerInterface
{
    public function __construct(CommodityRepo $commodityRepository)
    {
        $this->service = $commodityRepository;
    }

    public function index(GetCommoditiesRequest $request): BaseCollection
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show(GetCommoditiesRequest $request, int $id): JsonResponse
    {
        $with = $request->toArray()['with'] ?? null;
        $count = $request->toArray()['count'] ?? null;

        if ($with)
            $this->service->appendWith(explode(',',$with));
        if ($count)
            $this->service->appendCount(explode(',',$count));

        return $this->sendResponse($this->service->find($id));
    }

    public function store(CreateCommoditiesRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        return $this->service->create($data);
    }

    public function update(UpdateCommoditiesRequest $request, int $id): JsonResponse
    {
        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');
        return $this->service->update($id, $data);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteCommoditiesRequest $request): JsonResponse
    {
        return $this->service->multiDestroy($request->validated());
    }

    public function summary(GetCommoditiesRequest $request): JsonResponse
    {
        $model = $this->service->model;
        $geo_location_filter = $request->validated('geo_location_filter') ?? 'region';
        $geo_location_value = $request->validated('geo_location_value');
        $is_exact = $request->validated('is_exact');
        $commodity = $request->all()['commodity'] ?? null;
        $filter_by_parent_column = $request->validated('filter_by_parent_column');
        $filter_by_parent_id = $request->validated('filter_by_parent_id');
        $group_by = $this->service->determineLocFilterLevel($geo_location_filter);

        $commodities = $this->service->applyFilters($model, $commodity, $geo_location_value, $geo_location_filter, $filter_by_parent_column, $filter_by_parent_id)
            ->select($model->getSearchable())
            ->with(['breeder','location'])
            ->get();
        $chart_data = $this->service->applyFilters($model, $commodity, $geo_location_value, $geo_location_filter, $filter_by_parent_column, $filter_by_parent_id)
            ->selectRaw("$group_by as label, count(*) as total")
            ->groupBy($group_by)
            ->orderBy('total', 'desc')
            ->get();
        $commodities_chart = $this->service->applyFilters($model, $commodity, $geo_location_value, $geo_location_filter, $filter_by_parent_column, $filter_by_parent_id)
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
            'raw_data_labels' => $this->service->getCommodityLabels($model, $geo_location_value, $is_exact, $geo_location_filter),
            'group_search_labels' => $this->service->getGroupByGeoLoc($model, $commodity, $geo_location_filter),
            'linechart_data' => $this->service->linechartData($model, $geo_location_value, $is_exact, $geo_location_filter, $commodity),
        ]);
    }

    public function noPage(GetCommoditiesRequest $request): BaseCollection
    {
        $this->service->appendWith(['breeder','cityDesc']);
        $data = $this->service->search(new Collection($request->validated()), false);
        return new BaseCollection($data);
    }
}
