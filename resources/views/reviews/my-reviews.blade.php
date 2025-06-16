@extends('layouts.app')
@section('title', 'Mis Reseñas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reviews.css') }}">
@endpush

@section('content')
<div class="review-card-header">"container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-person-lines-fill me-2"></i>Mis Reseñas</h1>
        <a href="{{ route('reviews.create') }}" class="btn btn-primary d-flex align-items-center"><i class="bi bi-pencil-square me-1"></i>Escribir nueva reseña</a>
    </div>

@if($reviews->count() > 0)
    <div class="row">
        @foreach ($reviews as $review)
            <div class="col-md-12 col-lg-8 mx-auto">
                <div class="card review-card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fs-6 fw-bold">
                            <a href="{{ route('restaurants.show', $review->restaurant) }}" class="text-decoration-none">{{ $review->restaurant->name }}</a>
                        </h5>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rating-stars text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            <span class="fw-bold">{{ $review->rating }}</span>
                        </div>
                        <p class="card-text mt-1">{{ $review->comment }}</p>
                    </div>
                    <div class="review-card-actions d-flex justify-content-end pt-2">
                        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-link text-warning p-0 me-3" title="Editar reseña">
                            <i class="bi bi-pencil-fill"></i> Editar
                        </a>
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline delete-review-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-0" title="Eliminar reseña">
                                <i class="bi bi-trash3-fill"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
@else
    <div class="alert alert-light text-center mt-4" role="alert">
        <h4 class="alert-heading"><i class="bi bi-emoji-neutral me-2"></i>¡Aún no hay nada por aquí!</h4>
        <p>Parece que todavía no has compartido ninguna reseña. Tus opiniones son valiosas para otros usuarios.</p>
        <hr>
        <p class="mb-0">Anímate a <a href="{{ route('reviews.create') }}" class="alert-link">escribir tu primera reseña</a> o <a href="{{ route('restaurants.index') }}" class="alert-link">descubre restaurantes</a> para inspirarte.</p>
        <a href="{{ route('restaurants.index') }}" class="btn btn-primary mt-2">
            <i class="bi bi-search"></i> Explorar restaurantes
        </a>
    </div>
@endif
</div>

@pushOnce('scripts')
    <script src="{{ asset('js/reviews.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endPushOnce

@endsection