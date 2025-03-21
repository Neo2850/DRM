<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;

Route::post('/ai/process', [AIController::class, 'processPrompt']);
// Route::post('/ai/getProjectData', [AIController::class, 'getProjectData']);

