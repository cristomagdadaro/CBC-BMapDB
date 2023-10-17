<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository
{
    protected $model;

    public function __construct($model)
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
        $sort = $parameters->get('sort', 'id');
        $order = $parameters->get('order', 'asc');
        $search = $parameters->get('search', '');

        $builder = $this->model;

        if($isTrashed)
        {
            $builder = $builder->onlyTrashed();
        }

        if($search)
        {
            $builder = $builder->where(function($query) use ($search) {
                foreach($this->model->getSearchable() as $column)
                {
                    $query->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        if(!$withPagination)
        {
            return $builder->orderBy($sort, $order)->get();
        }

        return $builder->orderBy($sort, $order)->paginate($perPage)->withQueryString();
    }
}
