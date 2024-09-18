<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
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

    public function index(GetBreederRequest $request)
    {
        $this->service->appendWith(['affiliated', 'location']);
        $this->service->appendCount(['commodities']);
        if (auth()->user()->getRole() !== Role::ADMIN->value) {
            $this->service->appendCondition(auth()->id());
        }

        $data = $this->service->search(new Collection($request->validated()));
        //return new BaseCollection($data);
        return $data;
    }

    public function summary(GetBreederRequest $request)
    {
        $model = $this->service->model;
        $geo_location_filter = $request->validated('geo_location_filter') ?? 'region';
        $geo_location_value = $request->validated('geo_location_value') ?? '';
        $is_exact = $request->validated('is_exact');
        $commodity = $request->all()['commodity'] ?? null;
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        $breeders = $this->applyFilters($model, $geo_location_value, $geo_location_filter)
            ->select($model->getSearchable())
            ->with(['location', 'commodities','affiliated'])
            ->get();
       $chart_data = $this->applyFilters($model, $geo_location_value, $geo_location_filter)
            ->selectRaw("$group_by as label, count(*) as total")
            ->groupBy($group_by)
            ->orderBy('total', 'desc')
            ->get();
        $breeders_chart = $this->applyFilters($model, $geo_location_value, $geo_location_filter)
            ->selectRaw('name as label, count(*) as total')
            ->groupBy('name')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'params' => [
                'commodity' => $commodity,
                'group_by' => $group_by,
                'geo_location_filter' => $geo_location_filter,
                'geo_location_value' => $geo_location_value,
                'is_exact' => $is_exact,
            ],
            'group_search_labels' => $this->getGroupByGeoLoc($model, $commodity, $geo_location_filter),
            'group_search_institute' => $this->getGroupByInstitute($model, $commodity, $geo_location_filter),
            'raw_data' => $breeders,
            'raw_data_labels' => $this->getBreederLabels($model, $geo_location_value, $is_exact, $geo_location_filter),
            'chart_data' => $chart_data,
            'chart_labels' => $breeders_chart,
            'linechart_data' => $this->linechartData($model, $geo_location_value, $is_exact, $geo_location_filter),
        ]);
    }

    private function applyFilters($model, $geo_location_value, $geo_location_filter) {
        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        $group_by = $this->determineLocFilterLevel($geo_location_filter);

        $model = $model->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation');

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
        $pluck_name = $this->determineLocFilterLevel($geo_location_filter);

        return $model
            ->when($commodity, function ($query) use ($commodity) {
                return $query->where('name', $commodity);
            })
            ->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
            ->groupBy('loc_cities.' . $pluck_name)
            ->get($pluck_name)
            ->pluck($pluck_name)
            ->sort()
            ->values();
    }

    public function getGroupByInstitute($model, $commodity, $geo_location_filter) {
        $pluck_name = $this->determineLocFilterLevel($geo_location_filter);

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

    private function determineLocFilterLevel($geo_location_filter): string
    {
        return match ($geo_location_filter) {
            'province' => 'provDesc',
            'region' => 'regDesc',
            default => 'cityDesc',
        };
    }

    public function store(CreateBreederRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        return $this->service->create($data);
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
        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');
        return $this->service->update($id, $data);
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
