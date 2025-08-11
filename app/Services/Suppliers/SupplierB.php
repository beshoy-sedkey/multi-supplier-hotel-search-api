<?php

namespace App\Services\Suppliers;

use App\Contracts\HotelSupplierInterface;
use App\DTOs\HotelDTO;
use App\DTOs\HotelSearchRequestDTO;

class SupplierB implements HotelSupplierInterface
{
    public function search(HotelSearchRequestDTO $request): array
    {
        // Simulate API delay
        usleep(rand(150000, 600000)); // 0.15 to 0.6 seconds

        $hotels = [
            [
                'name' => 'Grand Hotel & Spa',
                'location' => $request->location . ', Country',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'price' => 285.00,
                'rating' => 4.5,
                'supplier_id' => 'B001',
                'amenities' => ['WiFi', 'Pool', 'Spa', 'Restaurant', 'Gym'],
                'image_url' => 'https://example.com/hotel-b1.jpg',
                'description' => 'Premium luxury hotel with comprehensive amenities'
            ],
            [
                'name' => 'Downtown Comfort Inn',
                'location' => $request->location . ', Country',
                'latitude' => 40.7600,
                'longitude' => -73.9900,
                'price' => 175.50,
                'rating' => 4.0,
                'supplier_id' => 'B002',
                'amenities' => ['WiFi', 'Restaurant', 'Bar'],
                'image_url' => 'https://example.com/hotel-b2.jpg',
                'description' => 'Comfortable hotel in the heart of downtown'
            ],
            [
                'name' => 'Airport Plaza Hotel',
                'location' => $request->location . ', Country',
                'latitude' => 40.6400,
                'longitude' => -73.7800,
                'price' => 120.00,
                'rating' => 3.9,
                'supplier_id' => 'B003',
                'amenities' => ['WiFi', 'Shuttle Service', 'Free Parking'],
                'image_url' => 'https://example.com/hotel-b3.jpg',
                'description' => 'Convenient airport hotel with shuttle service'
            ]
        ];

        return array_map(function ($hotel) {
            $hotel['supplier'] = $this->getSupplierName();
            return HotelDTO::fromArray($hotel);
        }, $hotels);
    }

    public function getSupplierName(): string
    {
        return 'Supplier B';
    }

    public function isAvailable(): bool
    {
        return rand(1, 100) <= 90;
    }
} 