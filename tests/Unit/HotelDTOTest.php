<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\DTOs\HotelDTO;

class HotelDTOTest extends TestCase
{
    public function test_hotel_dto_creation()
    {
        $hotel = new HotelDTO(
            name: 'Test Hotel',
            location: 'Test City',
            latitude: 40.7128,
            longitude: -74.0060,
            price: 299.99,
            rating: 4.5,
            supplier: 'Test Supplier',
            supplierId: 'TEST001',
            amenities: ['WiFi', 'Pool'],
            imageUrl: 'https://example.com/image.jpg',
            description: 'Test description'
        );

        $this->assertEquals('Test Hotel', $hotel->name);
        $this->assertEquals('Test City', $hotel->location);
        $this->assertEquals(40.7128, $hotel->latitude);
        $this->assertEquals(-74.0060, $hotel->longitude);
        $this->assertEquals(299.99, $hotel->price);
        $this->assertEquals(4.5, $hotel->rating);
        $this->assertEquals('Test Supplier', $hotel->supplier);
        $this->assertEquals('TEST001', $hotel->supplierId);
        $this->assertEquals(['WiFi', 'Pool'], $hotel->amenities);
        $this->assertEquals('https://example.com/image.jpg', $hotel->imageUrl);
        $this->assertEquals('Test description', $hotel->description);
    }

    public function test_hotel_dto_from_array()
    {
        $data = [
            'name' => 'Test Hotel',
            'location' => 'Test City',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'price' => 299.99,
            'rating' => 4.5,
            'supplier' => 'Test Supplier',
            'supplier_id' => 'TEST001',
            'amenities' => ['WiFi', 'Pool'],
            'image_url' => 'https://example.com/image.jpg',
            'description' => 'Test description'
        ];

        $hotel = HotelDTO::fromArray($data);

        $this->assertEquals('Test Hotel', $hotel->name);
        $this->assertEquals('Test City', $hotel->location);
        $this->assertEquals(40.7128, $hotel->latitude);
        $this->assertEquals(-74.0060, $hotel->longitude);
        $this->assertEquals(299.99, $hotel->price);
        $this->assertEquals(4.5, $hotel->rating);
        $this->assertEquals('Test Supplier', $hotel->supplier);
        $this->assertEquals('TEST001', $hotel->supplierId);
        $this->assertEquals(['WiFi', 'Pool'], $hotel->amenities);
        $this->assertEquals('https://example.com/image.jpg', $hotel->imageUrl);
        $this->assertEquals('Test description', $hotel->description);
    }

    public function test_hotel_dto_to_array()
    {
        $hotel = new HotelDTO(
            name: 'Test Hotel',
            location: 'Test City',
            latitude: 40.7128,
            longitude: -74.0060,
            price: 299.99,
            rating: 4.5,
            supplier: 'Test Supplier',
            supplierId: 'TEST001',
            amenities: ['WiFi', 'Pool'],
            imageUrl: 'https://example.com/image.jpg',
            description: 'Test description'
        );

        $array = $hotel->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Test Hotel', $array['name']);
        $this->assertEquals('Test City', $array['location']);
        $this->assertEquals(40.7128, $array['latitude']);
        $this->assertEquals(-74.0060, $array['longitude']);
        $this->assertEquals(299.99, $array['price']);
        $this->assertEquals(4.5, $array['rating']);
        $this->assertEquals('Test Supplier', $array['supplier']);
        $this->assertEquals('TEST001', $array['supplier_id']);
        $this->assertEquals(['WiFi', 'Pool'], $array['amenities']);
        $this->assertEquals('https://example.com/image.jpg', $array['image_url']);
        $this->assertEquals('Test description', $array['description']);
    }

    public function test_hotel_similarity_detection()
    {
        $hotel1 = new HotelDTO(
            name: 'Grand Hotel & Spa',
            location: 'New York, USA',
            price: 299.99
        );

        $hotel2 = new HotelDTO(
            name: 'Grand Hotel & Spa',
            location: 'New York, USA',
            price: 285.00
        );

        $hotel3 = new HotelDTO(
            name: 'Different Hotel',
            location: 'Los Angeles, USA',
            price: 199.99
        );

        $this->assertTrue($hotel1->isSimilarTo($hotel2));
        $this->assertFalse($hotel1->isSimilarTo($hotel3));
        $this->assertFalse($hotel2->isSimilarTo($hotel3));
    }
} 