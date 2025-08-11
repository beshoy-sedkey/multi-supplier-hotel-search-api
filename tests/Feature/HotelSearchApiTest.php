<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HotelSearchApiTest extends TestCase
{
    public function test_hotel_search_endpoint_exists()
    {
        $response = $this->get('/api/hotels/search');
        
        $this->assertNotEquals(404, $response->status());
    }

    public function test_hotel_search_requires_location()
    {
        $response = $this->get('/api/hotels/search?check_in=2025-12-01&check_out=2025-12-03');

        $this->assertEquals(422, $response->status());
        $response->assertJsonPath('details.location', ['The location field is required.']);
    }

    public function test_hotel_search_requires_check_in()
    {
        $response = $this->get('/api/hotels/search?location=New%20York&check_out=2025-12-03');

        $this->assertEquals(422, $response->status());
        $response->assertJsonPath('details.check_in', ['The check in field is required.']);
    }

    public function test_hotel_search_requires_check_out()
    {
        $response = $this->get('/api/hotels/search', [
            'location' => 'New York',
            'check_in' => '2025-12-01'
        ]);

        $this->assertEquals(422, $response->status());
        $response->assertJsonPath('details.check_out', ['The check out field is required.']);
    }

    public function test_hotel_search_with_valid_parameters()
    {
        $response = $this->get('/api/hotels/search?location=New%20York&check_in=2025-12-01&check_out=2025-12-03');

        if ($response->status() !== 200) {
            dump($response->content());
        }

        $this->assertEquals(200, $response->status());
        
        $response->assertJsonStructure([
            'success',
            'data' => [
                'hotels',
                'meta' => [
                    'total_results',
                    'execution_time_ms',
                    'suppliers_queried'
                ]
            ]
        ]);
    }

    public function test_hotel_search_with_price_filtering()
    {
        $response = $this->get('/api/hotels/search', [
            'location' => 'New York',
            'check_in' => '2025-12-01',
            'check_out' => '2025-12-03',
            'min_price' => 200,
            'max_price' => 300
        ]);

        $this->assertEquals(200, $response->status());
        
        $data = $response->json('data');
        $this->assertGreaterThan(0, $data['meta']['total_results']);
        
        // Check that all returned hotels are within price range
        foreach ($data['hotels'] as $hotel) {
            $this->assertGreaterThanOrEqual(200, $hotel['price']);
            $this->assertLessThanOrEqual(300, $hotel['price']);
        }
    }

    public function test_hotel_search_with_sorting_by_price()
    {
        $response = $this->get('/api/hotels/search', [
            'location' => 'New York',
            'check_in' => '2025-12-01',
            'check_out' => '2025-12-03',
            'sort_by' => 'price'
        ]);

        $this->assertEquals(200, $response->status());
        
        $data = $response->json('data');
        $this->assertGreaterThan(0, $data['meta']['total_results']);
        
        // Check that hotels are sorted by price (ascending)
        $prices = array_column($data['hotels'], 'price');
        $sortedPrices = $prices;
        sort($sortedPrices);
        
        $this->assertEquals($sortedPrices, $prices);
    }

    public function test_hotel_search_with_sorting_by_rating()
    {
        $response = $this->get('/api/hotels/search', [
            'location' => 'New York',
            'check_in' => '2025-12-01',
            'check_out' => '2025-12-03',
            'sort_by' => 'rating'
        ]);

        $this->assertEquals(200, $response->status());
        
        $data = $response->json('data');
        $this->assertGreaterThan(0, $data['meta']['total_results']);
        
        // Check that hotels are sorted by rating (descending)
        $ratings = array_column($data['hotels'], 'rating');
        $sortedRatings = $ratings;
        rsort($sortedRatings);
        
        $this->assertEquals($sortedRatings, $ratings);
    }

    public function test_hotel_search_with_guests_parameter()
    {
        $response = $this->get('/api/hotels/search', [
            'location' => 'New York',
            'check_in' => '2025-12-01',
            'check_out' => '2025-12-03',
            'guests' => 4
        ]);

        $this->assertEquals(200, $response->status());
        
        $data = $response->json('data');
        $this->assertGreaterThan(0, $data['meta']['total_results']);
    }

    public function test_hotel_search_returns_meta_information()
    {
        $response = $this->get('/api/hotels/search', [
            'location' => 'New York',
            'check_in' => '2025-12-01',
            'check_out' => '2025-12-03'
        ]);

        $this->assertEquals(200, $response->status());
        
        $data = $response->json('data');
        
        $this->assertArrayHasKey('meta', $data);
        $this->assertArrayHasKey('total_results', $data['meta']);
        $this->assertArrayHasKey('execution_time_ms', $data['meta']);
        $this->assertArrayHasKey('suppliers_queried', $data['meta']);
        
        $this->assertEquals(4, $data['meta']['suppliers_queried']);
        $this->assertGreaterThan(0, $data['meta']['total_results']);
        $this->assertGreaterThan(0, $data['meta']['execution_time_ms']);
    }

    public function test_hotel_search_handles_invalid_date_format()
    {
        $response = $this->get('/api/hotels/search', [
            'location' => 'New York',
            'check_in' => 'invalid-date',
            'check_out' => '2025-12-03'
        ]);

        $this->assertEquals(422, $response->status());
        $response->assertJsonPath('details.check_in', ['The check in field must be a date after today.']);
    }

    public function test_hotel_search_handles_past_dates()
    {
        $response = $this->get('/api/hotels/search', [
            'location' => 'New York',
            'check_in' => '2020-01-01',
            'check_out' => '2020-01-03'
        ]);

        $this->assertEquals(422, $response->status());
        $response->assertJsonPath('details.check_in', ['The check in field must be a date after today.']);
    }

    public function test_hotel_search_handles_invalid_check_out_date()
    {
        $response = $this->get('/api/hotels/search', [
            'location' => 'New York',
            'check_in' => '2025-12-01',
            'check_out' => '2025-11-30'
        ]);

        $this->assertEquals(422, $response->status());
        $response->assertJsonPath('details.check_out', ['The check out field must be a date after check in.']);
    }
} 