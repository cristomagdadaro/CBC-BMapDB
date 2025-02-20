<?php

namespace Modules\TwgDb\Repositories;

use App\Repository\AbstractRepoService;
use Modules\TwgDb\Models\TWGProject;

class TWGProjectRepo extends AbstractRepoService
{
    public function __construct(TWGProject $model)
    {
        parent::__construct($model);
    }
}
