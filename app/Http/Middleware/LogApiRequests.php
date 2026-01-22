<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ApiRequestLog;
use Illuminate\Support\Str;

class LogApiRequests
{
    public function handle(Request $request, Closure $next)
    {
        $method = $request->method();
        $preDeleteNames = [];

        if ($request->is('api/*') && strtoupper($method) === 'DELETE') {
            $preDeleteNames = $this->getNamesBeforeDelete($request);
        }

        $response = $next($request);

        if (!$request->is('api/*')) {
            return $response;
        }

        $userId = Auth::id();
        $user = Auth::user();
        $userRole = $user ? $user->getRole() : null;
        $ip_address = $request->ip();
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $response;
        }

        $url = $request->fullUrl();
        $model = $this->getModelFromUrl($url);
        $data = $request->all();
        $modifiedId = $data['id']
            ?? $request->route('id')
            ?? $request->route('user')
            ?? $request->route('uuid')
            ?? $this->getIdFromUrl($url);

        $log = new ApiRequestLog();
        $log->user_id = $userId;
        $log->user_role = $userRole;
        $log->ip_address = $ip_address;
        $log->method = $method;
        $log->url = $url;
        $log->model = $model;
        $log->data = json_encode($data);
        $log->modified_id = $modifiedId;
        $log->description = $this->buildDescription($method, $model, $modifiedId, $user, $data, $preDeleteNames);
        $log->save();

        return $response;
    }

    /**
     * Extract model name from the request URL.
     */
    protected function getModelFromUrl(string $url): ?string
    {
        $segments = explode('/', parse_url($url, PHP_URL_PATH));
        return $segments[2] ?? null;
    }

    protected function buildDescription(string $method, ?string $model, $modifiedId, $user, array $data, array $preDeleteNames = []): string
    {
        $actor = $this->getActorName($user);
        $verb = match (strtoupper($method)) {
            'POST' => 'created a new',
            'PUT', 'PATCH' => 'updated',
            'DELETE' => 'deleted',
            default => 'performed',
        };

        $ids = $this->extractIds($data, $modifiedId);
        $isBulk = count($ids) > 1;
        $item = $this->humanizeModel($model, $isBulk);

        $names = $preDeleteNames ?: $this->resolveDisplayNames($model, $ids, $data);
        if ($names) {
            $suffix = ' ' . implode(', ', $names);
            return trim("{$actor} {$verb} {$item}{$suffix}");
        }

        $suffix = $modifiedId ? " #{$modifiedId}" : '';
        return trim("{$actor} {$verb} {$item}{$suffix}");
    }

    protected function getActorName($user): string
    {
        if (!$user) {
            return 'User';
        }

        if (method_exists($user, 'getFullName')) {
            return $user->getFullName();
        }

        return $user->name ?? $user->email ?? 'User';
    }

    protected function resolveDisplayNames(?string $model, array $ids, array $data): array
    {
        $payloadName = $this->extractNameFromPayload($data);
        if ($payloadName) {
            return [$payloadName];
        }

        $modelClass = $this->resolveModelClass($model);
        if (!$modelClass || !$ids) {
            return [];
        }

        try {
            $records = $modelClass::query()->whereIn('id', $ids)->get();
            $names = [];
            foreach ($records as $record) {
                $name = $this->extractNameFromRecord($record);
                if ($name) {
                    $names[] = $name;
                }
            }
            return $names;
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected function extractNameFromPayload(array $data): ?string
    {
        $nameFields = [
            'name',
            'title',
            'label',
        ];

        foreach ($nameFields as $field) {
            if (!empty($data[$field]) && is_string($data[$field])) {
                return trim($data[$field]);
            }
        }

        $fname = $data['fname'] ?? null;
        $lname = $data['lname'] ?? null;
        $mname = $data['mname'] ?? null;
        $suffix = $data['suffix'] ?? null;

        if ($fname || $lname) {
            return trim(implode(' ', array_filter([$fname, $mname, $lname, $suffix])));
        }

        return null;
    }

    protected function extractNameFromRecord($record): ?string
    {
        if (method_exists($record, 'getFullName')) {
            return $record->getFullName();
        }

        if (property_exists($record, 'name') && $record->name) {
            return $record->name;
        }

        $parts = [];
        foreach (['fname', 'mname', 'lname', 'suffix'] as $field) {
            if (property_exists($record, $field) && $record->{$field}) {
                $parts[] = $record->{$field};
            }
        }

        return $parts ? trim(implode(' ', $parts)) : null;
    }

    protected function resolveModelClass(?string $model): ?string
    {
        if (!$model) {
            return null;
        }

        $map = [
            'breeders' => \Modules\PbMap\Models\Breeder::class,
            'commodities' => \Modules\PbMap\Models\Commodity::class,
            'users' => \App\Models\User::class,
            'institutes' => \App\Models\Institute::class,
            'accounts' => \App\Models\Accounts::class,
        ];

        return $map[$model] ?? null;
    }

    protected function humanizeModel(?string $model, bool $plural = false): string
    {
        if (!$model) {
            return 'record';
        }

        $model = str_replace(['-', '_'], ' ', $model);
        $model = Str::lower($model);

        $model = $plural ? Str::plural($model) : Str::singular($model);

        return ucwords($model);
    }

    protected function extractIds(array $data, $modifiedId): array
    {
        $ids = [];
        if (isset($data['ids'])) {
            $ids = is_array($data['ids']) ? $data['ids'] : explode(',', (string) $data['ids']);
        } elseif ($modifiedId) {
            $ids = [$modifiedId];
        }

        $ids = array_filter(array_map('intval', $ids));
        return array_values(array_unique($ids));
    }

    protected function getNamesBeforeDelete(Request $request): array
    {
        $url = $request->fullUrl();
        $model = $this->getModelFromUrl($url);
        $data = $request->all();
        $modifiedId = $data['id']
            ?? $request->route('id')
            ?? $request->route('user')
            ?? $request->route('uuid')
            ?? $this->getIdFromUrl($url);

        $ids = $this->extractIds($data, $modifiedId);
        if (!$ids) {
            return [];
        }

        return $this->resolveDisplayNames($model, $ids, $data);
    }

    protected function getIdFromUrl(string $url): ?int
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return null;
        }

        $segments = array_values(array_filter(explode('/', $path)));
        $last = end($segments);
        if ($last && ctype_digit($last)) {
            return (int) $last;
        }

        return null;
    }
}
