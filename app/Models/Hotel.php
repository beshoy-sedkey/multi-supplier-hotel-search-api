<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'location',
        'latitude',
        'longitude',
        'price',
        'rating',
        'supplier',
        'supplier_id',
        'amenities',
        'image_url',
        'description'
    ];

    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'amenities' => 'array'
    ];

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeByRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }
} 