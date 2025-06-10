<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controladores
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\PhotoController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
| Accesibles sin token de autenticación
*/

// Autenticación básica
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login'])->name('login');

// Rutas públicas de lectura
Route::get('restaurants', [RestaurantController::class, 'index']);
Route::get('restaurants/{id}', [RestaurantController::class, 'show']);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

Route::get('reviews', [ReviewController::class, 'index']);
Route::get('reviews/{id}', [ReviewController::class, 'show']);

Route::get('photos', [PhotoController::class, 'index']);
Route::get('photos/{id}', [PhotoController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Autenticación requerida)
|--------------------------------------------------------------------------
| Requieren token de acceso válido (middleware: auth:api)
*/

Route::middleware('auth:api')->group(function () {
    // Cerrar sesión
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // CRUD completo de Restaurantes
    Route::post('restaurants', [RestaurantController::class, 'store']);
    Route::put('restaurants/{id}', [RestaurantController::class, 'update']);
    Route::delete('restaurants/{id}', [RestaurantController::class, 'destroy']);

    // CRUD completo de Categorías
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    // CRUD completo de Reseñas
    Route::post('reviews', [ReviewController::class, 'store']);
    Route::put('reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);

    // CRUD completo de Fotos
    Route::post('photos', [PhotoController::class, 'store']);
    Route::put('photos/{id}', [PhotoController::class, 'update']);
    Route::delete('photos/{id}', [PhotoController::class, 'destroy']);
});
