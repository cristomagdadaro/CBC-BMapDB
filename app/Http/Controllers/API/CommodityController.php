<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateCommoditiesRequest;
use App\Http\Requests\DeleteCommoditiesRequest;
use App\Http\Requests\GetCommoditiesRequest;
use App\Http\Requests\UpdateCommoditiesRequest;
use App\Http\Resources\BaseCollection;
use App\Repository\API\CommodityRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CommodityController extends BaseController
{
    public function __construct(CommodityRepo $commodityRepository)
    {
        $this->service = $commodityRepository;
    }

    /**
     * Retrieves a paginated list of commodities based on the provided request parameters.
     *
     * @param GetCommoditiesRequest $request The request object containing the search parameters.
     * @param int|null $parent_id The ID of the parent commodity (optional, defaults to null).
     * @return BaseCollection A JSON response containing the paginated commodities.
     */
    public function index(GetCommoditiesRequest $request, int|null $parent_id = null): BaseCollection
    {
        $this->service->appendWith(['breeder', 'location']);
        $this->service->filterByParent(['column' => 'breeder_id', 'value' => $parent_id]);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    /**
     * Retrieves a single commodity based on the provided ID.
     *
     * @param GetCommoditiesRequest $request The request object containing the search parameters.
     * @param int $id The ID of the commodity to retrieve.
     * @return BaseCollection A JSON response containing the requested commodity.
     */
    public function show(GetCommoditiesRequest $request, int $id): BaseCollection
    {
        $this->service->appendWith(['breeder', 'location']);

        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    /**
     * Stores a new commodity record in the database.
     *
     * @param CreateCommoditiesRequest $request The request object containing the validated data for creating a new commodity.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *                      On success, the response will contain the created commodity data.
     *                      On failure, the response will contain an error message.
     */
    public function store(CreateCommoditiesRequest $request): JsonResponse
    {
        return $this->service->create($request->validated());
    }

    /**
     * Retrieves a paginated list of commodities based on the provided request parameters,
     * but without pagination.
     *
     * @param GetCommoditiesRequest $request The request object containing the search parameters.
     * @return BaseCollection A JSON response containing the commodities without pagination.
     */
    public function noPage(GetCommoditiesRequest $request): BaseCollection
    {
        $this->service->appendWith(['breeder','cityDesc']);
        $data = $this->service->search(new Collection($request->validated()), false);
        return new BaseCollection($data);
    }

    /**
     * Updates an existing commodity record in the database.
     *
     * @param UpdateCommoditiesRequest $request The request object containing the validated data for updating an existing commodity.
     * @param int $id The ID of the commodity to update.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *                      On success, the response will contain the updated commodity data.
     *                      On failure, the response will contain an error message.
     */
    public function update(UpdateCommoditiesRequest $request, int $id): JsonResponse
    {
        return $this->service->update($id, $request->validated());
    }

    /**
     * Deletes a commodity record from the database based on the provided ID.
     *
     * @param int $id The ID of the commodity to delete.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *                      On success, the response will contain a success message.
     *                      On failure, the response will contain an error message.
     */
    public function destroy(int $parent_id = null, int $id): JsonResponse
    {
        return $this->service->delete($id);
    }

    /**
     * Deletes multiple commodity records from the database based on the provided IDs.
     *
     * @param DeleteCommoditiesRequest $request The request object containing the validated data for deleting multiple commodities.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *                      On success, the response will contain a success message.
     *                      On failure, the response will contain an error message.
     */
    public function multiDestroy(DeleteCommoditiesRequest $request): JsonResponse
    {
        return $this->service->multiDestroy($request->validated());
    }

    /**
     * Retrieves summary data for commodities based on the provided request parameters.
     *
     * @param GetCommoditiesRequest $request The request object containing the search parameters.
     * @return JsonResponse A JSON response containing the summary data.
     *                      The response includes parameters, chart labels, chart data, raw data,
     *                      raw data labels, group search labels, and linechart data.
     */
    public function summary(GetCommoditiesRequest $request): JsonResponse
    {
        $model = $this->service->model;
        $geo_location_filter = $request->validated('geo_location_filter') ?? 'region';
        $geo_location_value = $request->validated('geo_location_value');
        $is_exact = $request->validated('is_exact');
        $commodity = $request->all()['commodity'] ?? null;
        $group_by = $this->service->determineLocFilterLevel($geo_location_filter);

        $commodities = $this->service->applyFilters($model, $commodity, $geo_location_value, $geo_location_filter)
            ->select($model->getSearchable())
            ->with(['breeder','location'])
            ->get();
        $chart_data = $this->service->applyFilters($model, $commodity, $geo_location_value, $geo_location_filter)
            ->selectRaw("$group_by as label, count(*) as total")
            ->groupBy($group_by)
            ->orderBy('total', 'desc')
            ->get();
        $commodities_chart = $this->service->applyFilters($model, $commodity, $geo_location_value, $geo_location_filter)
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
}
