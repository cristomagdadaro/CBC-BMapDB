<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Base implementation for filters with common utilities.
 */
abstract class AbstractFilter implements FilterContract
{
    /**
     * Get a parameter value with optional default.
     *
     * @param Collection $parameters
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getParameter(Collection $parameters, string $key, mixed $default = null): mixed
    {
        return $parameters->get($key, $default);
    }

    /**
     * Normalize a boolean parameter.
     *
     * @param mixed $value
     * @return bool|null
     */
    protected function normalizeBoolean(mixed $value): ?bool
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
     * Check if a parameter exists and is not empty.
     *
     * @param Collection $parameters
     * @param string $key
     * @return bool
     */
    protected function hasParameter(Collection $parameters, string $key): bool
    {
        return $parameters->has($key) && !empty($parameters->get($key));
    }
}

