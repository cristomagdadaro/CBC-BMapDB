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
use Illuminate\Support\Str;
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


    public function find(int $id): JsonResponse|Model
    {
        $builder = $this->model->query();
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

    protected function buildSearchQuery(Collection $parameters, bool $withPagination, bool $isTrashed): LengthAwarePaginator
    {
        $builder = $this->checkRole($this->model);
        $builder = $builder->select($this->model->getSearchable());

        $this->applyAppends($builder, $parameters);
        $this->applyParentFilter($builder, $parameters);

        if ($isTrashed) {
            $builder = $builder->onlyTrashed();
        }

        $this->applySearchFilters($builder, $parameters);
        $this->applySorting($builder, $parameters);

        if (!$withPagination)
            return $builder->get();
        return $this->applyPagination($builder, $parameters);
    }

    protected function applyPagination(Builder $query, Collection $parameters)
    {
        $perPage = $parameters->get('per_page', 10);
        $page = $parameters->get('page', 1);

        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    protected function applyAppends(Builder &$model, Collection $parameters): void
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

    protected function applyParentFilter(Builder &$query, Collection $parameters): void
    {
        $filterByParentColumn = $parameters->get('filter_by_parent_column');
        $filterByParentId = $parameters->get('filter_by_parent_id');

        if (!empty($filterByParentColumn) && !empty($filterByParentId)) {
            $query = $query->where($filterByParentColumn, $filterByParentId);
        }
    }

    protected function applySearchFilters(Builder &$query, Collection $parameters): void
    {
        $isExact = $parameters->get('is_exact', false);
        $filter = $parameters->get('filter', null);
        $searchTerm = $parameters->get('search', '');

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
        $columns = collect($query->getModel()->getSearchable());

        if (!empty($columns)) {
            if ($filter && str_contains($filter, '.')){
                $temp = explode('.', $filter);
                $filter = $temp[1];
            }

            if ($filter === 'name' && $columns->contains('fname') && $columns->contains('lname'))
                $query->orWhereRaw("CONCAT_WS(' ', fname, mname, lname, suffix) LIKE ?", ["%{$search}%"]);
            else
                $query->where(function ($subQuery) use ($columns, $search, $is_exact) {
                    foreach ($columns as $column) {
                        if ($is_exact) {
                            $subQuery->orWhere($column, $search);
                        } else {
                            $subQuery->orWhere($column, 'like', "%{$search}%");
                        }
                    }
                });
        }
    }

    protected function applyRelationSearch(Builder $query, string $search, ?string $filter, bool $is_exact, string $relation, $relatedModel): void
    {
        $query->orWhereHas($relation, function ($query) use ($search, $filter, $is_exact, $relatedModel) {
            if (str_contains($filter, '.')) {
                $temp = explode('.', $filter);
                $relatedModel = $this->model->{$temp[0]}()->getModel();
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

    private function applySorting(Builder &$query, Collection $parameters): void
    {
        $sortColumn = $parameters->get('sort', 'created_at');
        $order = strtoupper($parameters->get('order', 'desc'));

        // Validate the sort column exists to prevent SQL errors
        if (!Schema::hasColumn($query->getModel()->getTable(), $sortColumn)) {
            if (Schema::hasColumn($query->getModel()->getTable(), 'id'))
                $sortColumn = 'id'; // Default to ID if sorting column doesn't exist
            else
                $sortColumn = 'uuid';
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

    public function determineLocFilterLevel(string $geo_location_filter): string
    {
        return match ($geo_location_filter) {
            'institute' => 'institute',
            'province' => 'provDesc',
            'region' => 'regDesc',
            'city' => 'cityDesc',
            default => throw new \InvalidArgumentException("Invalid geo location filter: {$geo_location_filter}"),
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
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $model; // Return the model directly if the user is an admin
        } else if (!auth()->check()) {
            return $model; // Return the model directly if the user is not authenticated, for testing
        }

        try {
            if (Schema::hasColumn($this->model->getTable(), 'user_id')) {
                $model = $model->ownedBy(auth()->user()); // Filter data to retrieve only those owned by the user
            }
        } catch (\Exception $e) {
            // Handle the exception appropriately, e.g., log it or return an error response
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
