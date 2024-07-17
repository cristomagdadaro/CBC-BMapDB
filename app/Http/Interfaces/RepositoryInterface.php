<?php

namespace App\Http\Interfaces;

use Illuminate\Support\Collection;

interface RepositoryInterface
{
    public function search(Collection $parameters, bool $withPagination = true);
    public function all();
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
