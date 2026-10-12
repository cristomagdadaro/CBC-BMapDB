<?php

namespace App\Repository\API;

use App\Models\ApiRequestLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityLogRepo
{
    public function getLogs(array $filters, $user): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 15);
        
        $query = ApiRequestLog::query()->latest();

        $mineOnly = filter_var($filters['mine'] ?? true, FILTER_VALIDATE_BOOLEAN);
        
        if ($mineOnly || !$user || !$user->isAdmin()) {
            $query->where('user_id', $user?->id);
        } elseif (isset($filters['user_id'])) {
            $query->where('user_id', (int) $filters['user_id']);
        }

        if (isset($filters['method'])) {
            $query->where('method', strtoupper($filters['method']));
        }

        $query->whereNotIn('method', ['GET', 'HEAD', 'OPTIONS']);

        if (isset($filters['model'])) {
            $query->where('model', $filters['model']);
        }

        return $query->paginate($perPage);
    }
}
