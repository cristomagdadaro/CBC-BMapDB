<?php

namespace App\Repository\API;

use App\Models\Commodity;
use App\Repository\AbstractRepoService;

class CommodityRepo extends AbstractRepoService
{
    public function __construct(Commodity $model)
    {
        parent::__construct($model);
    }

    public function applyFilters($model, $commodity, $geo_location_value, $geo_location_filter) {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        if ($this->filterByParent && $this->filterByParent['column'] && $this->filterByParent['value']) {
            $model = $model->where($this->filterByParent['column'], $this->filterByParent['value']);
        }

        $model = $model->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation');

        $model = $model
            ->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            });

        if ($geo_location_value) {
            $model = $model->where('loc_cities.' . $group_by, $geo_location_value);
        }

        return $model;
    }

    public function linechartData($model, $search = null, $is_exact = false, $group_by = 'name', $commodity = null) {
        $group_by = $this->determineLocFilterLevel($group_by);


        $model = $model->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation');

        // Apply filters based on search criteria and commodity
        if ($search) {
            $model = $model->where($group_by, $search);
        }

        if ($commodity) {
            $model = $model->where('name', $commodity);
        }

        // Fetch results
        $results = $model->selectRaw('name, CONCAT(name, "-", variety) as full_name, variety, population as total')
            ->whereNotNull('population')
            ->orderBy('name', 'asc')
            ->orderBy('variety', 'asc')
            ->get();

        $datasets = [];
        foreach ($results->groupBy('name') as $name => $dataGroup) {
            $dataset = [
                'label' => $name,
                'data' => $dataGroup->pluck('total')->toArray(),
                'borderColor' => 'rgba('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).', 1)',
                'fill' => false
            ];
            $datasets[] = $dataset;
        }

        return [
            'labels' => $results->pluck('full_name')->unique()->values()->toArray(),
            'datasets' => $datasets
        ];
    }

    public function getGroupByGeoLoc($model, $commodity, $geo_location_filter)
    {
        $pluck_name = $this->determineLocFilterLevel($geo_location_filter);

        return $model
            ->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            })
            ->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation')
            ->groupBy('loc_cities.' . $pluck_name)
            ->get($pluck_name)
            ->pluck($pluck_name)
            ->sort()
            ->values();
    }

    public function getCommodityLabels($model, $geo_location_value, $is_exact, $geo_location_filter)
    {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        return $model
            ->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation')
            ->when($geo_location_value, function ($query) use ($geo_location_value, $is_exact, $group_by) {
                if ($is_exact === 'true') {
                    return $query->where($group_by, $geo_location_value);
                } else {
                    return $query->where($group_by, 'like', '%'.$geo_location_value.'%');
                }
            })
            ->get()
            ->pluck('name')
            ->unique()
            ->sort()
            ->values();
    }
}
