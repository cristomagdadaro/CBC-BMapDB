<?php

namespace App\Http\Controllers\Api;

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
            'commodity' => 'nullable|string',
            'institute' => 'nullable|string',
            'breeder_type' => 'nullable|string',
            'institute_type' => 'nullable|string',
            'region' => 'nullable|string',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
            'group_by' => 'nullable|string',
            'search' => 'nullable|string',
        ]);

        try {
            $dataType = $validated['data_type'];
            unset($validated['data_type']);

            // Validate filters
            $validation = $this->mapDataService->validateFilters($dataType, $validated);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid filters provided',
                    'errors' => $validation['errors']
                ], 422);
            }

            $result = $this->mapDataService->getMapData($dataType, $validated);

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
            'group_by' => 'required|string|in:region,province,city,institute,commodity,breeder_type,institute_type',
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
            $groupBy = $validated['group_by'];
            unset($validated['data_type'], $validated['group_by']);

            $distribution = $this->mapDataService->getGeographicDistribution($dataType, $groupBy, $validated);

            return response()->json([
                'success' => true,
                'distribution' => $distribution,
                'group_by' => $groupBy
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve geographic distribution',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
