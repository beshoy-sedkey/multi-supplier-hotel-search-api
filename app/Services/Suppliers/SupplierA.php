<?php

namespace App\Services\Suppliers;

use App\Contracts\HotelSupplierInterface;
use App\DTOs\HotelDTO;
use App\DTOs\HotelSearchRequestDTO;

class SupplierA implements HotelSupplierInterface
{
    public function search(HotelSearchRequestDTO $request): array
    {
        usleep(rand(100000, 500000)); 

        $hotels = [
            [
                'name' => 'Grand Hotel & Spa',
                'location' => $request->location . ', Country',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'price' => 299.99,
                'rating' => 4.5,
                'supplier_id' => 'A001',
                'amenities' => ['WiFi', 'Pool', 'Spa', 'Restaurant'],
                'image_url' => 'https://example.com/hotel-a1.jpg',
                'description' => 'Luxury hotel with spa and pool facilities'
            ],
            [
                'name' => 'Business Center Hotel',
                'location' => $request->location . ', Country',
                'latitude' => 40.7589,
                'longitude' => -73.9851,
                'price' => 199.99,
                'rating' => 4.2,
                'supplier_id' => 'A002',
                'amenities' => ['WiFi', 'Business Center', 'Restaurant'],
                'image_url' => 'https://example.com/hotel-a2.jpg',
                'description' => 'Perfect for business travelers'
            ],
            [
                'name' => 'Budget Inn Express',
                'location' => $request->location . ', Country',
                'latitude' => 40.7505,
                'longitude' => -73.9934,
                'price' => 89.99,
                'rating' => 3.8,
                'supplier_id' => 'A003',
                'amenities' => ['WiFi', 'Free Breakfast'],
                'image_url' => 'https://example.com/hotel-a3.jpg',
                'description' => 'Affordable accommodation with basic amenities'
            ]
        ];

        return array_map(function ($hotel) {
            $hotel['supplier'] = $this->getSupplierName();
            return HotelDTO::fromArray($hotel);
        }, $hotels);
    }

    public function getSupplierName(): string
    {
        return 'Supplier A';
    }

    public function isAvailable(): bool
    {
        return rand(1, 100) <= 95;
    }
} 