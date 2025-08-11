<?php

namespace App\DTOs;

class HotelDTO
{
    public function __construct(
        public string $name,
        public string $location,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public float $price = 0.0,
        public ?float $rating = null,
        public string $supplier = '',
        public string $supplierId = '',
        public array $amenities = [],
        public ?string $imageUrl = null,
        public ?string $description = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            location: $data['location'] ?? '',
            latitude: $data['latitude'] ?? null,
            longitude: $data['longitude'] ?? null,
            price: $data['price'] ?? 0.0,
            rating: $data['rating'] ?? null,
            supplier: $data['supplier'] ?? '',
            supplierId: $data['supplier_id'] ?? '',
            amenities: $data['amenities'] ?? [],
            imageUrl: $data['image_url'] ?? null,
            description: $data['description'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'price' => $this->price,
            'rating' => $this->rating,
            'supplier' => $this->supplier,
            'supplier_id' => $this->supplierId,
            'amenities' => $this->amenities,
            'image_url' => $this->imageUrl,
            'description' => $this->description
        ];
    }

    public function isSimilarTo(self $other): bool
    {
        // Check if hotels are similar based on name and location
        $nameSimilarity = similar_text(
            strtolower($this->name), 
            strtolower($other->name), 
            $namePercent
        );
        
        $locationSimilarity = similar_text(
            strtolower($this->location), 
            strtolower($other->location), 
            $locationPercent
        );

        return $namePercent > 70 && $locationPercent > 70;
    }
} 