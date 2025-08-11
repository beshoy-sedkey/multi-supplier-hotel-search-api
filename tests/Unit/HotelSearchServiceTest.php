<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\HotelSearchService;
use App\DTOs\HotelSearchRequestDTO;
use App\DTOs\HotelDTO;
use Mockery;

class HotelSearchServiceTest extends TestCase
{
    private HotelSearchService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new HotelSearchService();
    }

    public function test_search_returns_structured_response()
    {
        $request = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2025-12-01',
            checkOut: '2025-12-03',
            guests: 2
        );

        $result = $this->service->search($request);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('hotels', $result);
        $this->assertArrayHasKey('meta', $result);
        $this->assertArrayHasKey('total_results', $result['meta']);
        $this->assertArrayHasKey('execution_time_ms', $result['meta']);
        $this->assertArrayHasKey('suppliers_queried', $result['meta']);
    }

    public function test_search_returns_hotels_array()
    {
        $request = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2025-02-01',
            checkOut: '2025-02-03'
        );

        $result = $this->service->search($request);

        $this->assertIsArray($result['hotels']);
        $this->assertGreaterThan(0, count($result['hotels']));
    }

    public function test_search_handles_price_filtering()
    {
        $request = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2025-02-01',
            checkOut: '2025-02-03',
            minPrice: 200.0,
            maxPrice: 300.0
        );

        $result = $this->service->search($request);

        foreach ($result['hotels'] as $hotel) {
            $this->assertGreaterThanOrEqual(200.0, $hotel['price']);
            $this->assertLessThanOrEqual(300.0, $hotel['price']);
        }
    }

    public function test_search_handles_sorting_by_price()
    {
        $request = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2025-12-01',
            checkOut: '2025-12-03',
            sortBy: 'price'
        );

        $result = $this->service->search($request);

        $prices = array_column($result['hotels'], 'price');
        $sortedPrices = $prices;
        sort($sortedPrices);

        $this->assertEquals($sortedPrices, $prices);
    }

    public function test_search_handles_sorting_by_rating()
    {
        $request = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2025-12-01',
            checkOut: '2025-12-03',
            sortBy: 'rating'
        );

        $result = $this->service->search($request);

        $ratings = array_column($result['hotels'], 'rating');
        $sortedRatings = $ratings;
        rsort($sortedRatings);

        $this->assertEquals($sortedRatings, $ratings);
    }

    public function test_search_returns_meta_information()
    {
        $request = new HotelSearchRequestDTO(
            location: 'New York',
            checkIn: '2025-12-01',
            checkOut: '2025-12-03'
        );

        $result = $this->service->search($request);

        $this->assertEquals(4, $result['meta']['suppliers_queried']);
        $this->assertGreaterThan(0, $result['meta']['total_results']);
        $this->assertGreaterThan(0, $result['meta']['execution_time_ms']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 