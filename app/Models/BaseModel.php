<?php

namespace App\Models;

use App\Models\Location\City;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function location(): BelongsTo
    {
        return $this->belongsTo(City::class, 'geolocation')
            ->select((new City())->getSearchable());
    }
}
