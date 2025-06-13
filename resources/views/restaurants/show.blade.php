@extends('layouts.app')
@section('title', $restaurant->name)

@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver a restaurantes
    </a>
</div>

<div class="restaurant-header" style="background-image: url('{{ $restaurant->photos->count() > 0 ? $restaurant->photos->first()->url : 'https://via.placeholder.com/1200x400?text=Sin+imagen' }}')">
    <div class="restaurant-header-overlay">
        <div>
            <h1 class="restaurant-title">{{ $restaurant->name }}</h1>
            <div class="d-flex align-items-center">
                <div class="rating me-2 text-warning">
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
                <span class="text-white">
                    {{ number_format($rating, 1) }} ({{ $restaurant->reviews->count() }} {{ $restaurant->reviews->count() == 1 ? 'reseña' : 'reseñas' }})
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Información</h4>
                <div class="mb-3">
                    <p><i class="bi bi-geo-alt text-primary me-2"></i> {{ $restaurant->address }}</p>
                    <p><i class="bi bi-telephone text-primary me-2"></i> {{ $restaurant->phone ?? 'No disponible' }}</p>
                </div>
                
                <h5>Descripción</h5>
                <p>{{ $restaurant->description ?: 'No hay descripción disponible.' }}</p>
                
                <h5>Categorías</h5>
                <div class="mb-3">
                    @forelse ($restaurant->categories as $category)
                        <a href="{{ route('categories.restaurants', $category) }}" class="badge bg-info text-decoration-none me-1">
                            {{ $category->name }}
                        </a>
                    @empty
                        <span class="text-muted">No hay categorías asignadas</span>
                    @endforelse
                </div>
                
                <div class="d-flex flex-wrap gap-2 mt-4">
                    @auth
                        @if(Auth::id() == $restaurant->user_id)
                            <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form action="{{ route('restaurants.destroy', $restaurant) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        @else
                            <form action="{{ route('restaurants.favorite', $restaurant) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-heart"></i> Añadir a favoritos
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('reviews.create', $restaurant) }}" class="btn btn-primary">
                            <i class="bi bi-star"></i> Escribir reseña
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        @if($restaurant->photos->count() > 0)
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Galería de fotos</h4>
                    <div class="photo-gallery">
                        @foreach($restaurant->photos as $photo)
                            <div class="gallery-item">
                                <img src="{{ $photo->url }}" alt="{{ $restaurant->name }}" class="img-fluid" data-bs-toggle="modal" data-bs-target="#photoModal" data-src="{{ $photo->url }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Reseñas</h4>
                    @auth
                        <a href="{{ route('reviews.create', $restaurant) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Añadir reseña
                        </a>
                    @endauth
                </div>
                
                @if($restaurant->reviews->count() > 0)
                    <div class="list-group">
                        @foreach($restaurant->reviews as $review)
                            <div class="list-group-item review-card mb-3 border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="rating me-2 text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="fw-bold">{{ $review->rating }}/5</span>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                </div>
                                <p class="my-2">{{ $review->comment }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Por: {{ $review->user->name }}</small>
                                    
                                    <div class="review-actions">
                                        @can('update', $review)
                                            <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-warning" title="Editar reseña">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $review)
                                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline delete-review-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar reseña">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No hay reseñas para este restaurante. ¡Sé el primero en opinar!
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4 sticky-top" style="top: 20px; z-index: 1;">
            <div class="card-body">
                <h5 class="card-title">Información adicional</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person text-primary me-2"></i> Propietario</span>
                        <span>{{ $restaurant->user->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-calendar text-primary me-2"></i> Creado</span>
                        <span>{{ $restaurant->created_at->format('d/m/Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-star text-primary me-2"></i> Reseñas</span>
                        <span>{{ $restaurant->reviews->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-star-half text-primary me-2"></i> Puntuación media</span>
                        <span class="badge bg-primary rounded-pill">
                            {{ $restaurant->reviews->count() > 0 ? number_format($restaurant->reviews->avg('rating'), 1) : 'N/A' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver fotos en grande -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="" id="modalImage" class="img-fluid w-100">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Confirmación para eliminar restaurante
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que quieres eliminar este restaurante?')) {
                e.preventDefault();
            }
        });
    });
    
    // Confirmación para eliminar reseñas
    document.querySelectorAll('.delete-review-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que quieres eliminar esta reseña?')) {
                e.preventDefault();
            }
        });
    });
    
    // Modal para ver fotos en grande
    document.querySelectorAll('.gallery-item img').forEach(img => {
        img.addEventListener('click', function() {
            document.getElementById('modalImage').src = this.getAttribute('data-src');
        });
    });
</script>
@endsection