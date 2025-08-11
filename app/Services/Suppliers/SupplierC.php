<?php

namespace App\Services\Suppliers;

use App\Contracts\HotelSupplierInterface;
use App\DTOs\HotelDTO;
use App\DTOs\HotelSearchRequestDTO;

class SupplierC implements HotelSupplierInterface
{
    public function search(HotelSearchRequestDTO $request): array
    {
        // Simulate API delay
        usleep(rand(200000, 700000)); 

        $hotels = [
            [
                'name' => 'Grand Hotel & Spa',
                'location' => $request->location . ', Country',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'price' => 310.00,
                'rating' => 4.5,
                'supplier_id' => 'C001',
                'amenities' => ['WiFi', 'Pool', 'Spa', 'Restaurant', 'Gym', 'Concierge'],
                'image_url' => 'https://example.com/hotel-c1.jpg',
                'description' => 'Ultra-luxury hotel with full concierge service'
            ],
            [
                'name' => 'Riverside Boutique Hotel',
                'location' => $request->location . ', Country',
                'latitude' => 40.7500,
                'longitude' => -74.0050,
                'price' => 220.00,
                'rating' => 4.3,
                'supplier_id' => 'C002',
                'amenities' => ['WiFi', 'Restaurant', 'Bar', 'River View'],
                'image_url' => 'https://example.com/hotel-c2.jpg',
                'description' => 'Charming boutique hotel with river views'
            ],
            [
                'name' => 'Historic Inn',
                'location' => $request->location . ', Country',
                'latitude' => 40.7450,
                'longitude' => -73.9950,
                'price' => 150.00,
                'rating' => 4.1,
                'supplier_id' => 'C003',
                'amenities' => ['WiFi', 'Historic Building', 'Restaurant'],
                'image_url' => 'https://example.com/hotel-c3.jpg',
                'description' => 'Beautifully restored historic hotel'
            ]
        ];

        return array_map(function ($hotel) {
            $hotel['supplier'] = $this->getSupplierName();
            return HotelDTO::fromArray($hotel);
        }, $hotels);
    }

    public function getSupplierName(): string
    {
        return 'Supplier C';
    }

    public function isAvailable(): bool
    {
        return rand(1, 100) <= 85;
    }
} 