<?php

namespace App\Repository;

use App\Http\Interfaces\AbstractRepoServiceInterface;
use App\Models\ApiRequestLog;
use App\Models\BaseModel;
use App\Repository\Filters\FilterPipeline;
use App\Repository\Filters\AggregationFilter;
use App\Repository\Filters\DateRangeFilter;
use App\Repository\Filters\HavingFilter;
use App\Services\PushNotificationService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Optimized base repository service with robust filtering architecture.
 *
 * Key Improvements:
 * - Modular filter system using FilterPipeline for better maintainability
 * - Optimized query building with proper join management
 * - Better aggregation support with dedicated filters
 * - Enhanced performance through lazy loading and query optimization
 * - Standardized error handling and responses
 * - More flexible and extensible filtering
 * - Push notifications for CRUD operations
 *
 * @version 2.0
 */
abstract class AbstractRepoService implements AbstractRepoServiceInterface
{
    // Response type keys used with config('responses.*')
    public const RESPONSE_CREATED = 'created';
    public const RESPONSE_UPDATED = 'updated';
    public const RESPONSE_DELETED = 'deleted';
    public const RESPONSE_FAILURE = 'failure';
    public const RESPONSE_NOT_FOUND = 'not_found';

    // Defaults and common options
    public const DEFAULT_PER_PAGE = 25;
    public const DEFAULT_PAGE = 1;
    public const SORT_DEFAULT_ORDER = 'desc';

    // Column names
    public const COL_ID = 'id';
    public const COL_UUID = 'uuid';
    public const COL_NAME = 'name';

    // Messages
    public const MSG_NO_DATA = 'No Data Found or Already Deleted';
    public const MSG_NO_IDS = 'No IDs provided';

    /**
     * Model instance
     */
    public Model $model;

    /**
     * Filter pipeline for query building
     */
    protected FilterPipeline $filterPipeline;

    /**
     * Cache for filter pipeline to avoid recreating
     */
    private static ?FilterPipeline $defaultPipeline = null;

    /**
     * Push notification service
     */
    protected PushNotificationService $pushNotification;

    /**
     * Enable/disable push notifications for this repository
     */
    protected bool $enablePushNotifications = true;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->filterPipeline = $this->createFilterPipeline();
        $this->pushNotification = new PushNotificationService();
    }

    /**
     * Create and configure the filter pipeline.
     * Override this method in child classes to customize filters.
     */
    protected function createFilterPipeline(): FilterPipeline
    {
        if (self::$defaultPipeline === null) {
            self::$defaultPipeline = FilterPipeline::createDefault()
                ->addFilter(new AggregationFilter())
                ->addFilter(new DateRangeFilter())
                ->addFilter(new HavingFilter());
        }

        return clone self::$defaultPipeline;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): JsonResponse
    {
        try {
            $model = $this->model->create($data);

            // Send push notification
            if ($this->enablePushNotifications && auth()->check()) {
                $resourceName = $this->getResourceName();
                $userName = auth()->user()->name ?? 'A user';
                $this->pushNotification->notifyCreated($resourceName, $userName);
            }

            return $this->jsonResponse(self::RESPONSE_CREATED, $model);
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $model = $this->model->findOrFail($id);
            $model->update($data);

            // Send push notification
            if ($this->enablePushNotifications && auth()->check()) {
                $resourceName = $this->getResourceName();
                $userName = auth()->user()->name ?? 'A user';
                $this->pushNotification->notifyUpdated($resourceName, $userName);
            }

            return $this->jsonResponse(self::RESPONSE_UPDATED, $model);
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $model = $this->model->findOrFail($id);
            $model->delete();

            // Send push notification
            if ($this->enablePushNotifications && auth()->check()) {
                $resourceName = $this->getResourceName();
                $userName = auth()->user()->name ?? 'A user';
                $this->pushNotification->notifyDeleted($resourceName, $userName);
            }

            return $this->jsonResponse(self::RESPONSE_DELETED, $model);
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }

    /**
     * Bulk delete by IDs with improved validation.
     */
    public function multiDestroy(array $params): JsonResponse
    {
        try {
            $ids = $this->normalizeIdList($params['ids'] ?? []);

            if (empty($ids)) {
                return $this->jsonResponse(self::RESPONSE_FAILURE, null, ['message' => self::MSG_NO_IDS]);
            }

            $deletedCount = $this->model->whereIn(self::COL_ID, $ids)->delete();

            if ($deletedCount > 0) {
                // Send push notification for bulk delete
                if ($this->enablePushNotifications && auth()->check()) {
                    $resourceName = $this->getResourceName();
                    $userName = auth()->user()->name ?? 'A user';
                    $this->pushNotification->notifyCustom(
                        "Multiple {$resourceName} Deleted",
                        "{$userName} has deleted {$deletedCount} {$resourceName} records"
                    );
                }

                return $this->jsonResponse(self::RESPONSE_DELETED, ['count' => $deletedCount]);
            }

            return $this->jsonResponse(self::RESPONSE_FAILURE, null, ['message' => self::MSG_NO_DATA]);

        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }

    public function find(int $id, $parameters = null): JsonResponse|Model
    {
        $builder = $this->model->query();

        if ($parameters) {
            $params = $parameters instanceof Collection ? $parameters : collect($parameters);
            $builder = $this->filterPipeline->apply($builder, $params);
        }

        return $builder->findOr($id, fn() => $this->jsonResponse(self::RESPONSE_NOT_FOUND));
    }

    /**
     * Build a standardized JSON response payload from config('responses.*').
     */
    public function jsonResponse(string $type, mixed $data = null, ?array $overrides = null): JsonResponse
    {
        $responseConfig = Config::get("responses.{$type}");

        if (!$responseConfig) {
            throw new \InvalidArgumentException("Invalid response type: {$type}");
        }

        $response = array_merge([
            'data' => $data,
            'show' => true,
        ], $responseConfig);

        if ($overrides !== null) {
            $response = array_merge($response, $overrides);
        }

        $statusCode = $responseConfig['statusCode'] ?? Response::HTTP_OK;
        return response()->json($response, $statusCode);
    }

    /**
     * Main search method with optimized filtering.
     */
    public function search(Collection $parameters, bool $withPagination = true, bool $isTrashed = false): Builder|LengthAwarePaginator|Collection
    {
        try {
            $paginateRaw = $parameters->get('paginate', $withPagination);
            $normalized = $this->normalizeBoolean($paginateRaw);
            if (!is_null($normalized)) {
                $withPagination = $normalized;
            }

            return $this->buildSearchQuery($parameters, $withPagination, $isTrashed);
        } catch (Exception $error) {
            return $this->sendError($error);
        }
    }

    /**
     * Build the search query using the filter pipeline.
     */
    protected function buildSearchQuery(Collection $parameters, bool $withPagination, bool $isTrashed): LengthAwarePaginator|Builder|Collection
    {
        // Start with a fresh query builder (not applying scopes to the model directly)
        $builder = $this->model->newQuery();

        // Apply role-based filtering
        $builder = $this->applyRoleBasedFiltering($builder);

        // Apply soft delete filter
        if ($isTrashed) {
            $builder = $builder->onlyTrashed();
        }

        // Apply all filters through the pipeline
        $builder = $this->filterPipeline->apply($builder, $parameters);

        // Return based on pagination preference
        if (!$withPagination) {
            return $builder->get();
        }

        return $this->applyPagination($builder, $parameters);
    }

    /**
     * Apply pagination with optimized handling of special cases.
     */
    protected function applyPagination(Builder $query, Collection $parameters): LengthAwarePaginator
    {
        $perPageRaw = $parameters->get('per_page', Config::get('app.pagination_per_page', self::DEFAULT_PER_PAGE));
        $page = (int) $parameters->get('page', Config::get('app.pagination_page', self::DEFAULT_PAGE));

        // Handle '*' to return all results
        if (is_string($perPageRaw) && trim($perPageRaw) === '*') {
            $total = $this->getQueryCount($query);
            $perPage = max(1, $total);
            $page = 1;
            return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
        }

        $perPage = (int) $perPageRaw;
        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    /**
     * Get count efficiently without affecting the original query.
     */
    protected function getQueryCount(Builder $query): int
    {
        try {
            return (clone $query)->count();
        } catch (\Exception $e) {
            Log::warning('Failed to get query count', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Quick summary count of the model records.
     */
    public function summary(): int
    {
        try {
            return $this->model->count();
        } catch (Exception $e) {
            Log::error('Failed to get summary count', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Normalize incoming geo_location_filter to the corresponding column/key.
     * Maintained for backward compatibility.
     */
    public function determineLocFilterLevel($geo_location_filter): string|null
    {
        return match ($geo_location_filter) {
            'affiliation' => 'institutes.name',
            'province' => 'provDesc',
            'region' => 'regDesc',
            'city' => 'cityDesc',
            default => null,
        };
    }

    /**
     * Log the exception and wrap it into an ErrorRepository for unified error handling.
     *
     * @throws ErrorRepository
     */
    public function sendError(Exception $error)
    {
        Log::error('Repository error: ' . $error->getMessage(), [
            'exception' => $error,
            'trace' => $error->getTraceAsString(),
        ]);
        throw new ErrorRepository($error);
    }

    /**
     * Apply ownership scoping based on the authenticated user.
     * This method now works with a query builder instead of the model directly.
     */
    private function applyRoleBasedFiltering(Builder $builder): Builder
    {
        if (!auth()->check()) {
            return $builder;
        }

        try {
            $user = auth()->user();
            $model = $builder->getModel();

            // Check if model uses OwnedByTrait scopes
            if (method_exists($model, 'scopeOwnedByUser')) {
                $builder = $builder->ownedByUser($user);
            }

            if (method_exists($model, 'scopeOwnedByAffiliation')) {
                $builder = $builder->ownedByAffiliation($user);
            }
        } catch (Exception $e) {
            Log::warning('Failed to apply role-based filtering', ['error' => $e->getMessage()]);
        }

        return $builder;
    }

    /**
     * Apply ownership scoping based on the authenticated user.
     * @deprecated Use applyRoleBasedFiltering() instead
     */
    public function checkRole(BaseModel|Model $model): BaseModel|Model
    {
        if (!auth()->check()) {
            return $model;
        }

        try {
            $user = auth()->user();

            // Only apply if methods exist to avoid errors
            if (method_exists($model, 'ownedByUser')) {
                $model = $model->ownedByUser($user);
            }

            if (method_exists($model, 'ownedByAffiliation')) {
                $model = $model->ownedByAffiliation($user);
            }
        } catch (Exception $e) {
            Log::warning('Failed to apply role-based filtering', ['error' => $e->getMessage()]);
        }

        return $model;
    }

    /**
     * Persist a simple API request log entry.
     */
    protected function logApiRequest(string $method, string $url, array $data): void
    {
        try {
            $log = new ApiRequestLog();
            $log->method = $method;
            $log->url = $url;
            $log->data = $data;
            $log->save();
        } catch (Exception $e) {
            Log::error('Failed to log API request', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Normalize various truthy/falsey representations to boolean.
     */
    protected function normalizeBoolean($value): ?bool
    {
        if (is_bool($value)) return $value;
        if (is_int($value)) return $value !== 0;
        if (is_string($value)) {
            $v = strtolower(trim($value));
            if (in_array($v, ['1', 'true', 'yes', 'on'], true)) return true;
            if (in_array($v, ['0', 'false', 'no', 'off'], true)) return false;
        }
        return null;
    }

    /**
     * Normalize ID list from various formats.
     */
    protected function normalizeIdList(mixed $ids): array
    {
        if (is_array($ids)) {
            return array_filter(array_map('intval', $ids));
        }

        if (is_string($ids)) {
            return array_filter(array_map('trim', explode(',', $ids)));
        }

        return [];
    }

    /**
     * Get the filter pipeline instance for custom modifications.
     */
    public function getFilterPipeline(): FilterPipeline
    {
        return $this->filterPipeline;
    }

    /**
     * Replace the filter pipeline with a custom one.
     */
    public function setFilterPipeline(FilterPipeline $pipeline): self
    {
        $this->filterPipeline = $pipeline;
        return $this;
    }

    /**
     * Get a human-readable resource name for notifications.
     * Override this method in child repositories for custom names.
     */
    protected function getResourceName(): string
    {
        $className = class_basename($this->model);
        // Convert from CamelCase to spaces (e.g., "UserAccount" -> "User Account")
        return preg_replace('/(?<!^)([A-Z])/', ' $1', $className);
    }
}
