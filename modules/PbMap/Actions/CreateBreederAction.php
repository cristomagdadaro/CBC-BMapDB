<?php

namespace Modules\PbMap\Actions;

use Modules\PbMap\Repositories\BreederCreationRepo;
use Modules\PbMap\Requests\CreateBreederRequest;

class CreateBreederAction
{
    public function __construct(private BreederCreationRepo $breederCreationRepo)
    {
    }

    public function execute(CreateBreederRequest $request)
    {
        return $this->breederCreationRepo->createBreederData($request);
    }
}

