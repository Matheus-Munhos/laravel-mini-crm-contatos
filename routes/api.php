<?php

use App\Http\Controllers\Api\ProcessContactScoreController;
use App\Http\Controllers\Api\ContactController;
use Illuminate\Support\Facades\Route;

Route::apiResource('contacts', ContactController::class);
Route::post('/contacts/{id}/process-score', ProcessContactScoreController::class);