<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminCarController;
use App\Http\Controllers\Api\AdminDocumentController;
use App\Http\Controllers\Api\AdminReportController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;

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

Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{car}', [CarController::class, 'show']);
Route::get('/cars/{car}/reviews', [ReviewController::class, 'index']);

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::post('/cars/{car}/reviews', [ReviewController::class, 'store']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    Route::get('/documents', [DocumentController::class, 'index']);
    Route::post('/documents', [DocumentController::class, 'store']);

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::post('/users', [AdminUserController::class, 'store']);
        Route::get('/users/{user}', [AdminUserController::class, 'show']);
        Route::put('/users/{user}', [AdminUserController::class, 'update']);
        Route::patch('/users/{user}', [AdminUserController::class, 'update']);
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy']);

        Route::get('/cars', [AdminCarController::class, 'index']);
        Route::post('/cars', [AdminCarController::class, 'store']);
        Route::get('/cars/{car}', [AdminCarController::class, 'show']);
        Route::put('/cars/{car}', [AdminCarController::class, 'update']);
        Route::patch('/cars/{car}', [AdminCarController::class, 'update']);
        Route::delete('/cars/{car}', [AdminCarController::class, 'destroy']);

        Route::get('/document-requests', [AdminDocumentController::class, 'requests']);
        Route::get('/document-requests/{userId}', [AdminDocumentController::class, 'userDocuments']);
        Route::patch('/documents/{document}/decision', [AdminDocumentController::class, 'decide']);

        Route::get('/reports/excel', [AdminReportController::class, 'excel']);
    });
});
