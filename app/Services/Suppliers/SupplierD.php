<?php

namespace App\Services\Suppliers;

use App\Contracts\HotelSupplierInterface;
use App\DTOs\HotelDTO;
use App\DTOs\HotelSearchRequestDTO;

class SupplierD implements HotelSupplierInterface
{
    public function search(HotelSearchRequestDTO $request): array
    {
        usleep(rand(100000, 400000));

        $hotels = [
            [
                'name' => 'Grand Hotel & Spa',
                'location' => $request->location . ', Country',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'price' => 275.00,
                'rating' => 4.5,
                'supplier_id' => 'D001',
                'amenities' => ['WiFi', 'Pool', 'Spa', 'Restaurant', 'Gym'],
                'image_url' => 'https://example.com/hotel-d1.jpg',
                'description' => 'Luxury hotel with excellent service'
            ],
            [
                'name' => 'Central Station Hotel',
                'location' => $request->location . ', Country',
                'latitude' => 40.7550,
                'longitude' => -73.9900,
                'price' => 160.00,
                'rating' => 4.0,
                'supplier_id' => 'D002',
                'amenities' => ['WiFi', 'Restaurant', 'Near Station'],
                'image_url' => 'https://example.com/hotel-d2.jpg',
                'description' => 'Convenient hotel near central station'
            ],
            [
                'name' => 'Garden View Hotel',
                'location' => $request->location . ', Country',
                'latitude' => 40.7400,
                'longitude' => -73.9900,
                'price' => 135.00,
                'rating' => 3.9,
                'supplier_id' => 'D003',
                'amenities' => ['WiFi', 'Garden', 'Restaurant'],
                'image_url' => 'https://example.com/hotel-d3.jpg',
                'description' => 'Peaceful hotel with beautiful garden views'
            ]
        ];

        return array_map(function ($hotel) {
            $hotel['supplier'] = $this->getSupplierName();
            return HotelDTO::fromArray($hotel);
        }, $hotels);
    }

    public function getSupplierName(): string
    {
        return 'Supplier D';
    }

    public function isAvailable(): bool
    {
        return rand(1, 100) <= 92;
    }
} 