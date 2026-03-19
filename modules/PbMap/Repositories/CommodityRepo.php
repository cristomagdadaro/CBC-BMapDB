<?php

namespace Modules\PbMap\Repositories;

use App\Repository\AbstractRepoService;
use Modules\PbMap\Models\Commodity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class CommodityRepo extends AbstractRepoService
{
    protected array $characteristicKeys = [
        'weight',
        'length',
        'width',
        'shape',
        'skin_color',
        'skin_texture',
        'flesh_color',
        'flesh_texture',
        'flesh_flavor',
        'aroma',
        'root_flesh_color',
        'root_cortex_color',
        'root_skin_color',
        'root_shape',
        'tuber_flesh_color',
        'tuber_cortex_color',
        'tuber_skin_color',
        'tuber_shape',
    ];

    public function __construct(Commodity $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): JsonResponse
    {
        if (array_key_exists('photo', $data)) {
            $data['photo'] = $this->storeCommodityPhoto($data['photo']);
        }

        [$commodityData, $characteristics] = $this->splitCharacteristics($data);

        return DB::transaction(function () use ($commodityData, $characteristics) {
            $model = $this->model->create($commodityData);
            $this->saveCharacteristics($model, $characteristics);
            return $this->jsonResponse(self::RESPONSE_CREATED, $model->load('characteristics'));
        });
    }

    public function update(int $id, array $data, ?Model $resource = null): JsonResponse
    {
        if (array_key_exists('photo', $data)) {
            $data['photo'] = $this->storeCommodityPhoto($data['photo']);
        }

        [$commodityData, $characteristics] = $this->splitCharacteristics($data);

        $model = $resource ?? $this->model->findOrFail($id);

        return DB::transaction(function () use ($model, $commodityData, $characteristics) {
            $model->update($commodityData);
            $this->saveCharacteristics($model, $characteristics);
            return $this->jsonResponse(self::RESPONSE_UPDATED, $model->load('characteristics'));
        });
    }

    public function applyFilters($model, Collection|array $filters) {
        $builder = $model instanceof Builder ? $model : $model->newQuery();
        $params = $filters instanceof Collection ? $filters : collect($filters);

        if ($params->get('geo_location_filter') === 'institute') {
            $params = $params->put('geo_location_filter', 'affiliation');
        }

        if ($params->get('search') && !$params->get('with')) {
            $params = $params->put('with', 'breeder,location');
        }

        return $this->getFilterPipeline()->apply($builder, $params);
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

    private function storeCommodityPhoto(?string $photoData): ?string
    {
        if (!$photoData) {
            return null;
        }

        if (filter_var($photoData, FILTER_VALIDATE_URL)) {
            return $photoData;
        }

        $normalized = ltrim($photoData, '/');
        if (str_starts_with($normalized, 'storage/')) {
            return $normalized;
        }

        if (str_starts_with($normalized, 'data:image/')) {
            if (!preg_match('/^data:image\/(\w+);base64,/', $normalized, $matches)) {
                return null;
            }

            $extension = strtolower($matches[1] ?? 'jpg');
            $base64 = substr($normalized, strpos($normalized, ',') + 1);
            $binary = base64_decode($base64, true);

            if ($binary === false) {
                return null;
            }

            $filename = 'commodity-photos/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($filename, $binary);

            return 'storage/' . $filename;
        }

        return $normalized;
    }

    private function splitCharacteristics(array $data): array
    {
        $characteristics = Arr::only($data, $this->characteristicKeys);
        $commodityData = Arr::except($data, $this->characteristicKeys);

        return [$commodityData, $this->normalizeCharacteristics($characteristics)];
    }

    private function normalizeCharacteristics(array $characteristics): array
    {
        $normalized = [];
        foreach ($characteristics as $key => $value) {
            if (is_string($value)) {
                $value = trim($value);
                $value = $value === '' ? null : $value;
            }
            $normalized[$key] = $value;
        }

        return $normalized;
    }

    private function saveCharacteristics(Commodity $model, array $characteristics): void
    {
        if (empty($characteristics)) {
            return;
        }

        $model->characteristics()->updateOrCreate(
            ['commodity_id' => $model->id],
            $characteristics
        );
    }
}
