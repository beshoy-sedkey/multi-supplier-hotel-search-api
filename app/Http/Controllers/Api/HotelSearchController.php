<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\DTOs\HotelSearchRequestDTO;
use App\Http\Requests\SearchHotelSuppliersRequest;
use App\Services\HotelSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HotelSearchController extends Controller
{
    public function __construct(
        private HotelSearchService $hotelSearchService
    ) {}

    public function search(SearchHotelSuppliersRequest $request): JsonResponse
    {
        try {

            $searchRequest = HotelSearchRequestDTO::fromRequest($request->validated());
            
            $results = $this->hotelSearchService->search($searchRequest);

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Hotel search error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while searching for hotels',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
} 