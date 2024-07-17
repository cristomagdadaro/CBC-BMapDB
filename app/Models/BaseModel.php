<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected array $searchable = [];

    protected array $notifMessage = [];

    public function getSearchable(): array
    {
        return $this->searchable;
    }

    public function getNotifMessage($action = null): string
    {
        return $this->notifMessage[$action];
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('g:i a M j, Y');
    }
}
