<?php

namespace Modules\PbMap\Repositories;

use App\Repository\AbstractRepoService;
use Modules\PbMap\Controllers\CommodityFilters;
use Modules\PbMap\Filters\CommodityFilter;
use Modules\PbMap\Models\Commodity;

class CommodityRepo extends AbstractRepoService
{
    public function __construct(Commodity $model)
    {
        parent::__construct($model);
    }

    public function applyFilters($model, CommodityFilter $filters) {
        $group_by = $this->determineLocFilterLevel($filters->geo_location_filter);

        if ($filters->filter_by_parent_column && $filters->filter_by_parent_id) {
            $model = $model->where($filters->filter_by_parent_column, $filters->filter_by_parent_id);
        }

        $model = $model->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation')->join('users', 'users.id', '=', 'user_id');


        if ($filters->search) {
            $this->applySearch($model, $filters->search, $filters->filter, $filters->is_exact);

            $appendWith = ['breeder', 'location'];
            $this->applyAppends($model, $filters->collect());
            foreach ($appendWith as $table) {
                $relatedModel = $this->model->{$table}()->getModel();
                $this->applyRelationSearch($model, $filters->search, $filters->filter, $filters->is_exact, $table, $relatedModel);
            }

        }

        $model = $model->when($filters->commodities, function ($query) use ($filters) {
            return $query->where('name', $filters->commodities);
        });

        if ($filters->geo_location_value) {
            if ($group_by !== 'affiliation')
                $model = $model->where('loc_cities.' . $group_by, $filters->geo_location_value);
            else
                $model = $model->where('institutes.id', $filters->geo_location_value);
        }

        return $model;
    }

    public function linechartData($model, $search = null, $is_exact = false, $group_by = 'name', $commodity = null) {
        $group_by = $this->determineLocFilterLevel($group_by);


        $model = $model->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation');

        // Apply filters based on search criteria and commodity
        if ($search && $group_by !== 'institute') {
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
        if ($pluck_name !== 'institute')
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
        return $model
            ->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            })
            ->join('breeders', 'breeder_id', '=', 'breeders.id')
            ->join('institutes', 'institutes.id', '=', 'breeders.affiliation')
            ->groupBy('breeders.affiliation')
            ->get('institutes.name')
            ->sort()
            ->pluck('name');
    }

    public function getCommodityLabels($model, $geo_location_value, $is_exact, $geo_location_filter)
    {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        if ($group_by !== 'institute')
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


        $model = $model->with(['breeder']);

        return $model
            ->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation')
            ->join('users', 'users.id','=','user_id')
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

    public function getGroupByInstitute($model, $commodity, $geo_location_filter) {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        return $model
            ->select('institutes.name','institutes.id')
            ->when($commodity, function ($query) use ($commodity) {
                if ($commodity)
                    return $query->where('commodities.name', $commodity);
                return $query;
            })
            ->join('loc_cities', 'loc_cities.id', '=', 'geolocation')
            ->join('users', 'users.id','=','user_id')
            ->join('institutes', 'institutes.id', '=', 'users.affiliation')
            ->groupBy('institutes.name')
            ->get('institutes.name')
            ->sort()
            ->values();
    }
}
