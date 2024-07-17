<?php
namespace App\Repository;

use App\Http\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
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
    private string $apppendWith = '';

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
                'data' => $model
            ], Response::HTTP_OK);
        } catch (\Exception $error) {
            return response()->json($this->sendError($error), Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $model = $this->find($id);
            $model->update($data);

            return response()->json([
                'message' => $model->getNotifMessage('updated'),
                'data' => $model
            ], Response::HTTP_OK);
        } catch (\Exception $error) {
            return response()->json($this->sendError($error), Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $model = $this->find($id);
            $model->delete();

            return response()->json([
                'message' => $model->getNotifMessage('deleted'),
                'data' => $model
            ], Response::HTTP_OK);
        } catch (\Exception $error) {
            return response()->json($this->sendError($error), Response::HTTP_INTERNAL_SERVER_ERROR);
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
                } catch (\Exception $error) {
                    $failed[] = $this->sendError($error);
                }
            });

            if ($counter && count($failed) == 0)
                return response()->json([
                    'message' => 'Successfully deleted all data',
                    'deleted' => $models
                ], Response::HTTP_OK);

            else if ($counter && count($failed) > 0)
                return response()->json([
                    'message' => $counter. ' rows successfully deleted but failed to delete ' . count($failed) . ' rows',
                    'failed' => $failed
                ], Response::HTTP_INTERNAL_SERVER_ERROR);

            return response()->json([
                'message' => 'Failed to delete ' . count($failed) . ' rows of data',
                'failed' => $failed
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch (\Exception $error) {
            return response()->json($this->sendError($error), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a model
     * @param int $id model primary key
     * @return JsonResponse | Model
     **/
    public function find(int $id): JsonResponse | Model
    {
        $data = $this->model->find($id);
        if(!$data)
            return response()->json([
                'message' => $this->model->getNotifMessage('notFound'),
                'data' => null
            ], Response::HTTP_NOT_FOUND);

        return $data;
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
            return $this->searchData($parameters, $isTrashed, $withPagination);
        } catch (\Exception $error) {
            return response()->json($this->sendError($error), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function appendWith($tableToAppend)
    {
        $this->apppendWith = $tableToAppend;
    }

    private function searchData(Collection $parameters, bool $isTrashed, $withPagination)
    {
        $perPage = $parameters->get('per_page', 10);
        $page = $parameters->get('page', 1);
        $sort = $parameters->get('sort', 'created_at');
        $order = $parameters->get('order', 'desc');
        $search = $parameters->get('search', '');
        $filter = $parameters->get('filter', null);
        $is_exact = $parameters->get('is_exact', false);

        if ($this->apppendWith && $this->apppendWith != '')
            $builder = $this->model->with($this->apppendWith)->select($this->model->getSearchable());
        else
            $builder = $this->model->select($this->model->getSearchable());

        if($isTrashed)
        {
            $builder = $builder->onlyTrashed();
        }

        if($search)
        {
            $builder = $builder->where(function($query) use ($search, $filter, $is_exact) {
                foreach($this->model->getSearchable() as $column)
                {
                    if ($filter && $column != $filter)
                        $column = $filter;

                    if($is_exact)
                        $query->orWhere($column, $search);
                    else
                        $query->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        if(!$withPagination)
        {
            return $builder->orderBy($sort, $order)->get();
        }
        return $builder->orderBy($sort, $order)->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    private function sendError(\Exception $error): array
    {
        $error = new ErrorRepository($error);
        return $error->getErrorMessage();
    }

    public function summary()
    {
        return $this->model->count();
    }
}
