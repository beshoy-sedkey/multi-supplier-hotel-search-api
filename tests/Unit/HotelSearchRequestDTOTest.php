<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\DTOs\HotelSearchRequestDTO;

class HotelSearchRequestDTOTest extends TestCase
{
    public function test_dto_creation()
    {
        $dto = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2025-02-01',
            checkOut: '2025-02-03',
            guests: 2,
            minPrice: 100.0,
            maxPrice: 500.0,
            sortBy: 'price'
        );

        $this->assertEquals('New York', $dto->location);
        $this->assertEquals('2025-02-01', $dto->checkIn);
        $this->assertEquals('2025-02-03', $dto->checkOut);
        $this->assertEquals(2, $dto->guests);
        $this->assertEquals(100.0, $dto->minPrice);
        $this->assertEquals(500.0, $dto->maxPrice);
        $this->assertEquals('price', $dto->sortBy);
    }

    public function test_dto_from_request()
    {
        $requestData = [
            'location' => 'New York',
            'check_in' => '2025-12-01',
            'check_out' => '2025-12-03',
            'guests' => '2',
            'min_price' => '100.0',
            'max_price' => '500.0',
            'sort_by' => 'price'
        ];

        $dto = HotelSearchRequestDTO::fromRequest($requestData);

        $this->assertEquals('New York', $dto->location);
        $this->assertEquals('2025-12-01', $dto->checkIn);
        $this->assertEquals('2025-12-03', $dto->checkOut);
        $this->assertEquals(2, $dto->guests);
        $this->assertEquals(100.0, $dto->minPrice);
        $this->assertEquals(500.0, $dto->maxPrice);
        $this->assertEquals('price', $dto->sortBy);
    }

    public function test_dto_from_request_with_defaults()
    {
        $requestData = [
            'location' => 'New York',
            'check_in' => '2025-12-01',
            'check_out' => '2025-12-03'
        ];

        $dto = HotelSearchRequestDTO::fromRequest($requestData);

        $this->assertEquals('New York', $dto->location);
        $this->assertEquals('2025-12-01', $dto->checkIn);
        $this->assertEquals('2025-12-03', $dto->checkOut);
        $this->assertEquals(1, $dto->guests);
        $this->assertNull($dto->minPrice);
        $this->assertNull($dto->maxPrice);
        $this->assertNull($dto->sortBy);
    }

    public function test_validation_success()
    {
        $dto = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2025-12-01',
            checkOut: '2025-12-03',
            guests: 2,
            minPrice: 100.0,
            maxPrice: 500.0,
            sortBy: 'price'
        );

        $errors = $dto->validate();
        $this->assertEmpty($errors);
    }

    public function test_validation_missing_location()
    {
        $dto = new HotelSearchRequestDTO(
            location: '',
            checkIn: '2025-12-01',
            checkOut: '2025-12-03'
        );

        $errors = $dto->validate();
        $this->assertContains('Location is required', $errors);
    }

    public function test_validation_missing_check_in()
    {
        $dto = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '',
            checkOut: '2025-12-03'
        );

        $errors = $dto->validate();
        $this->assertContains('Check-in date is required', $errors);
    }

    public function test_validation_missing_check_out()
    {
        $dto = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2025-12-01',
            checkOut: ''
        );

        $errors = $dto->validate();
        $this->assertContains('Check-out date is required', $errors);
    }

    public function test_validation_invalid_guests()
    {
        $dto = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2024-02-01',
            checkOut: '2024-02-03',
            guests: 0
        );

        $errors = $dto->validate();
        $this->assertContains('Guests must be at least 1', $errors);
    }

    public function test_validation_invalid_price_range()
    {
        $dto = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2024-02-01',
            checkOut: '2024-02-03',
            minPrice: 500.0,
            maxPrice: 100.0
        );

        $errors = $dto->validate();
        $this->assertContains('Minimum price cannot be greater than maximum price', $errors);
    }

    public function test_validation_invalid_sort_by()
    {
        $dto = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2024-02-01',
            checkOut: '2024-02-03',
            sortBy: 'invalid'
        );

        $errors = $dto->validate();
        $this->assertContains('Sort by must be either "price" or "rating"', $errors);
    }
} 