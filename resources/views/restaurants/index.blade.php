@extends('layouts.app')
@section('title', 'Restaurantes')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/restaurants.css') }}">
<link rel="stylesheet" href="{{ asset('css/filters.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-shop me-2"></i>Explorar Restaurantes</h2>
        @auth
            <a href="{{ route('restaurants.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill me-1"></i> Nuevo Restaurante
            </a>
        @endauth
    </div>

    <!-- Filtros y búsqueda -->
    <div class="card mb-4 filter-card">
        <div class="card-body">
            <form method="GET" action="{{ route('restaurants.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="search" class="form-label">Buscar por nombre:</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Ej: La Pizzería" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="category" class="form-label">Filtrar por categoría:</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel-fill me-1"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($restaurants->isEmpty())
        <div class="alert alert-info text-center mt-4">
            <i class="bi bi-info-circle-fill me-2"></i> No se encontraron restaurantes con los filtros aplicados.
            @if(request()->has('search') || request()->has('category'))
                <a href="{{ route('restaurants.index') }}" class="alert-link">Mostrar todos los restaurantes</a>.
            @endif
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-3">
            @foreach ($restaurants as $restaurant)
                <div class="col">
                    @include('restaurants.partials.card', ['restaurant' => $restaurant, 'favoriteRestaurantIds' => $favoriteRestaurantIds])
                </div>
            @endforeach
        </div>
    @endif

    <!-- Paginación -->
    @if ($restaurants->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $restaurants->appends(request()->query())->links() }} 
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/restaurants.js') }}"></script>
<script src="{{ asset('js/favorites.js') }}"></script>
@endsection

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('restaurants.index') }}">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse ($restaurants as $restaurant)
            <div class="col-md-4 mb-4">
                <div class="card h-100 restaurant-card">
                    @if($restaurant->photos->count() > 0)
                        <img src="{{ $restaurant->photos->first()->url }}" class="card-img-top restaurant-image" alt="{{ $restaurant->name }}">
                    @else
                        <div class="card-img-top restaurant-image bg-light d-flex align-items-center justify-content-center">
                            <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
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
                                @for($i = 0; $i < $fullStars; $i++) <i class="bi bi-star-fill text-warning"></i> @endfor
                                @if($halfStar) <i class="bi bi-star-half text-warning"></i> @endif
                                @for($i = 0; $i < $emptyStars; $i++) <i class="bi bi-star text-warning"></i> @endfor
                            </div>
                            <small class="text-muted">
                                ({{ $restaurant->reviews->count() }} {{ Str::plural('reseña', $restaurant->reviews->count()) }})
                            </small>
                        </div>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Ver detalles
                                </a>
                                @auth
                                    <div>
                                        @php
                                            $isFavorite = in_array($restaurant->id, $favoriteRestaurantIds);
                                        @endphp
                                        <button class="btn btn-sm btn-outline-danger favorite-btn" data-id="{{ $restaurant->id }}" data-favorited="{{ $isFavorite ? 'true' : 'false' }}">
                                            <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                        </button>

                                        @can('update', $restaurant)
                                            <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('restaurants.destroy', $restaurant) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle-fill"></i> No se encontraron restaurantes con los filtros aplicados.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $restaurants->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Confirmación para eliminar restaurantes
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirm('¿Estás seguro de que quieres eliminar este restaurante?')) {
                e.preventDefault();
            }
        });
    });

    // Lógica para el botón de favoritos con AJAX
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function () {
            const restaurantId = this.dataset.id;
            const isFavorited = this.dataset.favorited === 'true';
            const icon = this.querySelector('i');

            const url = `/restaurants/${restaurantId}/favorite`;
            const method = isFavorited ? 'DELETE' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    if (isFavorited) {
                        this.dataset.favorited = 'false';
                        icon.classList.remove('bi-heart-fill');
                        icon.classList.add('bi-heart');
                        toastr.success('Restaurante eliminado de favoritos.');
                    } else {
                        this.dataset.favorited = 'true';
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill');
                        toastr.success('Restaurante añadido a favoritos.');
                    }
                } else {
                    toastr.error(data.message || 'Ha ocurrido un error.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Ha ocurrido un error de red. Por favor, inicia sesión para añadir favoritos.');
            });
        });
    });
});
</script>
@endsection