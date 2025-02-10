<?php

namespace App\Models;

use App\Enums\Role as RoleEnum;
use App\Models\Location\City;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BaseModel extends Model
{
    protected $table = null;

    protected $ignoreUserBasedFiltratration = false;

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

    public function scopeOwnedBy(Builder $query, $user)
    {
        if ($this->ignoreUserBasedFiltratration)
            return $query;

        // If no user is provided, return no records (or handle as required)
        if (!$user) {
            return $query->whereRaw('1 = 0'); // No records
        }

        // If the user is not an admin or does not have the RESEARCHER role
        if (!$user->isAdmin() && !$user->hasRole(RoleEnum::RESEARCHER->value)) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}
