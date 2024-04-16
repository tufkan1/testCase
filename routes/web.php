<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\SimulatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'getStarting']);
Route::get('/reset-all', [MainController::class, 'resetAll']);
Route::get('/standings', [MainController::class, 'getStandings']);
Route::get('/fixtures', [MainController::class, 'getFixtures']);

//predictionController
Route::get('/prediction', [PredictionController::class, 'getPrediction']);

//simulatorController
Route::get('/play-all-weeks', [SimulatorController::class, 'playAllWeeks']);
Route::get('/play-week/{weekId}', [SimulatorController::class, 'playWeekly']);
