@extends('layouts.app')
@section('title', 'Reseñas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reviews.css') }}">
@endpush

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-chat-square-text-fill me-2"></i>Reseñas de Restaurantes</h1>
    </div>

<div class="mb-4 d-flex justify-content-start gap-2">
    <a href="{{ route('reviews.create') }}" class="btn btn-primary d-flex align-items-center"><i class="bi bi-pencil-square me-1"></i>Escribir reseña</a>
    <a href="{{ route('reviews.my') }}" class="btn btn-outline-secondary d-flex align-items-center"><i class="bi bi-person-lines-fill me-1"></i>Mis reseñas</a>
</div>

@if($reviews->count() > 0)
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach ($reviews as $review)
            <div class="col">
                <div class="card review-card h-100 shadow-sm"> 
                    <div class="card-body">
                <div class="review-card-header">
                        <div class="d-flex align-items-center">
                            <img src="{{ $review->user->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $review->user->name }}" class="rounded-circle me-2" width="40" height="40">
                            <div>
                                <h5 class="card-title mb-0 fs-6 fw-bold">
                        <a href="{{ route('restaurants.show', $review->restaurant) }}">{{ $review->restaurant->name }}</a>
                        <span class="badge bg-primary ms-2">{{ $review->rating }} <i class="bi bi-star-fill"></i></span>
                    </h5>
                    </div>
                        </div>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                </div>
                </div>
                    <p class="card-text mt-2">{{ $review->comment }}</p>
                    <small class="text-muted">Restaurante: <a href="{{ route('restaurants.show', $review->restaurant) }}" class="text-decoration-none">{{ $review->restaurant->name }}</a></small>
                
                @canany(['update', 'delete'], $review)
                    <div class="review-card-actions mt-3 pt-2">"mt-2">
                        @can('update', $review)
                        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-link text-warning p-0 me-2"><i class="bi bi-pencil-fill"></i> Editar</a>
                        @endcan
                        @can('delete', $review)
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline delete-review-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" class="btn btn-link text-danger p-0"><i class="bi bi-trash3-fill"></i> Eliminar</button>
                        </form>
                        @endcan
                    </div>
                @endcanany
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
@else
    <div class="alert alert-light text-center" role="alert">
        <h4 class="alert-heading"><i class="bi bi-emoji-frown me-2"></i>¡Vaya!</h4>
        <p>Parece que aún no hay reseñas disponibles. Sé el primero en compartir tu opinión sobre tus restaurantes favoritos.</p>
        <hr>
        <p class="mb-0">Puedes <a href="{{ route('reviews.create') }}" class="alert-link">escribir una reseña ahora</a> o <a href="{{ route('restaurants.index') }}" class="alert-link">explorar restaurantes</a>.</p>
    </div>
@endif
</div>
</div>

@pushOnce('scripts')
    <script src="{{ asset('js/reviews.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endPushOnce

@endsection