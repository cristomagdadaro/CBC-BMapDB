<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

/**
 * Base class for all repositories.
 * This class will be used to handle all the basic CRUD operations.
 * @param Model $model
 **/
class BaseRepository
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
     * @return Model
     **/
    public function create(array $data): Model
    {
        $model = $this->model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * Update data
     * @param int $id model primary key
     * @param array $data updated set of data
     * @return bool
     **/
    public function update($id, array $data): bool
    {
        return $this->find($id)->update($data);
    }

    public function delete($id): bool
    {
        return $this->model->destroy($id);
    }

    public function multiDestroy(array $ids): bool
    {
        return $this->model->destroy($ids['ids']);
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function search(Collection $parameters, $withPagination = true)
    {

        return $this->searchData($parameters, false, $withPagination);
    }

    public function appendWith($tableToAppend)
    {
        $this->apppendWith = $tableToAppend;
    }

    private function searchData(Collection $parameters, bool $isTrashed, $withPagination)
    {
        $perPage = $parameters->get('per_page', 10);
        $page = $parameters->get('page', 1);
        $sort = $parameters->get('sort', 'id');
        $order = $parameters->get('order', 'asc');
        $search = $parameters->get('search', '');
        $filter = $parameters->get('filter', null);
        $is_exact = $parameters->get('is_exact', false);

        if ($this->apppendWith && $this->apppendWith != '')
            $builder = $this->model->with($this->apppendWith)->select($this->searchable);
        else
            $builder = $this->model->select($this->searchable);

        if($isTrashed)
        {
            $builder = $builder->onlyTrashed();
        }

        if($search)
        {
            $builder = $builder->where(function($query) use ($search, $filter, $is_exact) {
                foreach($this->searchable as $column)
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
}
