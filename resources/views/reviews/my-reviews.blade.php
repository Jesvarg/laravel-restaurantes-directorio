@extends('layouts.app')
@section('title', 'Mis Reseñas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Mis Reseñas</h2>
</div>

@if($reviews->count() > 0)
    <div class="row">
        @foreach ($reviews as $review)
            <div class="col-md-12">
                <div class="card review-card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <a href="{{ route('restaurants.show', $review->restaurant) }}" class="text-decoration-none">{{ $review->restaurant->name }}</a>
                        </h5>
                        <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rating text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            <span class="fw-bold">{{ $review->rating }}/5</span>
                        </div>
                        <p class="card-text">{{ $review->comment }}</p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end border-top-0 pt-0">
                        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-outline-warning me-2" title="Editar reseña">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline delete-review-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar reseña">
                                <i class="bi bi-trash"></i> Eliminar
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
    <div class="text-center p-5 border rounded bg-light">
        <i class="bi bi-chat-square-text-fill fs-1 text-primary"></i>
        <h4 class="mt-3">Aún no has escrito ninguna reseña</h4>
        <p class="text-muted">¿Por qué no buscas un restaurante y compartes tu opinión?</p>
        <a href="{{ route('restaurants.index') }}" class="btn btn-primary mt-2">
            <i class="bi bi-search"></i> Explorar restaurantes
        </a>
    </div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-review-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('¿Estás seguro de que quieres eliminar esta reseña? Esta acción no se puede deshacer.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection