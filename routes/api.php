<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\WebRTCController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::post('/profile/{profile}/like', [ProfileController::class, 'addLike']);
    Route::post('/profile/experience', [ProfileController::class, 'addExperiencePoint']);

    // Match routes
    Route::get('/categories', [MatchController::class, 'getCategories']);
    Route::post('/categories', [MatchController::class, 'updateUserCategories']);
    Route::post('/match/find', [MatchController::class, 'findMatch']);
    Route::post('/match/cancel', [MatchController::class, 'cancelMatch']);

    // WebRTC routes
    Route::post('/webrtc/signal', [WebRTCController::class, 'signal']);
});
