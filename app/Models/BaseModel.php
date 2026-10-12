<?php

namespace App\Models;

use App\Models\Location\City;
use App\Traits\OwnedByTrait;
use App\Enums\DataViews;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class BaseModel extends Model
{
    use OwnedByTrait;

    protected $table = null;

    protected array $searchable = [];

    protected array $notifMessage = [];

    public function getSearchable(): array
    {
        return $this->searchable;
    }

    public function getNotifMessage($action = null): ?string
    {
        return $this->notifMessage[$action] ?? null;
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

    public function toArray()
    {
        $array = parent::toArray();
        
        $ownerId = $this->user_id ?? null;
        if (!$ownerId) {
            return $array;
        }

        $guard = DataViews::PUBLIC->value;
        $viewer = auth()->user();

        if ($viewer) {
            if ($viewer->id === $ownerId) {
                $guard = DataViews::PRIVATE->value;
            } else {
                $guard = DataViews::SYSTEM->value;
            }
        }

        $table = $this->getTable();

        // Use a static array to cache data views within the request lifecycle to avoid N+1 queries
        static $dataViewCache = [];
        $cacheKey = "{$ownerId}_{$table}_{$guard}";

        if (!array_key_exists($cacheKey, $dataViewCache)) {
            $dataViewCache[$cacheKey] = \App\Models\DataView::where('user_account_id', $ownerId)
                ->where('model', $table)
                ->where('visibility_guard', $guard)
                ->value('columns');
        }

        $allowedColumns = $dataViewCache[$cacheKey];

        if ($allowedColumns === null) {
            return $array; // No config found, return all
        }

        // Always keep essential keys like id, and relations if necessary
        $filteredArray = [];
        foreach ($array as $key => $value) {
            if (in_array($key, $allowedColumns) || $key === 'id') {
                $filteredArray[$key] = $value;
            }
        }

        return $filteredArray;
    }
}
