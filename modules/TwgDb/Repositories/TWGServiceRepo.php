<?php

namespace Modules\TwgDb\Repositories;

use App\Repository\AbstractRepoService;
use Modules\TwgDb\Models\TWGService;

class TWGServiceRepo extends AbstractRepoService
{
    public function __construct(TWGService $model)
    {
        parent::__construct($model);
    }
}
