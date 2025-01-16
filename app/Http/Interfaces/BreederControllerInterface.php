<?php

namespace App\Http\Interfaces;

use App\Http\Requests\CreateBreederRequest;
use App\Http\Requests\DeleteBreederRequest;
use App\Http\Requests\GetBreederRequest;
use App\Http\Requests\UpdateBreederRequest;
use App\Http\Resources\BaseCollection;
use Illuminate\Http\JsonResponse;

interface BreederControllerInterface {

    /**
     * Retrieves a paginated list of breeders based on the provided filters.
     *
     * @param GetBreederRequest $request The request object containing the filters.
     * @return BaseCollection A collection of paginated breeder data.
     */
    public function index(GetBreederRequest $request): BaseCollection;

    /**
     * Retrieves a single breeder record based on the provided ID.
     *
     * @param int $id The unique identifier of the breeder record to retrieve.
     * @return JsonResponse A collection containing the requested breeder data.
     */
    public function show(GetBreederRequest $request, int $id): JsonResponse;

    /**
     * Stores a new breeder record in the database. User account is also created with a default role of breeder in the system.
     *
     * @param CreateBreederRequest $request The request object containing the breeder data.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     */
    public function store(CreateBreederRequest $request): JsonResponse;

    /**
     * Retrieves commodities associated with a specific breeder record without pagination.
     *
     * @param int $id The unique identifier of the breeder record.
     * @param GetBreederRequest $request The request object containing the filters.
     * @return JsonResponse A JSON response containing the commodities associated with the breeder.
     *     If no data is found, a 404 status code with a 'Data not found' message is returned.
     */
    public function noPage(int $id, GetBreederRequest $request): JsonResponse;

    /**
     * Updates an existing breeder record in the database.
     *
     * @param UpdateBreederRequest $request The request object containing the updated breeder data.
     * @param int $id The unique identifier of the breeder record to update.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *     If the operation is successful, the response will contain the updated breeder data.
     *     If the operation fails, the response will contain an error message.
     */
    public function update(UpdateBreederRequest $request, int $id): JsonResponse;

    /**
     * Deletes a specific breeder record from the database.
     *
     * @param int $id The unique identifier of the breeder record to delete.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *     If the operation is successful, the response will contain a success message.
     *     If the operation fails, the response will contain an error message.
     */
    public function destroy(int $id): JsonResponse;

    /**
     * Deletes multiple breeder records from the database based on the provided IDs.
     *
     * @param DeleteBreederRequest $request The request object containing the IDs of the breeder records to delete.
     * @return JsonResponse A JSON response indicating the success or failure of the operation.
     *     If the operation is successful, the response will contain a success message.
     *     If the operation fails, the response will contain an error message.
     */
    public function multiDestroy(DeleteBreederRequest $request): JsonResponse;

    /**
     * Retrieves summary data for breeders based on the provided filters.
     *
     * @param GetBreederRequest $request The request object containing the filters.
     * @return JsonResponse A JSON response containing the summary data.
     *     The response includes parameters, group search labels, group search institute, raw data,
     *     raw data labels, chart data, chart labels, and linechart data.
     */
    public function summary(GetBreederRequest $request): JsonResponse;
}
