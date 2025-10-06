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
            'filter_by' => 'nullable|string|in:commodity,city,province,region',
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
                'sql' => $result['sql'] ?? null,
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
        $filters['province'] = $validated['province'] ?? $validated['provinces'] ?? null;
        $filters['city'] = $validated['city'] ?? $validated['cities'] ?? null;
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
            'filter_by' => 'nullable|string|in:commodity,city,province,region',
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
            'filter_by' => 'nullable|string|in:commodity,city,province,region',
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
}
