<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;

// Página principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Rutas públicas
Route::get('restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('categories/{id}/restaurants', [CategoryController::class, 'restaurants'])->name('categories.restaurants');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Restaurantes
    Route::get('restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::post('restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
    Route::get('restaurants/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('restaurants.edit');
    Route::put('restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
    Route::delete('restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');
    
    // Favoritos
    Route::get('favorites', [RestaurantController::class, 'favorites'])->name('restaurants.favorites');
    Route::post('restaurants/{restaurant}/favorite', [RestaurantController::class, 'addToFavorites'])->name('restaurants.favorite');
    Route::delete('restaurants/{restaurant}/favorite', [RestaurantController::class, 'removeFromFavorites'])->name('restaurants.unfavorite');
    
    // Categorías (solo para administradores)
    Route::middleware('admin')->group(function () {
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
    
    // Reseñas
    // Crear una reseña para un restaurante específico
    Route::get('restaurants/{restaurant}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('restaurants/{restaurant}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Editar, actualizar y eliminar reseñas (se usará Route Model Binding con {review})
    Route::get('reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Ruta para que los usuarios vean sus propias reseñas
    Route::get('my-reviews', [ReviewController::class, 'myReviews'])->name('reviews.my');
    
    // Rutas de administración
    // Rutas de administración
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('users', [UserController::class, 'index'])
            ->name('users.index')
            ->middleware('can:viewAny,App\Models\User');

        Route::patch('users/{user}', [UserController::class, 'update'])
            ->name('users.update');
    });
});