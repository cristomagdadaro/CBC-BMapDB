<?php

namespace App\Repository\API;

use App\Models\User;
use App\Repository\AbstractRepoService;

class UserRepo extends AbstractRepoService
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function createUser(array $data): User
    {
        return $this->model->create($data);
    }

    public function findUserById(int $id): ?User
    {
        return $this->model->find($id);
    }
}
