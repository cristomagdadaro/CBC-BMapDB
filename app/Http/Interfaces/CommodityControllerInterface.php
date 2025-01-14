<?php
namespace App\Http\Interfaces;

use App\Http\Requests\CreateCommoditiesRequest;
use App\Http\Requests\DeleteCommoditiesRequest;
use App\Http\Requests\GetCommoditiesRequest;
use App\Http\Requests\UpdateCommoditiesRequest;
use App\Http\Resources\BaseCollection;
use Illuminate\Http\JsonResponse;

interface CommodityControllerInterface {

    /**
     * Retrieves a paginated list of commodities based on the provided request parameters.
     *
     * @param GetCommoditiesRequest $request The request object containing the search parameters.
     * @return BaseCollection A JSON response containing the paginated commodities.
     */
    public function index(GetCommoditiesRequest $request): BaseCollection;

    /**
     * Retrieves a single commodity based on the provided ID.
     *
     * @param GetCommoditiesRequest $request The request object containing the search parameters.
     * @param int $id The ID of the commodity to retrieve.
     * @return JsonResponse A JSON response containing the requested commodity.
     */
    public function show(GetCommoditiesRequest $request, int $id): JsonResponse;

    /**
     * Stores a new commodity record in the database.
     *
     * @param CreateCommoditiesRequest $request The request object containing the validated data for creating a new commodity.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *                      On success, the response will contain the created commodity data.
     *                      On failure, the response will contain an error message.
     */
    public function store(CreateCommoditiesRequest $request): JsonResponse;

    /**
     * Retrieves a paginated list of commodities based on the provided request parameters,
     * but without pagination.
     *
     * @param GetCommoditiesRequest $request The request object containing the search parameters.
     * @return BaseCollection A JSON response containing the commodities without pagination.
     */
    public function noPage(GetCommoditiesRequest $request): BaseCollection;

    /**
     * Updates an existing commodity record in the database.
     *
     * @param UpdateCommoditiesRequest $request The request object containing the validated data for updating an existing commodity.
     * @param int $id The ID of the commodity to update.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *                      On success, the response will contain the updated commodity data.
     *                      On failure, the response will contain an error message.
     */
    public function update(UpdateCommoditiesRequest $request, int $id): JsonResponse;

    /**
     * Deletes a commodity record from the database based on the provided ID.
     *
     * @param int $id The ID of the commodity to delete.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *                      On success, the response will contain a success message.
     *                      On failure, the response will contain an error message.
     */
    public function destroy(int $id): JsonResponse;

    /**
     * Deletes multiple commodity records from the database based on the provided IDs.
     *
     * @param DeleteCommoditiesRequest $request The request object containing the validated data for deleting multiple commodities.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *                      On success, the response will contain a success message.
     *                      On failure, the response will contain an error message.
     */
    public function multiDestroy(DeleteCommoditiesRequest $request): JsonResponse;

    /**
     * Retrieves summary data for commodities based on the provided request parameters.
     *
     * @param GetCommoditiesRequest $request The request object containing the search parameters.
     * @return JsonResponse A JSON response containing the summary data.
     *                      The response includes parameters, chart labels, chart data, raw data,
     *                      raw data labels, group search labels, and linechart data.
     */
    public function summary(GetCommoditiesRequest $request): JsonResponse;
}
