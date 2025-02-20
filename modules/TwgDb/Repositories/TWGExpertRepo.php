<?php

namespace Modules\TwgDb\Repositories;

use App\Repository\AbstractRepoService;
use Modules\TwgDb\Models\TWGExpert;

class TWGExpertRepo extends AbstractRepoService
{
    public function __construct(TWGExpert $model)
    {
        parent::__construct($model);
    }
}
