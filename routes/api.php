<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HotelSearchController;

Route::get('/hotels/search', [HotelSearchController::class, 'search']); 