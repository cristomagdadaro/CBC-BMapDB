<?php
namespace Modules\PbMap\Filters;

class CommodityFilter
{
    public string|null $geo_location_value;
    public string|null $geo_location_filter;
    public string|null $filter_by_parent_column;
    public string|null $filter_by_parent_id;
    public string|null $filter;
    public string|null $is_exact;
    public string|null $group_by;
    public string|null $commodities;
    public string|null $search;

    public function __construct($geo_location_value,
                                $geo_location_filter,
                                $filter_by_parent_column,
                                $filter_by_parent_id,
                                $filter,
                                $search,
                                $is_exact,
                                $commodities,
                                $group_by
    )
    {
        $this->geo_location_value = $geo_location_value;
        $this->geo_location_filter = $geo_location_filter;
        $this->filter_by_parent_column = $filter_by_parent_column;
        $this->filter_by_parent_id = $filter_by_parent_id;
        $this->filter = $filter;
        $this->search = $search;
        $this->is_exact = $is_exact;
        $this->commodities = $commodities;
        $this->group_by = $group_by;
    }
}
