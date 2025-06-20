@extends('layouts.app')
@section('title', 'Inicio - Directorio de Restaurantes')

@section('content')
<!-- Hero Section -->
<div class="hero">
    <div class="hero-content">
        <h1>Descubre los mejores restaurantes</h1>
        <p>Encuentra, reseña y comparte tus experiencias gastronómicas favoritas en un solo lugar.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('restaurants.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-search"></i> Explorar restaurantes
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-person-plus"></i> Registrarse
                </a>
            @else
                <a href="{{ route('restaurants.create') }}" class="btn btn-success btn-lg">
                    <i class="bi bi-plus-circle"></i> Añadir restaurante
                </a>
            @endguest
        </div>
    </div>
</div>

{{-- --- REACTIVANDO LAS SECCIONES --}}

<!-- Featured Restaurants -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Restaurantes destacados</h2>
        <a href="{{ route('restaurants.index') }}" class="btn btn-outline-primary">Ver todos</a>
    </div>
    
    <div class="row">
        @foreach($restaurants->take(3) as $restaurant)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($restaurant->photos->count() > 0)
                        <img src="{{ $restaurant->photos->first()->url }}" class="card-img-top restaurant-image" alt="{{ $restaurant->name }}">
                    @else
                        <div class="card-img-top restaurant-image bg-light d-flex align-items-center justify-content-center">
                            <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $restaurant->name }}</h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-geo-alt"></i> {{ $restaurant->address }}
                        </p>
                        
                        <div class="mb-2">
                            @foreach($restaurant->categories as $category)
                                <span class="badge bg-info text-white me-1">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="rating me-2">
                                @php
                                    $rating = $restaurant->reviews->avg('rating') ?? 0;
                                    $fullStars = floor($rating);
                                    $halfStar = $rating - $fullStars >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp
                                
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="bi bi-star-fill"></i>
                                @endfor
                                
                                @if($halfStar)
                                    <i class="bi bi-star-half"></i>
                                @endif
                                
                                @for($i = 0; $i < $emptyStars; $i++)
                                    <i class="bi bi-star"></i>
                                @endfor
                            </div>
                            <small class="text-muted">
                                ({{ $restaurant->reviews->count() }})
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-eye"></i> Ver detalles
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Categories -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Explora por categorías</h2>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">Ver todos</a>
    </div>
    
    <div class="row">
        @foreach($categories->take(6) as $category)
            <div class="col-md-4 mb-4">
                <a href="{{ route('categories.restaurants', $category) }}" class="text-decoration-none">
                    <div class="card category-card">
                        <img src="https://source.unsplash.com/random/300x150/?{{ urlencode($category->name) }},food" class="category-card-img" alt="{{ $category->name }}">
                        <div class="category-card-overlay">
                            <span>{{ $category->name }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

<!-- Statistics -->
<section class="mb-5">
    <div class="row text-center">
        <div class="col-md-3 mb-4">
            <div class="p-4">
                <i class="bi bi-shop feature-icon"></i>
                <h3>{{ $stats['restaurants'] }}</h3>
                <p>Restaurantes</p>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="p-4">
                <i class="bi bi-tags feature-icon"></i>
                <h3>{{ $stats['categories'] }}</h3>
                <p>Categorías</p>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="p-4">
                <i class="bi bi-star feature-icon"></i>
                <h3>{{ $stats['reviews'] }}</h3>
                <p>Reseñas</p>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="p-4">
                <i class="bi bi-people feature-icon"></i>
                <h3>{{ $stats['users'] }}</h3>
                <p>Usuarios</p>
            </div>
        </div>
    </div>
</section>
@endsection
