# Optimized Repository Service - Quick Start Guide

## What's New?

The AbstractRepoService has been completely rebuilt with a **modular filter pipeline architecture** that provides:

✅ **Better Performance** - Optimized query building with smart join management  
✅ **More Maintainable** - Each filter is a separate, testable class  
✅ **Highly Flexible** - Easily customize or extend filters per repository  
✅ **Robust Aggregations** - Built-in support for COUNT, SUM, AVG, MAX, MIN  
✅ **Standardized API** - Consistent parameter names across all repositories  

## Quick Examples

### Simple Search
```php
$repo = new CommodityRepo(new Commodity());
$results = $repo->search(collect([
    'search' => 'rice',
    'per_page' => 25,
]));
```

### Geographic Filtering
```php
$results = $repo->search(collect([
    'geo_location_filter' => 'province',
    'geo_location_value' => 'Batangas',
    'with' => 'breeder,location',
    'sort' => 'name',
    'order' => 'asc',
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
]));
```

### Complex Query
```php
$results = $repo->search(collect([
    'select' => 'id,name,category,created_at',
    'with' => 'breeder,location',
    'count' => 'commodities',
    'search' => 'wheat',
    'filter_by_parent_column' => 'breeder_id',
    'filter_by_parent_id' => 123,
    'geo_location_filter' => 'region',
    'geo_location_value' => 'Region IV-A',
    'date_from' => '2024-01-01',
    'date_to' => '2024-12-31',
    'sort' => 'created_at',
    'order' => 'desc',
    'per_page' => 50,
]));
```

## Available Parameters

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `search` | string | Search term | `'rice'` |
| `filter` | string | Specific column to search | `'name'` |
| `is_exact` | bool | Exact match search | `true` |
| `with` | string | Eager load relationships | `'breeder,location'` |
| `count` | string | Count relationships | `'commodities'` |
| `select` | string | Columns to select | `'id,name,email'` |
| `select_raw` | string | Raw SELECT statement | `'COUNT(*) as total'` |
| `filter_by_parent_column` | string | Parent column name | `'breeder_id'` |
| `filter_by_parent_id` | int | Parent ID | `123` |
| `geo_location_filter` | string | Location type | `'province'` |
| `geo_location_value` | string | Location value | `'Batangas'` |
| `aggregate` | string | Aggregation shorthand | `'count:id as total'` |
| `aggregate_function` | string | Aggregation function | `'sum'` |
| `aggregate_column` | string | Column to aggregate | `'amount'` |
| `aggregate_alias` | string | Result alias | `'total'` |
| `group_by` | string/array | Group by column(s) | `'category'` |
| `having` | string/array | HAVING clause | `'count(*) > 5'` |
| `date_column` | string | Date column to filter | `'created_at'` |
| `date_from` | string | Start date | `'2024-01-01'` |
| `date_to` | string | End date | `'2024-12-31'` |
| `sort` | string | Sort column | `'created_at'` |
| `order` | string | Sort direction | `'desc'` |
| `per_page` | int | Items per page | `25` |
| `page` | int | Current page | `1` |
| `paginate` | bool | Enable pagination | `true` |

## Filter Pipeline Order

Filters are applied in this optimized order:

1. **SelectFilter** - Define columns to select
2. **RelationshipFilter** - Eager load relationships
3. **ParentFilter** - Filter by parent (most restrictive)
4. **GeoLocationFilter** - Geographic filtering with joins
5. **SearchFilter** - Text search across columns
6. **AggregationFilter** - Apply aggregation functions
7. **GroupByFilter** - Group results
8. **HavingFilter** - Filter aggregated results
9. **DateRangeFilter** - Filter by date range
10. **SortFilter** - Sort results (last for efficiency)

## Creating Custom Filters

### Step 1: Create Filter Class
```php
<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class StatusFilter extends AbstractFilter
{
    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'status');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $status = $this->getParameter($parameters, 'status');
        return $query->where('status', $status);
    }
}
```

### Step 2: Add to Repository Pipeline
```php
<?php

namespace Modules\PbMap\Repositories;

use App\Repository\AbstractRepoService;
use App\Repository\Filters\FilterPipeline;
use App\Repository\Filters\StatusFilter;

class CommodityRepo extends AbstractRepoService
{
    protected function createFilterPipeline(): FilterPipeline
    {
        return parent::createFilterPipeline()
            ->addFilter(new StatusFilter());
    }
}
```

### Step 3: Use in Queries
```php
$results = $repo->search(collect([
    'status' => 'active',
    'search' => 'rice',
]));
```

## Migration from Old Code

### Before (Custom Methods)
```php
public function applyFilters($model, $filters) {
    if ($filters->search) {
        $model->where('name', 'like', "%{$filters->search}%");
    }
    if ($filters->location) {
        $model->join('loc_cities', 'loc_cities.id', '=', 'table.geolocation')
              ->where('loc_cities.provDesc', $filters->location);
    }
    return $model;
}

// Usage
$results = $repo->applyFilters($model, $filters)->get();
```

### After (Filter Pipeline)
```php
// No custom code needed!
// Just use parameters:

$results = $repo->search(collect([
    'search' => 'rice',
    'geo_location_filter' => 'province',
    'geo_location_value' => 'Batangas',
]));
```

## Performance Tips

### ✅ DO
- Use `select` to limit columns
- Use `with` for necessary relationships only
- Use parent filters for most restrictive filtering
- Use indexes on filtered columns
- Use `paginate => false` only when needed

### ❌ DON'T
- Select all columns when you only need a few
- Eager load all relationships
- Search without indexes
- Use raw SQL when parameters work
- Forget to add `group_by` with aggregations

## Common Patterns

### Data Export (No Pagination)
```php
$data = $repo->search(collect([
    'select' => 'id,name,email,created_at',
    'paginate' => false,
]));
```

### Dashboard Statistics
```php
$stats = $repo->search(collect([
    'select' => 'category',
    'aggregate' => 'count:* as total',
    'group_by' => 'category',
    'paginate' => false,
]));
```

### Filtered List with Relations
```php
$items = $repo->search(collect([
    'with' => 'breeder,location',
    'search' => $request->search,
    'geo_location_filter' => $request->location_type,
    'geo_location_value' => $request->location_value,
    'sort' => 'created_at',
    'order' => 'desc',
    'per_page' => 25,
]));
```

### Analytics Query
```php
$analytics = $repo->search(collect([
    'select_raw' => 'DATE(created_at) as date, COUNT(*) as count',
    'group_by' => 'date',
    'date_from' => '2024-01-01',
    'date_to' => '2024-12-31',
    'sort' => 'date',
    'order' => 'asc',
    'paginate' => false,
]));
```

## Troubleshooting

### Issue: Filters not working
**Solution:** Check parameter names match exactly (case-sensitive)

### Issue: Duplicate joins error
**Solution:** GeoLocationFilter prevents this automatically - check custom filters

### Issue: Slow queries
**Solution:** 
1. Use `select` to limit columns
2. Add database indexes
3. Reduce eager loaded relationships
4. Use `explain` to analyze queries

### Issue: Aggregation returns wrong results
**Solution:** Always use `group_by` with aggregations and consider `having` clause

## File Structure

```
app/Repository/
├── AbstractRepoService.php          # Main repository base class
├── ErrorRepository.php               # Error handling
└── Filters/
    ├── AbstractFilter.php           # Base filter class
    ├── FilterContract.php           # Filter interface
    ├── FilterPipeline.php           # Pipeline manager
    ├── AggregationFilter.php        # COUNT, SUM, AVG, etc.
    ├── DateRangeFilter.php          # Date range filtering
    ├── GeoLocationFilter.php        # Geographic filtering
    ├── GroupByFilter.php            # GROUP BY clause
    ├── HavingFilter.php             # HAVING clause
    ├── ParentFilter.php             # Parent relationship filtering
    ├── RelationshipFilter.php       # Eager loading (with, count)
    ├── SearchFilter.php             # Text search
    ├── SelectFilter.php             # Column selection
    └── SortFilter.php               # ORDER BY clause
```

## Need Help?

📖 Full documentation: `docs/REPOSITORY_FILTER_SYSTEM.md`  
🐛 Report issues: Create a ticket with query details  
💡 Feature requests: Extend filters or create custom ones  

---

**Version:** 2.0  
**Last Updated:** October 7, 2025

