<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository
{
    protected Model $model;

    protected array $searchable = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Model
    {
        return $this->model->newInstance($data);
    }

    public function save(Model $model): Model
    {
        $model->save();
        return $model;
    }

    public function update(Model $model, array $data): Model
    {
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function search(Collection $parameters, $withPagination = true)
    {

        return $this->searchData($parameters, false, $withPagination);
    }

    private function searchData(Collection $parameters, bool $isTrashed, $withPagination)
    {
        $perPage = $parameters->get('per_page', 10);
        $page = $parameters->get('page', 1);
        $sort = $parameters->get('sort', 'id');
        $order = $parameters->get('order', 'asc');
        $search = $parameters->get('search', '');
        $filter = $parameters->get('filter', null);

        $builder = $this->model;

        if($isTrashed)
        {
            $builder = $builder->onlyTrashed();
        }

        if($search)
        {
            $builder = $builder->where(function($query) use ($search, $filter) {
                foreach($this->searchable as $column)
                {
                    if ($filter && $column != $filter)
                        $column = $filter;
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
