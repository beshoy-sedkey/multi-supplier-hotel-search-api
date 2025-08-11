<?php

namespace App\Services;

use App\DTOs\HotelDTO;
use App\DTOs\HotelSearchRequestDTO;
use App\Services\Suppliers\SupplierA;
use App\Services\Suppliers\SupplierB;
use App\Services\Suppliers\SupplierC;
use App\Services\Suppliers\SupplierD;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class HotelSearchService
{
    private array $suppliers;

    public function __construct()
    {
        $this->suppliers = [
            new SupplierA(),
            new SupplierB(),
            new SupplierC(),
            new SupplierD(),
        ];
    }

    public function search(HotelSearchRequestDTO $request): array
    {
        $startTime = microtime(true);
        
        $supplierResults = $this->fetchFromSuppliers($request);
        $mergedHotels = $this->mergeAndDeduplicate($supplierResults);
        $filteredHotels = $this->applyFilters($mergedHotels, $request);
        $sortedHotels = $this->sortResults($filteredHotels, $request->sortBy);
        
        $endTime = microtime(true);
        $executionTime = round(($endTime - $startTime) * 1000, 2);
        
        Log::info('Hotel search completed', [
            'location' => $request->location,
            'execution_time_ms' => $executionTime,
            'total_results' => count($sortedHotels),
            'suppliers_queried' => count($this->suppliers)
        ]);

        return [
            'hotels' => array_map(fn(HotelDTO $hotel) => $hotel->toArray(), $sortedHotels),
            'meta' => [
                'total_results' => count($sortedHotels),
                'execution_time_ms' => $executionTime,
                'suppliers_queried' => count($this->suppliers)
            ]
        ];
    }

    private function fetchFromSuppliers(HotelSearchRequestDTO $request): array
    {
        $results = [];
        foreach ($this->suppliers as $supplier) {
            try {
                if ($supplier->isAvailable()) {
                    $hotels = $supplier->search($request);
                    $results[] = $hotels;
                } else {
                    Log::warning("Supplier {$supplier->getSupplierName()} is not available");
                }
                dD($hotels);
            } catch (\Exception $e) {
                Log::error("Error fetching from supplier {$supplier->getSupplierName()}: " . $e->getMessage());
            }
        }
        
        return $results;
    }

    private function mergeAndDeduplicate(array $supplierResults): array
    {
        $allHotels = [];
        $hotelGroups = [];
        
        foreach ($supplierResults as $supplierHotels) {
            foreach ($supplierHotels as $hotel) {
                $allHotels[] = $hotel;
            }
        }
        
        foreach ($allHotels as $hotel) {
            $grouped = false;
            
            foreach ($hotelGroups as &$group) {
                if ($hotel->isSimilarTo($group[0])) {
                    $group[] = $hotel;
                    $grouped = true;
                    break;
                }
            }
            
            if (!$grouped) {
                $hotelGroups[] = [$hotel];
            }
        }
        
        $mergedHotels = [];
        foreach ($hotelGroups as $group) {
            if (count($group) === 1) {
                $mergedHotels[] = $group[0];
            } else {
                $bestDeal = $group[0];
                foreach ($group as $hotel) {
                    if ($hotel->price < $bestDeal->price) {
                        $bestDeal = $hotel;
                    }
                }
                $mergedHotels[] = $bestDeal;
            }
        }
        
        return $mergedHotels;
    }

    private function applyFilters(array $hotels, HotelSearchRequestDTO $request): array
    {
        return array_filter($hotels, function (HotelDTO $hotel) use ($request) {
            if ($request->minPrice !== null && $hotel->price < $request->minPrice) {
                return false;
            }
            if ($request->maxPrice !== null && $hotel->price > $request->maxPrice) {
                return false;
            }
            
            return true;
        });
    }

    private function sortResults(array $hotels, ?string $sortBy): array
    {
        if (!$sortBy) {
            return $hotels;
        }
        
        usort($hotels, function (HotelDTO $a, HotelDTO $b) use ($sortBy) {
            return match ($sortBy) {
                'price' => $a->price <=> $b->price,
                'rating' => $b->rating <=> $a->rating, // Higher rating first
                default => 0
            };
        });
        
        return $hotels;
    }
} 