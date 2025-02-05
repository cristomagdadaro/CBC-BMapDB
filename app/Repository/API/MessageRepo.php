<?php

namespace App\Repository\API;

use App\Models\Message;
use App\Repository\AbstractRepoService;

class MessageRepo extends AbstractRepoService
{
    public function __construct(Message $model)
    {
        parent::__construct($model);
    }
}
