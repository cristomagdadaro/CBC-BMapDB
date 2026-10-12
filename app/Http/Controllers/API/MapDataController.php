<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repository\API\MapDataRepo;
use App\Services\MapDataFilterService;
use App\Http\Requests\Map\GetMapDataRequest;
use App\Http\Requests\Map\GetMapFilterOptionsRequest;
use App\Http\Requests\Map\GetMapSummaryRequest;
use App\Http\Requests\Map\GetMapDistributionRequest;
use App\Http\Requests\Map\GetOrbitItemsRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Simplified API controller for map data filtering
 * Uses the centralized MapDataFilterService
 */
class MapDataController extends Controller
{
    public function __construct(
        private MapDataFilterService $mapDataService,
        private MapDataRepo $mapDataRepo
    ) {}

    /**
     * Get filtered map data for plotting
     */
    public function getMapData(GetMapDataRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $dataType = $validated['data_type'];

            // Normalize filter parameters
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
    public function getFilterOptions(GetMapFilterOptionsRequest $request): JsonResponse
    {
        $validated = $request->validated();

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
    public function getSummary(GetMapSummaryRequest $request): JsonResponse
    {
        $validated = $request->validated();

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
    public function getGeographicDistribution(GetMapDistributionRequest $request): JsonResponse
    {
        $validated = $request->validated();

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
    public function getOrbitItems(GetOrbitItemsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $type = $validated['data_type'];
        $cityIds = array_filter(array_map('intval', explode(',', $validated['city_ids'])));
        $limit = (int) ($validated['limit'] ?? 12);

        if (empty($cityIds)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        try {
            $groupedData = $this->mapDataRepo->getOrbitItems(
                $type,
                $cityIds,
                $limit,
                \Illuminate\Support\Facades\Auth::check() && $request->user()?->isAdmin()
            );

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
