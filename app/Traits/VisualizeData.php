<?php

namespace App\Traits;

trait VisualizeData {

    public function applyFilters($model, array $filters)
    {
        foreach ($filters as $filterKey => $filterValue) {
            if (!empty($filterValue)) {
                $model = $this->applyFilter($model, $filterKey, $filterValue);
            }
        }

        return $model;
    }

    private function applyFilter($model, $filterKey, $filterValue)
    {
        $filterMapping = [
            'geo_location_value' => 'loc_cities.' . $this->determineLocFilterLevel($filterKey),
            // Add more filters here as needed
        ];

        if (isset($filterMapping[$filterKey])) {
            $model = $model->where($filterMapping[$filterKey], $filterValue);
        }

        return $model;
    }
}
