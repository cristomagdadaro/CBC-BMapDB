# Repository Filter System Documentation

## Overview

The optimized AbstractRepoService now uses a modular, pipeline-based filtering architecture that provides:

- **Better maintainability** through separation of concerns
- **Enhanced performance** with optimized query building
- **Greater flexibility** with customizable filter pipelines
- **Robust aggregation** support with dedicated filters
- **Standardized filtering** across all repositories

## Architecture

### Filter Pipeline

The `FilterPipeline` class manages the application of filters in a specific order:

```php
SelectFilter           // 1. Select columns first
RelationshipFilter     // 2. Define relationships (with, count)
ParentFilter           // 3. Parent filtering (most restrictive)
GeoLocationFilter      // 4. Geographic filtering (joins + filters)
SearchFilter           // 5. Text search
AggregationFilter      // 6. Aggregation functions
GroupByFilter          // 7. Aggregation grouping
HavingFilter           // 8. Filter aggregated results
DateRangeFilter        // 9. Date range filtering
SortFilter             // 10. Sort last (after all filtering)
```

### Available Filters

#### 1. SelectFilter
Handles column selection including raw selects.

**Parameters:**
- `select_raw`: Raw SQL SELECT statement
- `select`: Comma-separated list of columns

**Example:**
```php
$params = collect([
    'select' => 'id,name,email',
]);
```

#### 2. RelationshipFilter
Manages eager loading and relationship counts.

**Parameters:**
- `with`: Comma-separated list of relationships to eager load
- `count`: Comma-separated list of relationships to count

**Example:**
```php
$params = collect([
    'with' => 'breeder,location',
    'count' => 'commodities,projects',
]);
```

#### 3. ParentFilter
Filters by parent relationship.

**Parameters:**
- `filter_by_parent_column`: The column name for parent relationship
- `filter_by_parent_id`: The parent ID value

**Example:**
```php
$params = collect([
    'filter_by_parent_column' => 'breeder_id',
    'filter_by_parent_id' => 123,
]);
```

#### 4. GeoLocationFilter
Handles geographic filtering including regions, provinces, cities, and affiliations.

**Parameters:**
- `geo_location_filter`: Type of location filter (region, province, city, affiliation)
- `geo_location_value`: Value to filter by

**Example:**
```php
$params = collect([
    'geo_location_filter' => 'province',
    'geo_location_value' => 'Batangas',
]);
```

#### 5. SearchFilter
Full-text search across model and related models.

**Parameters:**
- `search`: Search term
- `filter`: Specific column to search
- `is_exact`: Boolean for exact match (default: false)
- `with`: Relations to search within

**Example:**
```php
$params = collect([
    'search' => 'John Doe',
    'filter' => 'name',
    'is_exact' => false,
    'with' => 'breeder,location',
]);
```

#### 6. AggregationFilter
Applies aggregation functions (COUNT, SUM, AVG, MAX, MIN).

**Parameters:**
- `aggregate`: Shorthand syntax "function:column as alias"
- `aggregate_function`: Function name (count, sum, avg, max, min)
- `aggregate_column`: Column to aggregate (default: *)
- `aggregate_alias`: Alias for the result

**Examples:**
```php
// Shorthand syntax
$params = collect([
    'aggregate' => 'count:id as total',
]);

// Full syntax
$params = collect([
    'aggregate_function' => 'sum',
    'aggregate_column' => 'amount',
    'aggregate_alias' => 'total_amount',
]);
```

#### 7. GroupByFilter
Groups query results.

**Parameters:**
- `group_by`: Column(s) to group by (string or array)

**Example:**
```php
$params = collect([
    'group_by' => 'category',
]);
```

#### 8. HavingFilter
Filters aggregated results.

**Parameters:**
- `having`: String or array format

**Examples:**
```php
// String format
$params = collect([
    'having' => 'count(*) > 5',
]);

// Array format
$params = collect([
    'having' => [
        'column' => 'total',
        'operator' => '>',
        'value' => 100,
    ],
]);
```

#### 9. DateRangeFilter
Filters by date range.

**Parameters:**
- `date_column`: Column to filter (default: created_at)
- `date_from`: Start date
- `date_to`: End date

**Example:**
```php
$params = collect([
    'date_column' => 'created_at',
    'date_from' => '2024-01-01',
    'date_to' => '2024-12-31',
]);
```

#### 10. SortFilter
Sorts query results.

**Parameters:**
- `sort`: Column to sort by
- `order`: Sort direction (asc, desc - default: desc)

**Example:**
```php
$params = collect([
    'sort' => 'created_at',
    'order' => 'desc',
]);
```

## Usage Examples

### Basic Search
```php
$repo = new CommodityRepo(new Commodity());
$results = $repo->search(collect([
    'search' => 'rice',
    'per_page' => 25,
]));
```

### Advanced Filtering
```php
$results = $repo->search(collect([
    'select' => 'id,name,category',
    'with' => 'breeder,location',
    'search' => 'wheat',
    'geo_location_filter' => 'province',
    'geo_location_value' => 'Batangas',
    'sort' => 'created_at',
    'order' => 'desc',
    'per_page' => 50,
]));
```

### Aggregation Query
```php
$results = $repo->search(collect([
    'select' => 'category',
    'aggregate' => 'count:id as total',
    'group_by' => 'category',
    'having' => 'count(id) > 10',
    'sort' => 'total',
    'order' => 'desc',
    'paginate' => false,
]));
```

### Date Range with Parent Filter
```php
$results = $repo->search(collect([
    'filter_by_parent_column' => 'breeder_id',
    'filter_by_parent_id' => 123,
    'date_from' => '2024-01-01',
    'date_to' => '2024-12-31',
    'with' => 'breeder',
    'per_page' => 25,
]));
```

## Customizing Filters

### Adding Custom Filters

Create a new filter class:

```php
<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CustomFilter extends AbstractFilter
{
    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'custom_param');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $value = $this->getParameter($parameters, 'custom_param');
        // Apply your custom logic
        return $query->where('custom_column', $value);
    }
}
```

### Customizing the Pipeline

Override `createFilterPipeline()` in your repository:

```php
<?php

namespace App\Repository\API;

use App\Repository\AbstractRepoService;
use App\Repository\Filters\FilterPipeline;
use App\Repository\Filters\CustomFilter;

class CustomRepo extends AbstractRepoService
{
    protected function createFilterPipeline(): FilterPipeline
    {
        return parent::createFilterPipeline()
            ->addFilter(new CustomFilter());
    }
}
```

## Performance Optimization

### Best Practices

1. **Use specific selects**: Only select needed columns
   ```php
   'select' => 'id,name,email'
   ```

2. **Limit eager loading**: Only load relationships you need
   ```php
   'with' => 'breeder'  // Not 'breeder,location,commodities,projects'
   ```

3. **Use parent filters**: Most restrictive filters first
   ```php
   'filter_by_parent_id' => 123
   ```

4. **Optimize aggregations**: Use HAVING instead of filtering after
   ```php
   'having' => 'count(*) > 10'
   ```

5. **Disable pagination when not needed**: For exports or aggregations
   ```php
   'paginate' => false
   ```

## Migration Guide

### From Old Method to New System

**Old way:**
```php
public function applyFilters($model, $filters) {
    if ($filters->search) {
        $this->applySearch($model, $filters->search);
    }
    if ($filters->geo_location_value) {
        $model->where('location', $filters->geo_location_value);
    }
    return $model;
}
```

**New way:**
```php
// Filters are automatically applied through the pipeline
$results = $repo->search(collect([
    'search' => $search,
    'geo_location_value' => $location,
]));
```

### Backward Compatibility

The following deprecated methods are still available but now no-ops:
- `applyRawSelectColumns()`
- `applyGroupBy()`
- `applyGeoFilters()`
- `applyAppends()`
- `applyParentFilter()`
- `applySearchFilters()`
- `applySorting()`

Replace them with parameter-based filtering through the pipeline.

## Troubleshooting

### Filter Not Applied

1. Check if `shouldApply()` returns true
2. Verify parameter names match exactly
3. Check filter order in pipeline

### Performance Issues

1. Use `select` to limit columns
2. Reduce eager loaded relationships
3. Add database indexes for filtered columns
4. Use `paginate => false` carefully

### Join Conflicts

The `GeoLocationFilter` automatically prevents duplicate joins. If you have custom joins, ensure they're in the correct filter order.

## API Reference

### AbstractRepoService Methods

- `search(Collection $parameters, bool $withPagination = true, bool $isTrashed = false)`
- `find(int $id, $parameters = null)`
- `create(array $data)`
- `update(int $id, array $data)`
- `delete(int $id)`
- `multiDestroy(array $params)`
- `getFilterPipeline()`
- `setFilterPipeline(FilterPipeline $pipeline)`

### FilterPipeline Methods

- `addFilter(FilterContract $filter)`: Add a filter to the pipeline
- `apply(Builder $query, Collection $parameters)`: Apply all filters
- `clear()`: Remove all filters
- `getFilters()`: Get registered filters
- `createDefault()`: Create default pipeline

## Examples Repository

See the test suite for more examples:
- `tests/Unit/Repository/FilterPipelineTest.php`
- `tests/Feature/Repository/SearchTest.php`

