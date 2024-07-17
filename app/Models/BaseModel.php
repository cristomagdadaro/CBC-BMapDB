<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected array $searchable = [];

    public function getSearchable(): array
    {
        return $this->searchable;
    }
}
