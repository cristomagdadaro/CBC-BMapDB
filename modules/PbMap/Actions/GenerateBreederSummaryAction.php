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

        $builder = $model->newModelQuery();

        $this->breederRepo->applyGeoFilters($builder, $request->collect());
        $this->breederRepo->applySearchFilters($builder, $request->collect());

        $builderA = (clone $builder);
        $this->breederRepo->applyAppends($builderA, $request->collect());
        $breeders = $builderA->select($model->getSearchable())->get();

        $chartDataParams = $request->collect()->put('select_raw', "{$params['group_by']} as label, count(*) as total")
            ->put('group_by', $params['group_by'])
            ->put('sort', 'total')
            ->put('order', 'desc');

        $builderB = (clone $builder)->selectRaw("{$params['group_by']} as label, count(*) as total");
        $this->breederRepo->applySorting($builderB, $chartDataParams);
        $chart_data = $builderB->groupBy($params['group_by'])->get();

        $builderC = (clone $builder)->selectRaw('CONCAT(breeders.fname, breeders.mname, breeders.lname, breeders.suffix) as label, count(*) as total');
        $this->breederRepo->applySorting($builderC, $chartDataParams);
        $breeders_chart = $builderC->groupBy('label')->get();

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

        $breeders = $this->breederRepo->applyFilters($this->breederRepo->checkRole($model), $params['breeder'], $params['geo_location_value'], $params['geo_location_filter'])
            ->select($model->getSearchable())
            ->with(['location', 'commodities','affiliated'])
            ->get();
        $chart_data = $this->breederRepo->applyFilters($model, $params['breeder'], $params['geo_location_value'], $params['geo_location_filter'])
            ->selectRaw("{$params['group_by']} as label, count(*) as total")
            ->groupBy($params['group_by'])
            ->orderBy('total', 'desc')
            ->get();
        $breeders_chart = $this->breederRepo->applyFilters($model, $params['breeder'], $params['geo_location_value'], $params['geo_location_filter'])
            ->selectRaw('CONCAT(fname, mname, lname, suffix) as label, count(*) as total')
            ->groupBy('label')
            ->orderBy('total', 'desc')
            ->get();

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

