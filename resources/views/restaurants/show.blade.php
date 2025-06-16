@extends('layouts.app')

@section('title', $restaurant->name)

@push('styles')
<link href="{{ asset('css/restaurants.css') }}" rel="stylesheet">
<link href="{{ asset('css/reviews.css') }}" rel="stylesheet">
{{-- Podríamos añadir un CSS específico para la galería si se vuelve más compleja --}}
@endpush

@section('content')
<div class="container mt-4">
    <div class="mb-3">
        <a href="{{ url()->previous(route('restaurants.index')) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="restaurant-show-header" style="background-image: url('{{ $restaurant->photos->count() > 0 ? Storage::url($restaurant->photos->first()->url) : asset('images/placeholder-restaurant-header.jpg') }}');">
        <div class="restaurant-show-header-overlay">
            <div class="container">
                <h1 class="restaurant-show-title">{{ $restaurant->name }}</h1>
                <div class="d-flex align-items-center mb-2">
                    <div class="rating-stars me-2 text-warning">
                        @php
                            $avgRating = $restaurant->averageRating();
                            $fullStars = floor($avgRating);
                            $halfStar = $avgRating - $fullStars >= 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        @endphp
                        @for($i = 0; $i < $fullStars; $i++) <i class="bi bi-star-fill"></i> @endfor
                        @if($halfStar) <i class="bi bi-star-half"></i> @endif
                        @for($i = 0; $i < $emptyStars; $i++) <i class="bi bi-star"></i> @endfor
                    </div>
                    <span class="text-white fw-light">
                        {{ number_format($avgRating, 1) }} ({{ $restaurant->reviews_count }} {{ Str::plural('reseña', $restaurant->reviews_count) }})
                    </span>
                </div>
                <div>
                    @forelse ($restaurant->categories as $category)
                        <a href="{{ route('categories.show', $category) }}" class="badge bg-light text-dark text-decoration-none me-1 rounded-pill shadow-sm">{{ $category->name }}</a>
                    @empty
                        <span class="badge bg-secondary rounded-pill shadow-sm">Sin categorías</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            {{-- Información del Restaurante --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Información del Restaurante</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="bi bi-geo-alt-fill text-primary me-2"></i><strong>Dirección:</strong> {{ $restaurant->address }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="bi bi-telephone-fill text-primary me-2"></i><strong>Teléfono:</strong> {{ $restaurant->phone ?? 'No disponible' }}</p>
                        </div>
                    </div>
                    
                    <h5 class="mt-3"><i class="bi bi-blockquote-left text-primary me-2"></i>Descripción</h5>
                    <p class="text-muted">{{ $restaurant->description ?: 'No hay descripción disponible.' }}</p>
                    
                    <div class="mt-4 d-flex flex-wrap gap-2 justify-content-start align-items-center">
                        @auth
                            @can('update', $restaurant)
                                <a href="{{ route('restaurants.edit', $restaurant) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Editar Restaurante
                                </a>
                            @endcan
                            @can('delete', $restaurant)
                                <form action="{{ route('restaurants.destroy', $restaurant) }}" method="POST" class="d-inline delete-restaurant-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash-fill"></i> Eliminar Restaurante
                                    </button>
                                </form>
                            @endcan
                            
                            @if(Auth::id() != $restaurant->user_id)
                                @php $isFavorited = Auth::user()->favorites->contains($restaurant->id); @endphp
                                <button class="btn btn-sm {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }} favorite-button" 
                                        data-restaurant-id="{{ $restaurant->id }}" 
                                        data-url="{{ route('restaurants.toggleFavorite', $restaurant) }}">
                                    <i class="bi {{ $isFavorited ? 'bi-heart-fill' : 'bi-heart' }}"></i> 
                                    <span class="button-text">{{ $isFavorited ? 'Quitar de Favoritos' : 'Añadir a Favoritos' }}</span>
                                </button>
                            @endif
                        @endauth
                        <a href="{{ route('reviews.create', ['restaurant' => $restaurant]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-chat-square-text-fill"></i> Escribir Reseña
                        </a>
                    </div>
                </div>
            </div>

            {{-- Galería de Fotos --}}
            @if($restaurant->photos->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3"><i class="bi bi-images text-primary me-2"></i>Galería de Fotos</h4>
                    <div class="row g-2 restaurant-gallery">
                        @foreach($restaurant->photos as $photo)
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ Storage::url($photo->url) }}" data-bs-toggle="modal" data-bs-target="#photoModal-{{ $photo->id }}">
                                <img src="{{ Storage::url($photo->url) }}" alt="Foto de {{ $restaurant->name }}" class="img-fluid rounded shadow-sm gallery-image">
                            </a>
                            <!-- Modal -->
                            <div class="modal fade" id="photoModal-{{ $photo->id }}" tabindex="-1" aria-labelledby="photoModalLabel-{{ $photo->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="photoModalLabel-{{ $photo->id }}">{{ $restaurant->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-0">
                                            <img src="{{ Storage::url($photo->url) }}" alt="Foto de {{ $restaurant->name }}" class="img-fluid rounded">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Sección de Reseñas --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0"><i class="bi bi-chat-dots-fill text-primary me-2"></i>Reseñas ({{ $restaurant->reviews()->count() }})</h4>
                        @auth
                            <a href="{{ route('reviews.create', ['restaurant' => $restaurant]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-circle-fill"></i> Añadir Reseña
                            </a>
                        @endauth
                    </div>

                    @forelse ($restaurant->reviews()->paginate(5) as $review) {{-- Paginación aquí --}}
                        <div class="review-card mb-3">
                            <div class="review-card-header">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $review->user->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $review->user->name }}" class="rounded-circle me-2" width="30" height="30">
                                    <span class="fw-bold">{{ $review->user->name }}</span>
                                </div>
                                <div class="rating-stars text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                    <span class="ms-1 text-muted">({{ $review->rating }}/5)</span>
                                </div>
                            </div>
                            <div class="review-card-body">
                                <p class="mb-1">{{ $review->comment }}</p>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            @canany(['update', 'delete'], $review)
                            <div class="review-card-actions">
                                @can('update', $review)
                                    <a href="{{ route('reviews.edit', $review) }}" class="btn btn-link btn-sm text-warning p-0 me-2" title="Editar reseña">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                @endcan
                                @can('delete', $review)
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline delete-review-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-sm text-danger p-0" title="Eliminar reseña">
                                            <i class="bi bi-trash-fill"></i> Eliminar
                                        </button>
                                    </form>
                                @endcan
                            </div>
                            @endcanany
                        </div>
                    @empty
                        <div class="alert alert-light text-center" role="alert">
                            <i class="bi bi-chat-square-dots me-2"></i> Todavía no hay reseñas para este restaurante. ¡Sé el primero en compartir tu opinión!
                        </div>
                    @endforelse
                    
                    @if($restaurant->reviews()->paginate(5)->hasPages())
                        <div class="mt-3">
                            {{ $restaurant->reviews()->paginate(5)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Barra Lateral --}}
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-building text-primary me-2"></i>Detalles Adicionales</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted"><i class="bi bi-person-fill me-2"></i>Propietario</span>
                            <strong>{{ $restaurant->user->name }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted"><i class="bi bi-calendar-check-fill me-2"></i>Publicado</span>
                            <strong>{{ $restaurant->created_at->isoFormat('LL') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted"><i class="bi bi-pencil-fill me-2"></i>Última Actualización</span>
                            <strong>{{ $restaurant->updated_at->isoFormat('LL') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted"><i class="bi bi-images me-2"></i>Fotos</span>
                            <strong>{{ $restaurant->photos->count() }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@pushOnce('scripts')
<script src="{{ asset('js/favorites.js') }}"></script>
<script src="{{ asset('js/restaurants.js') }}"></script> {{-- Para confirmación de borrado de restaurante --}}
<script src="{{ asset('js/reviews.js') }}"></script> {{-- Para confirmación de borrado de reseña --}}
@endPushOnce