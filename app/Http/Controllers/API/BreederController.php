<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetBreederRequest;
use App\Http\Requests\CreateBreederRequest;
use App\Http\Requests\UpdateBreederRequest;
use App\Http\Requests\DeleteBreederRequest;
use App\Repository\API\BreederRepo;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BaseCollection;

class BreederController extends BaseController
{
    public function __construct(BreederRepo $breederRepository)
    {
        $this->service = $breederRepository;
    }

    /**
     * Retrieves a paginated list of breeders based on the provided filters.
     *
     * @param GetBreederRequest $request The request object containing the filters.
     * @param int|null $parent_id The optional parent ID for filtering.
     * @return BaseCollection A collection of paginated breeder data.
     */
    public function index(GetBreederRequest $request, int|null $parent_id = null): BaseCollection
    {
        $this->service->appendWith(['affiliated', 'location', 'commodities']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    /**
     * Retrieves a single breeder record based on the provided ID.
     *
     * @param GetBreederRequest $request The request object containing the filters.
     * @param int $id The unique identifier of the breeder record to retrieve.
     * @return BaseCollection A collection containing the requested breeder data.
     */
    public function show(GetBreederRequest $request, int $id): BaseCollection
    {
        $this->service->appendWith(['affiliated', 'location']);
        $this->service->appendCount(['commodities']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    /**
     * Stores a new breeder record in the database.
     *
     * @param CreateBreederRequest $request The request object containing the breeder data.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     */
    public function store(CreateBreederRequest $request): JsonResponse
    {
        // Merge validated request data with user_id and create a new breeder record
        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);

        // Call the create method of the service to store the breeder data
        return $this->service->create($data);
    }


    /**
     * Retrieves commodities associated with a specific breeder record without pagination.
     *
     * @param int $id The unique identifier of the breeder record.
     * @param GetBreederRequest $request The request object containing the filters.
     * @return JsonResponse A JSON response containing the commodities associated with the breeder.
     *     If no data is found, a 404 status code with a 'Data not found' message is returned.
     */
    public function noPage(int $id, GetBreederRequest $request): JsonResponse
    {
        $this->service->appendWith(['commodities']);
        $data = $this->service->search(new Collection($request->validated()), false);
        if (count($data) === 0) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json(['data' => $data[0]->commodities]);
    }

    /**
     * Updates an existing breeder record in the database.
     *
     * @param UpdateBreederRequest $request The request object containing the updated breeder data.
     * @param int $id The unique identifier of the breeder record to update.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *     If the operation is successful, the response will contain the updated breeder data.
     *     If the operation fails, the response will contain an error message.
     */
    public function update(UpdateBreederRequest $request, int $id): JsonResponse
    {
        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');
        return $this->service->update($id, $data);
    }

    /**
     * Deletes a specific breeder record from the database.
     *
     * @param int $id The unique identifier of the breeder record to delete.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *     If the operation is successful, the response will contain a success message.
     *     If the operation fails, the response will contain an error message.
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->service->delete($id);
    }

    /**
     * Deletes multiple breeder records from the database based on the provided IDs.
     *
     * @param DeleteBreederRequest $request The request object containing the IDs of the breeder records to delete.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *     If the operation is successful, the response will contain a success message.
     *     If the operation fails, the response will contain an error message.
     */
    public function multiDestroy(DeleteBreederRequest $request): JsonResponse
    {
        return $this->service->multiDestroy($request->validated());
    }

    /**
     * Retrieves summary data for breeders based on the provided filters.
     *
     * @param GetBreederRequest $request The request object containing the filters.
     * @return JsonResponse A JSON response containing the summary data.
     *     The response includes parameters, group search labels, group search institute, raw data,
     *     raw data labels, chart data, chart labels, and linechart data.
     */
    public function summary(GetBreederRequest $request): JsonResponse
    {
        $model = $this->service->model;
        $geo_location_filter = $request->validated('geo_location_filter') ?? 'region';
        $geo_location_value = $request->validated('geo_location_value') ?? '';
        $is_exact = $request->validated('is_exact');
        $commodity = $request->all()['commodity'] ?? null;
        $group_by = $this->service->determineLocFilterLevel($geo_location_filter);

        $breeders = $this->service->applyFilters($model, [$geo_location_filter => $geo_location_value])
            ->select($model->getSearchable())
            ->with(['location', 'commodities','affiliated'])
            ->get();
        $chart_data = $this->service->applyFilters($model, [$geo_location_filter => $geo_location_value])
            ->selectRaw("$group_by as label, count(*) as total")
            ->groupBy($group_by)
            ->orderBy('total', 'desc')
            ->get();
        $breeders_chart = $this->service->applyFilters($model, [$geo_location_filter => $geo_location_value])
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
            'group_search_labels' => $this->service->getGroupByGeoLoc($model, $commodity, $geo_location_filter),
            'group_search_institute' => $this->service->getGroupByInstitute($model, $commodity, $geo_location_filter),
            'raw_data' => $breeders,
            'raw_data_labels' => $this->service->getBreederLabels($model, $geo_location_value, $is_exact, $geo_location_filter),
            'chart_data' => $chart_data,
            'chart_labels' => $breeders_chart,
            'linechart_data' => $this->service->linechartData($model, $geo_location_value, $is_exact, $geo_location_filter),
        ]);
    }
}
