@extends('layouts.app')

@section('title', 'Mis Restaurantes Favoritos')

@push('styles')
    <link href="{{ asset('css/restaurants.css') }}" rel="stylesheet">
    <link href="{{ asset('css/favorites.css') }}" rel="stylesheet"> {{-- Consider creating this if specific styles are needed --}}
@endpush

@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Mis Restaurantes Favoritos</h1>
        <a href="{{ route('restaurants.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-search me-1"></i> Explorar Restaurantes
        </a>
    </div>

    @if($favorites->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($favorites as $restaurant)
                <div class="col">
                    <div class="card restaurant-card h-100 shadow-sm">
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="restaurant-card-link">
                            @if($restaurant->photos->count() > 0 && Storage::exists($restaurant->photos->first()->url))
                                <img src="{{ Storage::url($restaurant->photos->first()->url) }}" class="restaurant-card-img-top" alt="{{ $restaurant->name }}">
                            @else
                                <img src="{{ asset('images/placeholder-restaurant.jpg') }}" class="restaurant-card-img-top" alt="Imagen no disponible">
                            @endif
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title restaurant-card-title">
                                <a href="{{ route('restaurants.show', $restaurant) }}">{{ $restaurant->name }}</a>
                            </h5>
                            <p class="card-text restaurant-card-address"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ Str::limit($restaurant->address, 60) }}</p>
                            
                            <div class="d-flex align-items-center mb-2 restaurant-card-categories flex-wrap">
                                @forelse ($restaurant->categories->take(2) as $category)
                                    <a href="{{ route('categories.show', $category) }}" class="badge bg-light text-dark me-1 mb-1 text-decoration-none rounded-pill">{{ $category->name }}</a>
                                @empty
                                    <span class="badge bg-secondary me-1 mb-1 rounded-pill">Sin categoría</span>
                                @endforelse
                                @if($restaurant->categories->count() > 2)
                                    <span class="badge bg-light text-dark mb-1 rounded-pill">+{{ $restaurant->categories->count() - 2 }}</span>
                                @endif
                            </div>

                            <div class="mt-auto d-flex justify-content-between align-items-center pt-2">
                                <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye-fill"></i> Ver Detalles
                                </a>
                                <button class="btn btn-sm btn-danger favorite-button" 
                                        data-restaurant-id="{{ $restaurant->id }}" 
                                        data-url="{{ route('restaurants.toggleFavorite', $restaurant) }}" 
                                        data-favorited="true">
                                    <i class="bi bi-heart-fill"></i> Quitar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if ($favorites->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $favorites->links() }}
            </div>
        @endif

    @else
        <div class="text-center py-5">
            <i class="bi bi-bookmark-x fs-1 text-muted mb-3"></i>
            <h4 class="text-muted">Aún no tienes restaurantes favoritos.</h4>
            <p class="text-muted">¡Explora y encuentra tus próximos lugares preferidos!</p>
            <a href="{{ route('restaurants.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-compass me-1"></i> Descubrir Restaurantes
            </a>
        </div>
    @endif
</div>
@endsection

@pushOnce('scripts')
    <script src="{{ asset('js/favorites.js') }}"></script>
@endPushOnce