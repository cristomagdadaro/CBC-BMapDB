<?php
namespace App\Http\Interfaces;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface AbstractRepoServiceInterface {

    /**
     * Determines the location filter level based on the given geolocation filter.
     *
     * @param string $geo_location_filter The geolocation filter to determine the level.
     * @return string The corresponding location filter level.
     */
    public function determineLocFilterLevel(string $geo_location_filter): string;

    /**
     * Get all data
     * @return Collection
     **/
    public function all(): Collection;

    /**
     * Create new data
     * @param array $data
     * @return JsonResponse
     **/
    public function create(array $data): JsonResponse;

    /**
     * Update data
     * @param int $id model primary key
     * @param array $data updated set of data
     * @return JsonResponse
     **/
    public function update(int $id, array $data): JsonResponse;

    /**
     * Delete data
     * @param int $id model primary key
     * @return JsonResponse
     **/
    public function delete(int $id): JsonResponse;

    /**
     * Perform multiple model deletion
     * @param array $ids model primary key
     * @return JsonResponse
     **/
    public function multiDestroy(array $ids): JsonResponse;

    /**
     * Retrieve a model by its primary key.
     *
     * @param int $id Model primary key.
     * @return JsonResponse | Model
     *
     * @throw Exception
     */
    public function find(int $id): JsonResponse|BaseModel;

    /**
     * Data filtering
     * @param Collection $parameters search parameters
     * @param bool $withPagination
     * @param bool $isTrashed
     */
    public function search(Collection $parameters, bool $withPagination = true, bool $isTrashed = false);


}
