<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QueueController;
use App\Http\Controllers\Api\StarWarsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/add-to-queue', [QueueController::class, 'testStore']);

Route::post('/star-wars-fetch', [StarWarsController::class, 'fetch']);
