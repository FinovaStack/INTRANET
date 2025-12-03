<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication routes
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,1');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');

// Email verification
Route::post('/email/verify', [AuthController::class, 'verifyEmail'])->middleware('throttle:5,1');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    // Email verification
    Route::post('/email/resend-verification', [AuthController::class, 'resendVerification'])->middleware('throttle:3,1');

    // Password management
    Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('throttle:5,1');
    Route::post('/set-initial-password', [AuthController::class, 'setInitialPassword'])->middleware('throttle:5,1');

    // Security questions
    Route::get('/security-questions', [AuthController::class, 'getSecurityQuestions']);
    Route::put('/security-questions', [AuthController::class, 'updateSecurityQuestions']);

    // Admin user management (TODO: Add admin middleware)
    Route::post('/users', [AuthController::class, 'createUser'])->middleware('throttle:10,1');
});

// Security questions removed for corporate maturity
