<?php

namespace App\Repository\API;

use App\Models\CBCTourVisitor;
use App\Repository\AbstractRepoService;

class CBCTourVisitorRepo extends AbstractRepoService
{
    public function __construct(CBCTourVisitor $model)
    {
        parent::__construct($model);
    }

    public function createVisitor(array $data): CBCTourVisitor
    {
        return $this->model->create($data);
    }

    public function getVisitorCount(): int
    {
        return $this->model->count();
    }
}
