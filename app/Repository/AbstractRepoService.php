<?php
namespace App\Repository;

use App\Http\Interfaces\AbstractRepoServiceInterface;
use App\Models\BaseModel;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

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
    private array $appendWith = [];

    /**
     * Count the rows of the appended tables
     * @var string[]
    */
    private array $appendCount = [];

    /**
     * Filter the data according to the parent id
    */
    protected array|null $filterByParent = null;

    /**
     * Use to filter the data according to the role
    */
    private int $appendFilter = 0;

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

            return $this->jsonResponse($this->model->getNotifMessage('created'), $model->toArray(), 'New Data Inserted', 'success', 201);
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

            return $this->jsonResponse( $model->getNotifMessage('updated'), $model->toArray(), 'Updated Data', 'success');
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $model = $this->model->find($id);
            if ($model)
                $model->delete();
            else
                return $this->jsonResponse($this->model->getNotifMessage('notFound'), null, 'Not found', 'error', 404);

            return $this->jsonResponse($this->model->getNotifMessage('deleted'), $model->toArray(), 'Removed Data', 'warning');
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    public function multiDestroy(array $ids): JsonResponse
    {
        try {
            $models = $this->model->find($ids['ids']);
            $counter = 0;
            $failed = [];

            $models->each(function ($model) use (&$counter, &$failed) {
                try {
                    $model->delete();
                    $counter++;
                } catch (Exception $error) {
                    $failed[] = $this->sendError($error);
                }
            });

            if ($counter && count($failed) == 0)
                return response()->json([
                    'message' => 'Successfully deleted all data',
                    'data' => $models,
                    'show' => true,
                    'title' => "Deleted",
                    'type' => "warning",
                    'timeout' => 10000
                ], Response::HTTP_OK);

            else if ($counter && count($failed) > 0)
                return response()->json([
                    'message' => $counter. ' rows successfully deleted but failed to delete ' . count($failed) . ' rows',
                    'data' => $failed,
                    'show' => true,
                    'title' => "Deleted",
                    'type' => "warning",
                    'timeout' => 10000
                ],  Response::HTTP_OK);

            return response()->json([
                'message' => 'Failed to delete ' . count($failed) . ' rows of data',
                'data' => $failed,
                'show' => true,
                'title' => "Deleted",
                'type' => "warning",
                'timeout' => 10000
            ], Response::HTTP_OK);
        }catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    public function find(int $id): JsonResponse|BaseModel
    {
        try {
            $query = $this->model->query();
            $this->applyAppends($query);
            return $query->findOr($id, fn() => $this->jsonResponse($this->model->getNotifMessage('notFound'), null, 'Not found', 'error', 404));
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    private function applyAppends(Builder &$model)
    {
        if (!empty($this->appendWith)) {
            $model = $model->with($this->appendWith);
        }

        if (!empty($this->appendCount)) {
            $model = $model->withCount($this->appendCount);
        }
    }

    /**
     * Create a standardized JSON response.
     *
     * @param string $message Response message.
     * @param mixed $data Response data.
     * @param string $title Response title.
     * @param string $type Notification type (success, warning, error).
     * @param int $statusCode HTTP status code.
     * @return JsonResponse
     */
    private function jsonResponse(
        string $message,
               $data = null,
        string $title = '',
        string $type = 'info',
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'show' => true,
            'title' => $title,
            'type' => $type,
            'timeout' => 10000,
        ], $statusCode);
    }

    public function search(Collection $parameters, bool $withPagination = true, bool $isTrashed = false)
    {
        try {
            return $this->searchData($parameters, $withPagination, $isTrashed);
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    private function searchData(Collection $parameters, bool $withPagination, bool $isTrashed)
    {
        $perPage = $parameters->get('per_page', 10);
        $page = $parameters->get('page', 1);
        $sort = $parameters->get('sort', 'created_at');
        $order = $parameters->get('order', 'desc');
        $search = $parameters->get('search', '');
        $filter = $parameters->get('filter', null);
        $is_exact = $parameters->get('is_exact', false);
        /*Used when viewing single row while filtering the subtable*/
        $filter_by_parent_id = $parameters->get('filter_by_parent_id', null);
        $filter_by_parent_column = $parameters->get('filter_by_parent_column', null);

        $builder = $this->checkRole($this->model);

        $with = $parameters->get('with', null);
        $count = $parameters->get('count', null);

        if ($with)
            $this->appendWith = explode(',', $with);
        if ($count)
            $this->appendCount = explode(',', $count);

        $builder = $builder->select($this->model->getSearchable());

        $this->applyAppends($builder);

        if ($filter_by_parent_column && $filter_by_parent_id) {
            $builder = $builder->where($filter_by_parent_column, $filter_by_parent_id);
        }

        if ($isTrashed) {
            $builder = $builder->onlyTrashed();
        }


        if ($search) {
            $builder->where(function ($query) use ($search, $filter, $is_exact) {
                foreach ($this->model->getSearchable() as $column) {
                    if ($filter && $column != $filter) {
                        $column = $filter;
                    }

                    if ($is_exact) {
                        $query->orWhere($column, $search);
                    } else {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                }
            });

            if ($this->appendWith) {
                foreach ($this->appendWith as $table) {
                    $relatedModel = $this->model->{$table}()->getModel();
                    $builder->orWhereHas($table, function ($query) use ($search, $filter, $is_exact, $relatedModel) {
                        $query->where(function ($query) use ($search, $filter, $is_exact, $relatedModel) {
                            foreach ($relatedModel->getSearchable() as $column) {
                                if ($filter && $column != $filter) {
                                    $column = $filter;
                                }

                                if ($is_exact) {
                                    $query->orWhere($column, $search);
                                } else {
                                    $query->orWhere($column, 'like', "%{$search}%");
                                }
                            }
                        });
                    });
                }
            }
        }

        // Check if the sort column exists in the current table
        if (!Schema::hasColumn($this->model->getTable(), $sort)) {
            $sort = 'id'; // Default sort column
        }

        if (!$withPagination) {
            return $builder->orderBy($sort, $order)->get();
        }

        return $builder->orderBy($sort, $order)->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function appendWith(array $tableToAppend): void
    {
        $this->appendWith = $tableToAppend;
    }

    public function appendCount(array $countTable): void
    {
        $this->appendCount = $countTable;
    }

    public function filterByParent(array|null $parent): void
    {
        $this->filterByParent = $parent;
    }

    public function appendCondition(int $tableConditions): void
    {
        $this->appendFilter = $tableConditions;
    }

    public function summary(): int
    {
        return $this->model->count();
    }

    public function determineLocFilterLevel(string $geo_location_filter): string
    {
        return match ($geo_location_filter) {
            'province' => 'provDesc',
            'region' => 'regDesc',
            default => 'cityDesc',
        };
    }

    public function sendError(Exception $error): JsonResponse
    {
        $error = new ErrorRepository($error);
        return response()->json($error->getErrorMessage(), $error->getErrorCode());
    }

    public function checkRole(BaseModel|Model $model)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $model; // Return the model directly if the user is an admin
        }

        if (Schema::hasColumn($this->model->getTable(), 'user_id')) {
            $model = $model->ownedBy(auth()->user()); // Filter data to retrieve only those owned by the user
        }

        return $model;
    }
}
