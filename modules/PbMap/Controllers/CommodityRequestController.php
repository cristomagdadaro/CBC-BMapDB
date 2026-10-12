<?php

namespace Modules\PbMap\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\PbMap\Models\CommodityRequest;
use Illuminate\Http\JsonResponse;

class CommodityRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Handle pagination parameters from CRCMDatatable
        $perPage = $request->input('per_page', 10);
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        
        $requests = CommodityRequest::orderBy($sort, $order)->paginate($perPage);
        
        return response()->json([
            'data' => $requests->items(),
            'meta' => [
                'current_page' => $requests->currentPage(),
                'last_page' => $requests->lastPage(),
                'total' => $requests->total(),
                'from' => $requests->firstItem(),
                'to' => $requests->lastItem(),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scientific_name' => 'nullable|string|max:255',
        ]);

        $commodityRequest = CommodityRequest::create([
            'name' => $validated['name'],
            'scientific_name' => $validated['scientific_name'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Commodity request submitted successfully.',
            'data' => $commodityRequest
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $commodityRequest = CommodityRequest::findOrFail($id);
        $commodityRequest->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Commodity request status updated successfully.',
            'data' => $commodityRequest
        ]);
    }
}
