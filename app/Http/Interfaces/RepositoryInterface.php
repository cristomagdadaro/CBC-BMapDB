<?php

namespace App\Http\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    /**
     * Data filtering
     * @param Collection $parameters search parameters
     * @param bool $withPagination include pagination
     * @return Collection
     **/
    public function search(Collection $parameters, bool $withPagination = true);

    /**
     * Get all data
     * @return Collection
     **/
    public function all();

    /**
     * Retrieve a model
     * @param int $id model primary key
     **/
    public function find(int $id);

    /**
     * Create new data
     * @param array $data new set of data
     * @return JsonResponse
     **/
    public function create(array $data);

    /**
     * Update data
     * @param int $id model primary key
     * @param array $data updated set of data
     * @return JsonResponse
     **/
    public function update(int $id, array $data);

    /**
     * Delete data
     * @param int $id model primary key
     * @return JsonResponse
     **/
    public function delete(int $id);

    /**
     * Perform multiple model deletion
     * @param array $ids model primary key
     * @return JsonResponse
     **/
    public function multiDestroy(array $ids);

    /**
     * Append related models
     * @param array $tableToAppend related models
     **/
    public function appendWith(array $tableToAppend): void;

    /**
     * Append count of related models
     * @param array $countTable related models
     **/
    public function appendCount(array $countTable): void;

    /**
     * Filter by parent model
     * @param array|null $parent parent model
     **/
    public function filterByParent(array|null $parent): void;

    /**
     * Append condition to query
     * @param int $tableConditions condition to append
     **/
    public function appendCondition(int $tableConditions): void;

    /**
     * Get summary of data
     * @return int
     **/
    public function summary(): int;

    /**
     * Determines the location filter level based on the given geolocation filter.
     *
     * @param string $geo_location_filter The geolocation filter to determine the level.
     * @return string The corresponding location filter level.
     */
    public function determineLocFilterLevel(string $geo_location_filter);

}
