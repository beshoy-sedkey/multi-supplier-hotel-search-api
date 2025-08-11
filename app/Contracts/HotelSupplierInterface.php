<?php

namespace App\Contracts;

use App\DTOs\HotelSearchRequestDTO;
use App\DTOs\HotelDTO;

interface HotelSupplierInterface
{
    /**
     * Search for hotels based on the given criteria
     */
    public function search(HotelSearchRequestDTO $request): array;

    /**
     * Get the supplier name
     */
    public function getSupplierName(): string;

    /**
     * Check if the supplier is available
     */
    public function isAvailable(): bool;
} 