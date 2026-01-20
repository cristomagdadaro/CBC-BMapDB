<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Handles geographic location filtering including regions, provinces, cities, and affiliations.
 */
class GeoLocationFilter extends AbstractFilter
{
    private const GEO_TABLE_LOC_CITIES = 'loc_cities';
    private const GEO_TABLE_USERS = 'users';
    private const GEO_TABLE_INSTITUTES = 'institutes';
    private const COL_ID = 'id';
    private const COL_NAME = 'name';
    private const COL_GEOLOCATION = 'geolocation';
    private const COL_USER_ID = 'user_id';
    private const COL_BREEDER_ID = 'breeder_id';

    private const LOC_COL_PROVINCE = 'provDesc';
    private const LOC_COL_REGION = 'regDesc';
    private const LOC_COL_CITY = 'cityDesc';

    private array $joinedTables = [];

    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'geo_location_filter') ||
               $this->hasParameter($parameters, 'geo_location_value');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $this->joinedTables = [];
        $model = $query->getModel();
        $table = $model->getTable();

        $filterType = $this->getParameter($parameters, 'geo_location_filter');
        $filterValue = $this->getParameter($parameters, 'geo_location_value');

        // Apply necessary joins based on model structure
        $this->applyGeoJoins($query, $table, $filterType);

        // Apply specific value filtering if provided
        if ($filterValue && $filterType) {
            $this->applyGeoValueFilter($query, $filterType, $filterValue);
        }

        return $query;
    }

    /**
     * Apply necessary joins for geographic filtering.
     */
    private function applyGeoJoins(Builder $query, string $table, ?string $filterType): void
    {
        // Join loc_cities if geolocation column exists
        if (Schema::hasColumn($table, self::COL_GEOLOCATION)) {
            $this->safeJoin($query, self::GEO_TABLE_LOC_CITIES,
                self::GEO_TABLE_LOC_CITIES . '.' . self::COL_ID,
                '=',
                $table . '.' . self::COL_GEOLOCATION
            );
        }

        // Join users if user_id column exists
        if (Schema::hasColumn($table, self::COL_USER_ID)) {
            $this->safeJoin($query, self::GEO_TABLE_USERS,
                self::GEO_TABLE_USERS . '.' . self::COL_ID,
                '=',
                $table . '.' . self::COL_USER_ID
            );
        }

        // Handle affiliation filtering with additional joins
        if ($filterType === 'affiliation') {
            // Join breeders table if breeder_id exists
            if (Schema::hasColumn($table, self::COL_BREEDER_ID)) {
                $this->safeJoin($query, 'breeders',
                    'breeders.' . self::COL_ID,
                    '=',
                    $table . '.' . self::COL_BREEDER_ID
                );
            }

            // Join institutes through breeders.affiliation
            $this->safeJoin($query, self::GEO_TABLE_INSTITUTES,
                self::GEO_TABLE_INSTITUTES . '.' . self::COL_ID,
                '=',
                'breeders.affiliation'
            );
        }
    }

    /**
     * Apply value-based geographic filtering.
     */
    private function applyGeoValueFilter(Builder $query, string $filterType, mixed $filterValue): void
    {
        if ($filterType === 'affiliation') {
            $query->where(self::GEO_TABLE_INSTITUTES . '.' . self::COL_NAME, $filterValue);
        } else {
            $column = $this->mapFilterTypeToColumn($filterType);
            if ($column && Schema::hasColumn(self::GEO_TABLE_LOC_CITIES, $column)) {
                $query->where(self::GEO_TABLE_LOC_CITIES . '.' . $column, $filterValue);
            }
        }
    }

    /**
     * Map filter type to database column.
     */
    private function mapFilterTypeToColumn(string $filterType): ?string
    {
        return match ($filterType) {
            'province' => self::LOC_COL_PROVINCE,
            'region' => self::LOC_COL_REGION,
            'city' => self::LOC_COL_CITY,
            default => null,
        };
    }

    /**
     * Safely join a table only if it hasn't been joined already.
     */
    private function safeJoin(Builder $query, string $table, string $first, string $operator, string $second): void
    {
        if (in_array($table, $this->joinedTables, true)) {
            return;
        }

        $query->leftJoin($table, $first, $operator, $second);
        $this->joinedTables[] = $table;
    }
}

