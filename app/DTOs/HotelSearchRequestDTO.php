<?php

namespace App\DTOs;

class HotelSearchRequestDTO
{
    public function __construct(
        public string $location,
        public string $checkIn,
        public string $checkOut,
        public int $guests = 1,
        public ?float $minPrice = null,
        public ?float $maxPrice = null,
        public ?string $sortBy = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            location: $data['location'],
            checkIn: $data['check_in'],
            checkOut: $data['check_out'],
            guests: (int) ($data['guests'] ?? 1),
            minPrice: isset($data['min_price']) ? (float) $data['min_price'] : null,
            maxPrice: isset($data['max_price']) ? (float) $data['max_price'] : null,
            sortBy: $data['sort_by'] ?? null
        );
    }

} 