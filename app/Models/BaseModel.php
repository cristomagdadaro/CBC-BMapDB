<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $table = null;

    protected array $searchable = [];

    protected array $notifMessage = [];

    public function getSearchable(): array
    {
        return $this->searchable;
    }

    public function getNotifMessage($action = null): string | null
    {
        if ($action === null || !array_key_exists($action, $this->notifMessage))
            return null;
        else
            return $this->notifMessage[$action];
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('M j, Y g:i a');
    }

    public function getTableName(): string
    {
        return $this->table;
    }
}
