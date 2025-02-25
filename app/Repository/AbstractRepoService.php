<?php

namespace App\Repository;

use App\Filters\Filter;
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

abstract class AbstractRepoService implements AbstractRepoServiceInterface
{
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
     * Add custom filters
     */
    private Filter $filters;

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
            $model = $this->model->fill($data);
            $model->save();

            return $this->jsonResponse('created', $model->toArray());
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $model = $this->model->find($id);
            $model->fill($data);
            $model->save();

            return $this->jsonResponse('updated', $model->toArray());
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $model = $this->find($id);

            if ($model instanceof JsonResponse)
                return $model;

            $model->delete();
            return $this->jsonResponse('deleted', $model->toArray());
        } catch (\Exception $e) {
            return $this->sendError($e);
        }
    }

    public function multiDestroy(array $params): JsonResponse
    {
        try {
            $successful = [];
            $failed = [];

            $ids = $params['ids'];

            foreach ($ids as $id) {
                try {
                    $model = $this->find($id);

                    if ($model instanceof JsonResponse) {
                        return $model;
                    }

                    $model->delete();
                    $successful[] = $model;

                } catch (\Exception $e) {
                    $failed[$id] = $this->sendError($e);
                }
            }

            if (!empty($successful)) {
                return $this->jsonResponse('deleted', [
                    'successful' => $successful,
                    'message' => 'Some Data Deleted Successfully',
                ]);
            } elseif (empty($failed)) {
                return $this->jsonResponse('failure', null, ['message' => 'No Data Found or Already Deleted']);
            }

            return $this->jsonResponse('error');

        } catch (\Exception $e) {
            return $this->sendError($e);
        }
    }


    public function find(int $id, $parameters = null): JsonResponse|Model
    {
        $builder = $this->model->query();
        if ($parameters)
            $this->applyAppends($builder, $parameters);
        return $builder->findOr($id, fn() => $this->jsonResponse('not_found'));
    }

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
            return $this->buildSearchQuery($parameters, $withPagination, $isTrashed);
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    protected function buildSearchQuery(Collection $parameters, bool $withPagination, bool $isTrashed): LengthAwarePaginator | Builder
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
            return $builder;
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

        if ($group_by) {
            $query->groupBy($group_by);
        }
    }

    public function applyGeoFilters(Builder &$query, Collection $parameters): void
    {
        $group_by = $this->determineLocFilterLevel($parameters->get('geo_location_filter'));
        $geo_location_value = $parameters->get('geo_location_value');

        if (Schema::hasColumn($this->model->getTable(), 'geolocation'))
        $query = $query->join('loc_cities', 'loc_cities.id', '=', 'geolocation')
            ->join('users', 'users.id', '=', 'user_id');

        if ($geo_location_value) {
            if ($group_by !== 'affiliation') {
                // Check if the column exists before applying the filter
                if (Schema::hasColumn('loc_cities', $group_by)) {
                    $query = $query->where('loc_cities.' . $group_by, $geo_location_value);
                }
            } else {
                // Assuming 'institutes' is another table you are joining, so check for its columns
                if (Schema::hasColumn('institutes', 'id')) {
                    $query = $query->where('institutes.id', $geo_location_value);
                }
            }
        }
    }


    protected function applyPagination(Builder $query, Collection $parameters)
    {
        $perPage = $parameters->get('per_page', 10);
        $page = $parameters->get('page', 1);

        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function applyAppends(Builder &$model, Collection $parameters): void
    {
        $with = $parameters->get('with', null);
        $count = $parameters->get('count', null);

        if ($with) {
            $this->appendWith = explode(',', $with);
        }

        if ($count) {
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
        if ($columns->contains('fname') && $columns->contains('lname')) {
            $query->orWhereRaw("CONCAT_WS(' ', fname, mname, lname, suffix) LIKE ?", ["%{$search}%"]);
            return;
        }

        // Handle specific "name" column search
        if ($filter === 'name') {
            $query->where('name', 'like', "%{$search}%");
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
                if (($filter === 'name' && in_array('fname', $searchable) && in_array('lname', $searchable) || $table === 'users')) {
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



    public function applySorting(Builder &$query, Collection $parameters): void
    {
        $sortColumn = $parameters->get('sort', null);
        $order = strtoupper($parameters->get('order', 'desc'));

        if (!$sortColumn) return;

        // Validate the sort column exists to prevent SQL errors
        $table = $query->getModel()->getTable();
        if (!Schema::hasColumn($table, $sortColumn)) {
            $selectedColumns = $query->getQuery()->getColumns() ? $query->getQuery()->getColumns()[0] : ''; // Get selected columns from query

            if (str_contains($selectedColumns, $sortColumn)) {
                // If sort column exists in the query, use it
                $sortColumn = $sortColumn;
            } elseif (Schema::hasColumn($query->getModel()->getTable(), 'id')) {
                // Default to table ID if it exists
                $sortColumn = $table.'.id';
            } else {
                // Default to UUID if no valid column is found
                $sortColumn = 'uuid';
            }
        }

        if (in_array($order, ['ASC', 'DESC'])) {
            $query->orderBy($sortColumn, $order);
        } else {
            $query->orderBy($sortColumn, 'desc'); // Fallback to descending order
        }
    }

    public function summary(): int
    {
        try {
            return $this->model->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function determineLocFilterLevel($geo_location_filter): string|null
    {
        return match ($geo_location_filter) {
            'institute' => 'institute',
            'province' => 'provDesc',
            'region' => 'regDesc',
            'city' => 'cityDesc',
            default => null,
        };
    }

    /**
     * @throws ErrorRepository
     */
    public function sendError(Exception $error)
    {
        Log::error('Error occurred: ' . $error->getMessage(), ['exception' => $error]);
        throw new ErrorRepository($error);
    }

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

    protected function logApiRequest(string $method, string $url, array $data): void
    {
        $log = new ApiRequestLog();
        $log->method = $method;
        $log->url = $url;
        $log->data = $data;
        $log->save();
    }
}
