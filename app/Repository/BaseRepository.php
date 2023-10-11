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
}
