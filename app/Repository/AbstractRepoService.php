<?php

namespace App\Repository;

use App\Http\Interfaces\AbstractRepoServiceInterface;
use App\Models\ApiRequestLog;
use App\Models\BaseModel;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;

/**
 * Base repository service providing common data access helpers with
 * pagination, filtering, sorting, relation appends, and standardized
 * JSON responses.
 *
 * Notes
 * - Avoid magic strings; common keys and table/column names are centralized as constants.
 * - Responses are driven by config('responses.*') using the type keys below.
 */
abstract class AbstractRepoService implements AbstractRepoServiceInterface
{
    // Response type keys used with config('responses.*')
    public const RESPONSE_CREATED = 'created';
    public const RESPONSE_UPDATED = 'updated';
    public const RESPONSE_DELETED = 'deleted';
    public const RESPONSE_FAILURE = 'failure';
    public const RESPONSE_NOT_FOUND = 'not_found';

    // Defaults and common options
    public const DEFAULT_PER_PAGE = 10;
    public const DEFAULT_PAGE = 1;
    public const SORT_DEFAULT_ORDER = 'desc';
    public const ORDER_ASC = 'ASC';
    public const ORDER_DESC = 'DESC';

    // Table names
    public const GEO_TABLE_LOC_CITIES = 'loc_cities';
    public const GEO_TABLE_USERS = 'users';
    public const GEO_TABLE_INSTITUTES = 'institutes';

    // Column names
    public const COL_ID = 'id';
    public const COL_UUID = 'uuid';
    public const COL_NAME = 'name';
    public const COL_GEOLOCATION = 'geolocation';
    public const COL_USER_ID = 'user_id';
    public const COL_BREEDER_ID = 'breeder_id';
    public const COL_FNAME = 'fname';
    public const COL_MNAME = 'mname';
    public const COL_LNAME = 'lname';
    public const COL_SUFFIX = 'suffix';

    // Geo filter keys/columns
    public const GEO_FILTER_INSTITUTE = 'institute';
    public const LOC_COL_PROVINCE = 'provDesc';
    public const LOC_COL_REGION = 'regDesc';
    public const LOC_COL_CITY = 'cityDesc';

    // Messages
    public const MSG_NO_DATA = 'No Data Found or Already Deleted';
    public const MSG_NO_IDS = 'No IDs provided';

    /**
     * Model to be used
     * @var Model
     **/
    public Model $model;

    /**
     * Table to append with
     * @var string[]
     */
    public array $appendWith = [];

    /**
     * Count the rows of the appended tables
     * @var string[]
     */
    public array $appendCount = [];

    /**
     * Filter the data according to the parent id
     */
    protected array|null $filterByParent = null;

    /**
     * List of searchable and viewable columns
     * @var string[]
     **/
    protected array $searchable = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): JsonResponse
    {
        try {
            $model = $this->model->create($data);
            return $this->jsonResponse(self::RESPONSE_CREATED, $model);
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $model = $this->model->findOrFail($id);
            $model->update($data);
            return $this->jsonResponse(self::RESPONSE_UPDATED, $model);
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $model = $this->model->findOrFail($id);
            $model->delete();
            return $this->jsonResponse(self::RESPONSE_DELETED, $model);
        } catch (\Exception $e) {
            return $this->sendError($e);
        }
    }

    /**
     * Bulk delete by IDs.
     *
     * Accepts an array of IDs or a comma-separated string in params['ids'].
     */
    public function multiDestroy(array $params): JsonResponse
    {
        try {
            $ids = $params['ids'] ?? [];

            if (!is_array($ids)) {
                if (is_string($ids)) {
                    $ids = array_filter(array_map('trim', explode(',', $ids)));
                } else {
                    $ids = [];
                }
            }

            if (empty($ids)) {
                return $this->jsonResponse(self::RESPONSE_FAILURE, null, ['message' => self::MSG_NO_IDS]);
            }

            $deletedCount = $this->model->whereIn(self::COL_ID, $ids)->delete();

            if ($deletedCount > 0) {
                return $this->jsonResponse(self::RESPONSE_DELETED, ['count' => $deletedCount]);
            }

            return $this->jsonResponse(self::RESPONSE_FAILURE, null, ['message' => self::MSG_NO_DATA]);

        } catch (\Exception $e) {
            return $this->sendError($e);
        }
    }


    public function find(int $id, $parameters = null): JsonResponse|Model
    {
        $builder = $this->model->query();
        if ($parameters)
            $this->applyAppends($builder, $parameters);
        return $builder->findOr($id, fn() => $this->jsonResponse(self::RESPONSE_NOT_FOUND));
    }

    /**
         * Build a standardized JSON response payload from config('responses.*').
         *
         * @param string $type Response type key (see RESPONSE_* constants)
         * @param mixed $data Optional payload data
         * @param array|null $overrides Optional keys to override from the config template
         */
        public function jsonResponse(string $type, mixed $data = null, ?array $overrides = null): JsonResponse
    {
        $responseConfig = Config::get("responses.{$type}");

        if (!$responseConfig) {
            throw new \InvalidArgumentException("Invalid response type: {$type}");
        }

        $response = array_merge([
            'data' => $data,
            'show' => true,
        ], $responseConfig);

        if ($overrides !== null) {
            foreach ($overrides as $key => $value) {
                if (array_key_exists($key, $response)) {
                    $response[$key] = $value;
                }
            }
        }

        $statusCode = $responseConfig['statusCode'] ?? Response::HTTP_OK;
        return response()->json($response, $statusCode);
    }

    public function search(Collection $parameters, bool $withPagination = true, bool $isTrashed = false)
    {
        try {
            // Respect request param 'paginate' when provided (default true)
            $paginateRaw = $parameters->get('paginate', $withPagination);
            $normalized = $this->normalizeBoolean($paginateRaw);
            if (!is_null($normalized)) {
                $withPagination = $normalized;
            }

            return $this->buildSearchQuery($parameters, $withPagination, $isTrashed);
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    protected function buildSearchQuery(Collection $parameters, bool $withPagination, bool $isTrashed): LengthAwarePaginator | Builder | \Illuminate\Support\Collection
    {
        $builder = $this->checkRole($this->model);
        $builder = $this->applyRawSelectColumns($builder, $parameters);

        $this->applyAppends($builder, $parameters);
        $this->applyParentFilter($builder, $parameters);

        if ($isTrashed)
            $builder = $builder->onlyTrashed();

        $this->applyGeoFilters($builder, $parameters);
        $this->applySearchFilters($builder, $parameters);
        $this->applyGroupBy($builder, $parameters);
        $this->applySorting($builder, $parameters);

        if (!$withPagination)
            return $builder->get();
        return $this->applyPagination($builder, $parameters);
    }

    public function applyRawSelectColumns($query, Collection $parameters)
    {
        $select = $parameters->get('select_raw', null);

        if ($select) {
            return $query->selectRaw($select);
        } else if ($this->model->getSearchable()) {
            return $query->select($this->model->getSearchable());
        }

        return $query->select('*');
    }

    public function applyGroupBy(Builder &$query, Collection $parameters): void
    {
        $group_by = $parameters->get('group_by', null);

        if (is_string($group_by)) {
            $query->groupBy($group_by);
        }
    }

    /**
     * Apply geographic filters to the query builder.
     */
    public function applyGeoFilters(Builder &$query, Collection $parameters): void
    {
        $geo_location_filter = $this->determineLocFilterLevel($parameters->get('geo_location_filter'));
        $geo_location_value = $parameters->get('geo_location_value');

        if (Schema::hasColumn($this->model->getTable(), self::COL_GEOLOCATION)) {
            $query = $query->join(self::GEO_TABLE_LOC_CITIES, self::GEO_TABLE_LOC_CITIES.'.'.self::COL_ID, '=', self::COL_GEOLOCATION);
        }
        if (Schema::hasColumn($this->model->getTable(), self::COL_USER_ID)) {
            $query = $query->join(self::GEO_TABLE_USERS, self::GEO_TABLE_USERS.'.'.self::COL_ID, '=', self::COL_USER_ID);
        }

        // to refactor, breeder_id should not be explicitly specified
        if (Schema::hasColumn($this->model->getTable(), self::COL_BREEDER_ID) && $geo_location_filter === self::GEO_FILTER_INSTITUTE) {
            $query = $query->with(['breeder']);
        }

        if ($geo_location_value) {
            if ($geo_location_filter !== self::GEO_FILTER_INSTITUTE) {
                // Check if the column exists before applying the filter
                if (Schema::hasColumn(self::GEO_TABLE_LOC_CITIES, $geo_location_filter)) {
                    $query = $query->where(self::GEO_TABLE_LOC_CITIES.'.' . $geo_location_filter, $geo_location_value);
                }
            } else {
                // Institute filter via breeder relationship
                if (Schema::hasColumn(self::GEO_TABLE_INSTITUTES, self::COL_NAME)) {
                    $tableDotName = self::GEO_TABLE_INSTITUTES.'.'.self::COL_NAME;
                    $query = $query->whereHas('breeder.affiliated', function ($instituteQuery) use ($geo_location_value, $tableDotName) {
                        $instituteQuery->where($tableDotName, $geo_location_value);
                    });
                }
            }
        }
    }


    /**
     * Apply pagination with config-driven defaults and safe fallbacks.
     */
    protected function applyPagination(Builder $query, Collection $parameters)
    {
        $perPageRaw = $parameters->get('per_page', Config::get('app.pagination_per_page', self::DEFAULT_PER_PAGE));
        $page = (int) $parameters->get('page', Config::get('app.pagination_page', self::DEFAULT_PAGE));

        // If per_page is '*', return all rows on a single page
        if (is_string($perPageRaw) && trim($perPageRaw) === '*') {
            // Clone the builder for a safe count without affecting the original
            $total = (clone $query)->count();
            $perPage = max(1, (int) $total);
            $page = 1; // normalize to first page
            return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
        }

        $perPage = (int) $perPageRaw;
        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function applyAppends(Builder &$model, Collection $parameters): void
    {
        $with = $parameters->get('with', null);
        $count = $parameters->get('count', null);

        if (is_string($with)) {
            $this->appendWith = explode(',', $with);
        }

        if (is_string($count)) {
            $this->appendCount = explode(',', $count);
        }

        if ($this->appendWith) {
            $model = $model->with($this->appendWith);
        }

        if ($this->appendCount) {
            $model = $model->withCount($this->appendCount);
        }
    }

    public function applyParentFilter(Builder &$query, Collection $parameters): void
    {
        $filterByParentColumn = $parameters->get('filter_by_parent_column');
        $filterByParentId = $parameters->get('filter_by_parent_id');

        if (!empty($filterByParentColumn) && !empty($filterByParentId)) {
            $query = $query->where($filterByParentColumn, $filterByParentId);
        }
    }

    public function applySearchFilters(Builder &$query, Collection $parameters): void
    {
        $isExact = $parameters->get('is_exact', false);
        $filter = $parameters->get('filter', null);
        $searchTerm = $parameters->get('search', '');

        if (empty($searchTerm)) return;

        // Apply search on the main model
        $this->applySearch($query, $searchTerm, $filter, $isExact);

        // Apply search on related models if specified
        foreach ($this->appendWith as $relation) {
            $relatedModel = $this->model->{$relation}()->getModel();
            if (!is_null($relatedModel) && $searchTerm) {
                $this->applyRelationSearch($query, $searchTerm, $filter, $isExact, $relation, $relatedModel);
            }
        }
    }

    protected function applySearch(Builder $query, string $search, ?string $filter, bool $is_exact): void
    {
        if (empty($search)) {
            return;
        }

        // Apply search to a specific column if filter is provided
        if ($filter) {
            if (str_contains($filter, '.')) {
                $filter = explode('.', $filter)[1]; // Extract the column name if filter contains a relation
            }
            $query->where($filter, 'like', "%{$search}%");
            return;
        }

        // Retrieve searchable columns
        $columns = collect($query->getModel()->getSearchable());
        if ($columns->isEmpty()) {
            return;
        }

        // Handle full name search
        if ($columns->contains(self::COL_FNAME) && $columns->contains(self::COL_LNAME)) {
            $query->orWhereRaw("CONCAT_WS(' ', fname, mname, lname, suffix) LIKE ?", ["%{$search}%"]);
            return;
        }

        // Handle specific "name" column search
        if ($filter === self::COL_NAME) {
            $query->where(self::COL_NAME, 'like', "%{$search}%");
            return;
        }

        // Apply search to all searchable columns
        $query->where(function ($subQuery) use ($columns, $search, $is_exact) {
            foreach ($columns as $column) {
                $operator = $is_exact ? '=' : 'like';
                $value = $is_exact ? $search : "%{$search}%";
                $subQuery->orWhere($column, $operator, $value);
            }
        });
    }

    protected function applyRelationSearch(Builder $query, string $search, ?string $filter, bool $is_exact, string $relation, $relatedModel): void
    {
        $query->orWhereHas($relation, function ($query) use ($search, $filter, $is_exact, $relatedModel) {
            if (str_contains($filter, '.')) {
                $temp = explode('.', $filter);
                $filter = $temp[1];
            }

            // Get related table name
            $table = $query->getModel()->getTable();
            $searchable = Schema::getColumnListing($table);

            $query->where(function ($query) use ($search, $searchable, $is_exact, $table, $filter) {
                if (($filter === self::COL_NAME && in_array(self::COL_FNAME, $searchable) && in_array(self::COL_LNAME, $searchable) || $table === self::GEO_TABLE_USERS)) {
                    $query->orWhereRaw("CONCAT_WS(' ', fname, mname, lname, suffix) LIKE ?", ["%{$search}%"]);
                } else if ($filter) {
                    if ($is_exact) {
                        $query->orWhere($filter, $search);
                    } else {
                        $query->orWhere($filter, 'like', "%{$search}%");
                    }
                } else {
                    foreach ($searchable as $column) {
                        if (Schema::hasColumn($table, $column))
                            if ($is_exact) {
                                $query->orWhere($column, $search);
                            } else {
                                $query->orWhere($column, 'like', "%{$search}%");
                            }
                    }
                }
            });
        });
    }



    /**
     * Apply sorting by validated column and order. Falls back to ID/UUID if needed.
     */
        public function applySorting(Builder &$query, Collection $parameters): void
    {
        $sortColumn = $parameters->get('sort', null);
        $order = strtoupper($parameters->get('order', self::SORT_DEFAULT_ORDER));

        if (!$sortColumn || !is_string($sortColumn)) return;

        // Validate the sort column exists to prevent SQL errors
        $table = $query->getModel()->getTable();
        if (!Schema::hasColumn($table, $sortColumn)) {
            $selectedColumns = $query->getQuery()->getColumns() ? $query->getQuery()->getColumns()[0] : ''; // Get selected columns from query
            $selectedInQuery = is_string($selectedColumns) && str_contains($selectedColumns, $sortColumn);

            if (!$selectedInQuery) {
                if (Schema::hasColumn($query->getModel()->getTable(), self::COL_ID)) {
                    // Default to table ID if it exists
                    $sortColumn = $table.'.'.self::COL_ID;
                } else {
                    // Default to UUID if no valid column is found
                    $sortColumn = self::COL_UUID;
                }
            }
        }

        if (in_array($order, [self::ORDER_ASC, self::ORDER_DESC], true)) {
            $query->orderBy($sortColumn, $order);
        } else {
            $query->orderBy($sortColumn, self::SORT_DEFAULT_ORDER); // Fallback to descending order
        }
    }

    /**
         * Quick summary count of the model records.
         */
        public function summary(): int
    {
        try {
            return $this->model->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Normalize incoming geo_location_filter to the corresponding column/key.
     */
    public function determineLocFilterLevel($geo_location_filter): string|null
    {
        return match ($geo_location_filter) {
            self::GEO_FILTER_INSTITUTE => self::GEO_FILTER_INSTITUTE,
            'province' => self::LOC_COL_PROVINCE,
            'region' => self::LOC_COL_REGION,
            'city' => self::LOC_COL_CITY,
            default => null,
        };
    }

    /**
     * Log the exception and wrap it into an ErrorRepository for unified error handling.
     *
     * @throws ErrorRepository
     */
    public function sendError(Exception $error)
    {
        Log::error('Error occurred: ' . $error->getMessage(), ['exception' => $error]);
        throw new ErrorRepository($error);
    }

    /**
     * Apply ownership scoping based on the authenticated user when available.
     */
        public function checkRole(BaseModel|Model $model)
    {
        if (!auth()->check())
            return $model;

        try {
            $user = auth()->user();
            $model = $model->ownedByUser($user)->ownedByAffiliation($user);
        } catch (\Exception $e) {
            return $this->sendError($e);
        }

        return $model;
    }

    /**
         * Persist a simple API request log entry.
         */
        protected function logApiRequest(string $method, string $url, array $data): void
    {
        $log = new ApiRequestLog();
        $log->method = $method;
        $log->url = $url;
        $log->data = $data;
        $log->save();
    }

    /**
     * Normalize various truthy/falsey representations to boolean.
     * Returns null if value cannot be determined.
     */
    protected function normalizeBoolean($value): ?bool
    {
        if (is_bool($value)) return $value;
        if (is_int($value)) return $value !== 0;
        if (is_string($value)) {
            $v = strtolower(trim($value));
            if (in_array($v, ['1','true','yes','on'], true)) return true;
            if (in_array($v, ['0','false','no','off'], true)) return false;
        }
        return null;
    }
}
