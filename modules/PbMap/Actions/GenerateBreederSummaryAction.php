<?php

namespace Modules\PbMap\Actions;

use Illuminate\Http\JsonResponse;
use Modules\PbMap\Repositories\BreederRepo;
use Modules\PbMap\Requests\GetBreederRequest;

class GenerateBreederSummaryAction
{
    private BreederRepo $breederRepo;

    public function __construct(BreederRepo $breederRepo)
    {
        $this->breederRepo = $breederRepo;
    }

    public function execute(GetBreederRequest $request): JsonResponse
    {
        if (auth()->check()) {
            return $this->summaryPrivate($request);
        }
        return $this->summaryPublic($request);
    }

    private function summaryPrivate(GetBreederRequest $request): JsonResponse
    {
        $model = $this->breederRepo->model;
        $params = $this->getSummaryParams($request);

        $baseParams = collect([
            'geo_location_filter' => $params['geo_location_filter'],
            'geo_location_value' => $params['geo_location_value'],
            'is_exact' => $params['is_exact'],
            'paginate' => false,
        ]);

        if ($params['breeder']) {
            $baseParams = $baseParams
                ->put('search', $params['breeder'])
                ->put('filter', 'fname,mname,lname,suffix');
        }

        $breeders = $this->breederRepo->search(
            $baseParams->put('select', implode(',', $model->getSearchable())),
            false
        );

        $chartDataParams = $baseParams
            ->put('select_raw', "{$params['group_by']} as label, count(*) as total")
            ->put('group_by', $params['group_by'])
            ->put('sort', 'total')
            ->put('order', 'desc');

        $chart_data = $this->breederRepo->search($chartDataParams, false);

        $breeders_chart = $this->breederRepo->search(
            $baseParams
                ->put('select_raw', 'CONCAT(breeders.fname, breeders.mname, breeders.lname, breeders.suffix) as label, count(*) as total')
                ->put('group_by', 'label')
                ->put('sort', 'total')
                ->put('order', 'desc'),
            false
        );

        return response()->json([
            'params' => $params,
            'group_search_labels' => $this->breederRepo->getGroupByGeoLoc($model, $params['breeder'], $params['geo_location_filter']),
            'group_search_institute' => $this->breederRepo->getGroupByInstitute($model, $params['breeder'], $params['geo_location_filter']),
            'raw_data' => $breeders,
            'raw_data_labels' => $this->breederRepo->getBreederLabels($model, $params['geo_location_value'], $params['is_exact'], $params['geo_location_filter']),
            'chart_data' => $chart_data,
            'chart_labels' => $breeders_chart,
            'linechart_data' => $this->breederRepo->linechartData($model, $params['geo_location_value'], $params['is_exact'], $params['geo_location_filter']),
        ]);
    }

    private function summaryPublic(GetBreederRequest $request): JsonResponse
    {
        $model = $this->breederRepo->model;
        $params = $this->getSummaryParams($request);

        $baseParams = collect([
            'geo_location_filter' => $params['geo_location_filter'],
            'geo_location_value' => $params['geo_location_value'],
            'is_exact' => $params['is_exact'],
            'with' => 'location,commodities,affiliated',
            'paginate' => false,
        ]);

        if ($params['breeder']) {
            $baseParams = $baseParams
                ->put('search', $params['breeder'])
                ->put('filter', 'fname,mname,lname,suffix');
        }

        $breeders = $this->breederRepo->search(
            $baseParams->put('select', implode(',', $model->getSearchable())),
            false
        );

        $chart_data = $this->breederRepo->search(
            $baseParams
                ->put('select_raw', "{$params['group_by']} as label, count(*) as total")
                ->put('group_by', $params['group_by'])
                ->put('sort', 'total')
                ->put('order', 'desc'),
            false
        );

        $breeders_chart = $this->breederRepo->search(
            $baseParams
                ->put('select_raw', 'CONCAT(breeders.fname, breeders.mname, breeders.lname, breeders.suffix) as label, count(*) as total')
                ->put('group_by', 'label')
                ->put('sort', 'total')
                ->put('order', 'desc'),
            false
        );

        return response()->json([
            'params' => $params,
            'group_search_labels' => $this->breederRepo->getGroupByGeoLoc($model, $params['breeder'], $params['geo_location_filter']),
            'group_search_institute' => $this->breederRepo->getGroupByInstitute($model, $params['breeder'], $params['geo_location_filter']),
            'raw_data' => $breeders,
            'raw_data_labels' => $this->breederRepo->getBreederLabels($model, $params['geo_location_value'], $params['is_exact'], $params['geo_location_filter']),
            'chart_data' => $chart_data,
            'chart_labels' => $breeders_chart,
            'linechart_data' => $this->breederRepo->linechartData($model, $params['geo_location_value'], $params['is_exact'], $params['geo_location_filter']),
        ]);
    }

    private function getSummaryParams(GetBreederRequest $request): array
    {
        $geo_location_filter = $request->validated('geo_location_filter') ?? 'region';
        $geo_location_value = $request->validated('geo_location_value') ?? '';
        $is_exact = $request->validated('is_exact');
        $breeder = $request->input('breeder');
        $group_by = $this->breederRepo->determineLocFilterLevel($geo_location_filter);

        return [
            'breeder' => $breeder,
            'group_by' => $group_by,
            'geo_location_filter' => $geo_location_filter,
            'geo_location_value' => $geo_location_value,
            'is_exact' => $is_exact,
        ];
    }
}

