<?php
namespace App\Repository;

use App\Enums\Role;
use App\Http\Interfaces\RepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base class for all repositories.
 * This class will be used to handle all the basic CRUD operations.
 * @param Model $model
 **/
abstract class AbstractRepoService implements RepositoryInterface
{
    /**
     * Model to be used
     * @var Model
    **/
    public Model $model;

    /**
     * Table to append with
     * @var string
    */
    private array $appendWith = [];

    /**
     * Count the rows of the appended tables
     * @var string
    */
    private array $appendCount = [];

    /**
     * Filter the data according to the parent id
    */
    private array|null $filterByParent = null;

    /**
     * Use to filter the data according to the role
    */
    private int $appendFilter = 0;

    /**
     * List of searchable and viewable columns
     * @var array
    **/
    protected array $searchable = [];

    /**
     * Constructor
     * @param Model $model
    **/
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all data
     * @return Collection
    **/
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Create new data
     * @param array $data
     * @return JsonResponse
     **/
    public function create(array $data): JsonResponse
    {
        try {
            $model = $this->model->fill($data);
            $model->save();

            return response()->json([
                'message' => $model->getNotifMessage('created'),
                'data' => $model,
                'show' => true,
                'title' => "Created",
                'type' => "success",
                'timeout' => 10000,
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            return response()->json($this->sendError($error),  500);
        }
    }

    /**
     * Update data
     * @param int $id model primary key
     * @param array $data updated set of data
     * @return JsonResponse
     **/
    public function update(int $id, array $data): JsonResponse
    {
        try {
            $model = $this->model->find($id);
            $model->update($data);

            return response()->json([
                'message' => $model->getNotifMessage('updated'),
                'data' => $model,
                'show' => true,
                'title' => "Updated",
                'type' => "success",
                'timeout' => 10000,
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            return response()->json($this->sendError($error), $error->getCode());
        }
    }

    /**
     * Delete data
     * @param int $id model primary key
     * @return JsonResponse
     **/
    public function delete(int $id): JsonResponse
    {
        try {
            $model = $this->model->find($id);
            $model->delete();

            return response()->json([
                'message' => $model->getNotifMessage('deleted'),
                'data' => $model,
                'show' => true,
                'title' => "Deleted",
                'type' => "warning",
                'timeout' => 10000
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            return response()->json($this->sendError($error),  $error->getCode());
        }
    }

    /**
     * Perform multiple model deletion
     * @param array $ids model primary key
     * @return JsonResponse
     **/
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
            return response()->json($this->sendError($error),  $error->getCode());
        }
    }

    /**
     * Retrieve a model
     * @param int $id model primary key
     * @return JsonResponse
     **/
    public function find(int $id): JsonResponse
    {
        $query = $this->model;

        if (!empty($this->appendWith)) {
            $query = $query->with($this->appendWith);
        }

        $data = $query->find($id);

        if(!$data)
            return response()->json([
                'message' => $this->model->getNotifMessage('notFound'),
                'data' => null,
                'show' => true,
                'title' => "Not Found",
                'type' => "warning",
                'timeout' => 10000
            ], Response::HTTP_NOT_FOUND);

        return response()->json([
            'message' => $this->model->getNotifMessage('found'),
            'data' => $data,
            'show' => true,
            'title' => "Found",
            'type' => "success",
            'timeout' => 10000
        ], Response::HTTP_OK);
    }

    /**
     * Data filtering
     * @param Collection $parameters search parameters
     * @param bool $withPagination
     * @return JsonResponse
     **/
    public function search(Collection $parameters, bool $withPagination = true, bool $isTrashed = false)
    {
        try {
            return $this->searchData($parameters, $withPagination, $isTrashed);
        } catch (Exception $error) {
            return response()->json($this->sendError($error),  500);
        }
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

    private function searchData(Collection $parameters, bool $withPagination, bool $isTrashed)
    {
        $perPage = $parameters->get('per_page', 10);
        $page = $parameters->get('page', 1);
        $sort = $parameters->get('sort', 'created_at');
        $order = $parameters->get('order', 'desc');
        $search = $parameters->get('search', '');
        $filter = $parameters->get('filter', null);
        $is_exact = $parameters->get('is_exact', false);

        if ($this->appendFilter) {
            $builder = $this->model->ofModel($this->appendFilter);
        } else
            $builder = $this->model;

        $builder = $builder->select($this->model->getSearchable());

        if ($this->appendWith) {
            foreach ($this->appendWith as $table) {
                $builder->with($table);
            }
        }

        if ($this->appendCount) {
            foreach ($this->appendCount as $table) {
                $builder->withCount($table);
            }
        }

        if ($this->filterByParent && $this->filterByParent['column'] && $this->filterByParent['value']) {
            $builder = $builder->where($this->filterByParent['column'], $this->filterByParent['value']);
        }

        if ($isTrashed) {
            $builder = $builder->onlyTrashed();
        }

        // Check user role
        /*$user = auth()->user();
        if ($user->getRole() !== Role::ADMIN->value) {
            $builder->where('user_id', $user->id);
        }*/


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



    private function sendError(Exception $error): array
    {
        $error = new ErrorRepository($error);
        return $error->getErrorMessage();
    }

    public function summary(): int
    {
        return $this->model->count();
    }

    /**
     * Determines the location filter level based on the given geolocation filter.
     *
     * @param string $geo_location_filter The geolocation filter to determine the level.
     * @return string The corresponding location filter level.
     */
    public function determineLocFilterLevel(string $geo_location_filter): string
    {
        return match ($geo_location_filter) {
            'province' => 'provDesc',
            'region' => 'regDesc',
            default => 'cityDesc',
        };
    }
}
