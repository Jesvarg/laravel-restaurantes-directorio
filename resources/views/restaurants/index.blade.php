@extends('layouts.app')
@section('title', 'Restaurantes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-shop"></i> Restaurantes</h2>
    <a href="{{ route('restaurants.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Restaurante
    </a>
</div>

<div class="row">
    @forelse ($restaurants as $restaurant)
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
                            ({{ $restaurant->reviews->count() }} {{ $restaurant->reviews->count() == 1 ? 'reseña' : 'reseñas' }})
                        </small>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Ver detalles
                        </a>
                        
                        @auth
                            <div>
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
                                @else
                                    <form action="{{ route('restaurants.favorite', $restaurant) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No hay restaurantes disponibles. ¡Sé el primero en añadir uno!
            </div>
        </div>
    @endforelse
</div>

@endsection

@section('scripts')
<script>
    // Confirmación para eliminar restaurantes
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que quieres eliminar este restaurante?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection