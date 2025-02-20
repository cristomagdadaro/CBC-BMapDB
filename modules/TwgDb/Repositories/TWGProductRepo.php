<?php

namespace Modules\TwgDb\Repositories;

use App\Repository\AbstractRepoService;
use Modules\TwgDb\Models\TWGProduct;

class TWGProductRepo extends AbstractRepoService
{
    public function __construct(TWGProduct $model)
    {
        parent::__construct($model);
    }
}
