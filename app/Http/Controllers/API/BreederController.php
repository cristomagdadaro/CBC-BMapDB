<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetBreederRequest;
use App\Http\Requests\CreateBreederRequest;
use App\Http\Requests\UpdateBreederRequest;
use App\Http\Requests\DeleteBreederRequest;
use App\Models\Breeder;
use App\Repository\API\BreederRepo;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BaseCollection;

class BreederController extends BaseController
{
    public function __construct(BreederRepo $breederRepository)
    {
        $this->service = $breederRepository;
    }

    public function index(GetBreederRequest $request): BaseCollection
    {
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function summary(GetBreederRequest $request)
    {
        $model = $this->service->model;
        $group_by = $request->validated('group_by') ?? 'region';
        $search = $request->validated('search') ?? '';
        $is_exact = $request->validated('is_exact');

        $breeders = $this->applyFilters($model, $search, $is_exact, $group_by)->get();
        $chart_data = $this->applyFilters($model->selectRaw("$group_by as label, count(*) as total"), $search, $is_exact, $group_by)
            ->groupBy($group_by)
            ->orderBy('total', 'desc')
            ->get();
        $breeders_chart = $this->applyFilters($model->selectRaw('name as label, count(*) as total'), $search, $is_exact, $group_by)
            ->groupBy('name')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'commodity_labels' => $this->getBreederLabels($model, $search, $is_exact, $group_by),
            'group_search_labels' => $this->getGroupByLabels($model, $group_by),
            'chart_data' => $chart_data,
            'commodities_chart' => $breeders_chart,
            'commodities_linechart' => $this->linechartData($model, $search, $is_exact, $group_by),
            'commodities' => $breeders,
        ]);
    }

    private function applyFilters($model, $search, $is_exact, $group_by) {
        $breeder = null;
        return $model->when(null, function ($query) use ($breeder) {
            return $query->where('name', $breeder);
        })
            ->when($search, function ($query) use ($search, $is_exact, $group_by) {
                if ($is_exact === 'true') {
                    return $query->where($group_by, $search);
                } else {
                    return $query->where($group_by, 'like', '%'.$search.'%');
                }
            })->with(['cityDesc']);
    }

    public function getBreederLabels($model, $search, $is_exact, $group_by)
    {
        return $model->when($search, function ($query) use ($search, $is_exact, $group_by) {
            if ($is_exact === 'true') {
                return $query->where($group_by, $search);
            } else {
                return $query->where($group_by, 'like', '%'.$search.'%');
            }
        })
        ->get()
        ->pluck('name')
        ->unique()
        ->sort()
        ->values();
    }

    public function linechartData($model, $search = null, $is_exact = false, $group_by = 'name') {
        if ($search) {
            $model = $model->where($group_by, $search);
        }

        $results = $model->selectRaw('name, CONCAT(name, "-", agency) as full_name, agency, phone as total')
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
            'labels' => $results->pluck('agency')->unique()->values()->toArray(),
            'datasets' => $datasets
        ];
    }

    public function getGroupByLabels($model, $group_by) {
        return $model->get()->pluck($group_by)->unique()->sort()->values();
    }

    public function store(CreateBreederRequest $request): JsonResponse
    {
        return $this->service->create($request->validated());
    }

    public function show(int $id): JsonResponse
    {
        return $this->service->find($id);
    }

    public function noPageSearch(int $id, GetBreederRequest $request)
    {
        $this->service->appendWith(['commodities']);
        $data = $this->service->search(new Collection($request->validated()), false);
        if (count($data) === 0) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json(['data' => $data[0]->commodities]);
    }

    public function update(UpdateBreederRequest $request, int $id): JsonResponse
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id): JsonResponse
    {
        return $this->service->delete($id);
    }

    public function multiDestroy(DeleteBreederRequest $request): JsonResponse
    {
        return $this->service->multiDestroy($request->validated());
    }
}
