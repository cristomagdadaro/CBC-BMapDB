<?php

namespace Modules\PbMap\Repositories;

use App\Repository\AbstractRepoService;
use Modules\PbMap\Models\Breeder;
use Illuminate\Database\Eloquent\Builder;

class BreederRepo extends AbstractRepoService
{
    public function __construct(Breeder $model)
    {
        parent::__construct($model);
    }

    public function applyFilters($model, $breeder, $geo_location_value = null, $geo_location_filter = null) {
        $builder = $model instanceof Builder ? $model : $model->newQuery();

        $filterType = $geo_location_filter === 'institute' ? 'affiliation' : $geo_location_filter;

        $params = collect([
            'geo_location_filter' => $filterType,
            'geo_location_value' => $geo_location_value,
        ]);

        if ($breeder) {
            $params = $params
                ->put('search', $breeder)
                ->put('filter', 'fname,mname,lname,suffix');
        }

        return $this->getFilterPipeline()->apply($builder, $params);
    }

    public function getBreederLabels($model, $geo_location_value, $is_exact, $geo_location_filter)
    {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        if ($group_by !== 'institute')
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

        return $model
            ->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
            ->join('institutes', 'institutes.id', '=', 'users.affiliation')
            ->whereHas('breeder.affiliated', function ($instituteQuery) use ($geo_location_value) {
                $instituteQuery->where('institutes.name', $geo_location_value);
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

        if ($search && $group_by !== 'institute') {
            $model = $model->where($group_by, $search);
        }

        $results = $model->selectRaw('CONCAT(fname," ",lname," ", "-", affiliation) as full_name, affiliation, mobile_no as total')
            ->orderBy('total', 'desc')
            ->get();

        $datasets = [];

        foreach ($results->groupBy('full_name') as $name => $dataGroup) {
            $dataset = [
                'label' => $name,
                'data' => $dataGroup->pluck('full_name')->toArray(),
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
        if ($group_by !== 'institute')
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
        return $model
            ->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            })
            ->join('institutes', 'institutes.id', '=', 'breeders.affiliation')
            ->groupBy('breeders.affiliation')
            ->get('institutes.name')
            ->sort()
            ->pluck('name');
    }

    public function getGroupByInstitute($model, $commodity, $geo_location_filter) {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        return $model
            ->select('institutes.name','institutes.id')
            ->when($commodity, function ($query) use ($commodity) {
                if ($commodity)
                    return $query->where('breeders.name', $commodity);
                return $query;
            })
            ->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
            ->join('institutes', 'institutes.id', '=', 'breeders.affiliation')
            ->groupBy('institutes.name')
            ->get('institutes.name')
            ->sort()
            ->values();
    }
}
