<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\MapDataFilterService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Simplified API controller for map data filtering
 * Uses the centralized MapDataFilterService for cleaner, more maintainable code
 */
class MapDataController extends Controller
{
    public function __construct(
        private MapDataFilterService $mapDataService
    ) {}

    /**
     * Get filtered map data for plotting
     */
    public function getMapData(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data_type' => 'required|string|in:commodities,breeders,institutes',
            'filter_by' => 'nullable|string|in:commodity,city,province,region,institute',
            'commodity' => 'nullable|string',
            'commodities' => 'nullable|string', // Alternative naming
            'institute' => 'nullable|string',
            'breeder_type' => 'nullable|string',
            'institute_type' => 'nullable|string',
            'region' => 'nullable|string',
            'regions' => 'nullable|string', // Alternative naming
            'province' => 'nullable|string',
            'provinces' => 'nullable|string', // Alternative naming
            'city' => 'nullable|string|numeric',
            'cities' => 'nullable|string|numeric', // Alternative naming (ID value)
            'search' => 'nullable|string',
        ]);

        try {
            $dataType = $validated['data_type'];

            // Normalize filter parameters (handle both naming conventions)
            $filters = $this->normalizeFilters($validated);

            // Validate filters
            $validation = $this->mapDataService->validateFilters($dataType, $filters);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid filters provided',
                    'errors' => $validation['errors']
                ], 422);
            }

            $result = $this->mapDataService->getMapData($dataType, $filters);

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'metadata' => $result['metadata'],
                'filter_options' => $result['options'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve map data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Normalize filter parameters to handle different naming conventions
     */
    private function normalizeFilters(array $validated): array
    {
        $filters = [];

        // Remove data_type from filters
        unset($validated['data_type']);

        // Normalize naming conventions
        $filters['filter_by'] = $validated['filter_by'] ?? null;
        $filters['commodity'] = $validated['commodity'] ?? $validated['commodities'] ?? null;
        $filters['region'] = $validated['region'] ?? $validated['regions'] ?? null;
        $filters['province' ]= $validated['province'] ?? $validated['provinces'] ?? null;
        $filters['city' ]= $validated['city'] ?? $validated['cities'] ?? null;
        $filters['institute'] = $validated['institute'] ?? null;
        $filters['breeder_type'] = $validated['breeder_type'] ?? null;
        $filters['institute_type'] = $validated['institute_type'] ?? null;
        $filters['search'] = $validated['search'] ?? null;

        // Remove null values
        return array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });
    }

    /**
     * Get available filter options
     */
    public function getFilterOptions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data_type' => 'required|string|in:commodities,breeders,institutes',
        ]);

        try {
            $options = $this->mapDataService->getFilterOptions($validated['data_type']);

            return response()->json([
                'success' => true,
                'options' => $options
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve filter options',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary statistics
     */
    public function getSummary(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data_type' => 'required|string|in:commodities,breeders,institutes',
            'filter_by' => 'nullable|string|in:commodity,city,province,region,institute',
            'commodity' => 'nullable|string',
            'institute' => 'nullable|string',
            'breeder_type' => 'nullable|string',
            'institute_type' => 'nullable|string',
            'region' => 'nullable|string',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
            'search' => 'nullable|string',
        ]);

        try {
            $dataType = $validated['data_type'];
            unset($validated['data_type']);

            $summary = $this->mapDataService->getSummaryData($dataType, $validated);

            return response()->json([
                'success' => true,
                'summary' => $summary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve summary data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get geographic distribution data
     */
    public function getGeographicDistribution(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data_type' => 'required|string|in:commodities,breeders,institutes',
            'filter_by' => 'nullable|string|in:commodity,city,province,region,institute',
            'commodity' => 'nullable|string',
            'institute' => 'nullable|string',
            'breeder_type' => 'nullable|string',
            'institute_type' => 'nullable|string',
            'region' => 'nullable|string',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
            'search' => 'nullable|string',
            'group_by' => 'nullable|string|in:region,province,city,institute,breeder_type',
        ]);

        try {
            $dataType = $validated['data_type'];
            unset($validated['data_type']);

            $groupBy = $validated['group_by'] ?? ($validated['filter_by'] ?? 'region');
            unset($validated['group_by']);

            $distribution = $this->mapDataService->getGeographicDistribution($dataType, $groupBy, $validated);

            return response()->json([
                'success' => true,
                'distribution' => $distribution,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve geographic distribution',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Return minimal items (id, image, label) for orbit overlay by city id.
     */
    public function getOrbitItems(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data_type' => 'required|string|in:commodities,breeders',
            'city_ids' => 'required|string', // Expect a comma-separated string of IDs
            'limit' => 'nullable|integer|min:1|max:24',
        ]);

        $type = $validated['data_type'];
        $cityIds = array_filter(array_map('intval', explode(',', $validated['city_ids'])));
        $limit = (int) ($validated['limit'] ?? 12);

        if (empty($cityIds)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        try {
            $query = null;
            if ($type === 'commodities') {
                $query = \DB::table('commodities')
                    ->join('breeders', 'commodities.breeder_id', '=', 'breeders.id')
                    ->join('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
                    ->whereIn('loc_cities.id', $cityIds)
                    ->when(!$request->user()->isAdmin(), function ($q) {
                        $q->whereNotNull('commodities.approved_at');
                    })
                    ->select([
                        'loc_cities.id as city_id',
                        'commodities.id',
                        'commodities.name as label',
                        'commodities.photo as photo',
                        \DB::raw('ROW_NUMBER() OVER (PARTITION BY loc_cities.id ORDER BY commodities.updated_at DESC) as rn')
                    ]);

            } else { // breeders
                $query = \DB::table('breeders')
                    ->join('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
                    ->whereIn('loc_cities.id', $cityIds)
                    ->select([
                        'loc_cities.id as city_id',
                        'breeders.id',
                        \DB::raw("TRIM(CONCAT(breeders.fname,' ',IFNULL(breeders.mname,''),' ',breeders.lname,' ',IFNULL(breeders.suffix,''))) as label"),
                        'breeders.photo as photo',
                        \DB::raw('ROW_NUMBER() OVER (PARTITION BY loc_cities.id ORDER BY breeders.updated_at DESC) as rn')
                    ]);
            }

            // Wrap the query to apply the limit per city
            $rankedRows = \DB::table(\DB::raw("({$query->toSql()}) as sub"))
                ->mergeBindings($query)
                ->where('rn', '<=', $limit)
                ->get();

            $groupedData = $rankedRows->groupBy('city_id')->map(function ($rows) {
                return $rows->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'label' => $r->label,
                        'image' => $r->photo ? asset($r->photo) : asset('img/logos/pin.webp'),
                    ];
                });
            });

            return response()->json([
                'success' => true,
                'data' => $groupedData,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load orbit items',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
