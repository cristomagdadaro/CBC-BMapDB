<?php

namespace App\Repository\API;

use App\Models\Breeder;
use App\Repository\AbstractRepoService;
use App\Traits\VisualizeData;

class BreederRepo extends AbstractRepoService
{
    public function __construct(Breeder $model)
    {
        parent::__construct($model);
    }

    public function applyFilters($model, $breeder, $geo_location_value = null, $geo_location_filter = null) {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        $model = $model->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation');

        $model = $model
            ->when($breeder, function ($query) use ($breeder) {
                return $query->where('name', $breeder);
            });

        if ($geo_location_value) {
            $model = $model->where('loc_cities.' . $group_by, $geo_location_value);
        }

        return $model;
    }

    public function getBreederLabels($model, $geo_location_value, $is_exact, $geo_location_filter)
    {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        return $model
            ->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
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

    public function linechartData($model, $search = null, $is_exact = false, $group_by = 'name') {
        $group_by = $this->determineLocFilterLevel($group_by);


        $model = $model->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation');

        if ($search) {
            $model = $model->where($group_by, $search);
        }

        $results = $model->selectRaw('name, CONCAT(name, "-", affiliation) as full_name, affiliation, phone as total')
            ->orderBy('total', 'desc')
            ->get();

        $datasets = [];

        foreach ($results->groupBy('name') as $name => $dataGroup) {
            $dataset = [
                'label' => $name,
                'data' => $dataGroup->pluck('name')->toArray(),
                'borderColor' => 'rgba('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).', 1)',
                'fill' => false
            ];

            $datasets[] = $dataset;
        }

        return [
            'labels' => $results->pluck('affiliation')->unique()->values()->toArray(),
            'datasets' => $datasets
        ];
    }

    public function getGroupByGeoLoc($model, $commodity, $geo_location_filter) {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        return $model
            ->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            })
            ->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
            ->groupBy('loc_cities.' . $group_by)
            ->get($group_by)
            ->pluck($group_by)
            ->sort()
            ->values();
    }

    public function getGroupByInstitute($model, $commodity, $geo_location_filter) {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        return $model
            ->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            })
            ->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
            ->join('institutes', 'institutes.id', '=', 'breeders.affiliation')
            ->groupBy('institutes.name')
            ->get('institutes.name')
            ->pluck('name')
            ->sort()
            ->values();
    }
}
