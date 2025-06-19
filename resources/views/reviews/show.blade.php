@extends('layouts.app')
@section('title', 'Reseña de ' . $review->restaurant->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reviews.css') }}">
@endpush

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-chat-left-text-fill me-2"></i>Detalle de la Reseña</h1>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary d-flex align-items-center"><i class="bi bi-arrow-left-circle-fill me-1"></i>Volver</a>
    </div>

<div class="card review-card shadow-sm mb-4">
    <div class="review-card-header">
        <div>
            <span class="fw-bold ms-1">{{ $review->rating }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <small class="text-muted">{{ $review->created_at->diffForHumans() }} por {{ $review->user->name }}</small>
        </div>
    </div>
    <div class="card-body">
        <p class="card-text mt-3">{{ $review->comment }}</p>
    </div>
    <div class="review-card-actions d-flex justify-content-end pt-2">
        <a href="{{ route('restaurants.show', $review->restaurant) }}" class="btn btn-outline-primary d-flex align-items-center me-2"><i class="bi bi-eye-fill me-1"></i>Ver Restaurante</a>
        @canany(['update', 'delete'], $review)
            @can('update', $review)
            <a href="{{ route('reviews.edit', $review) }}" class="btn btn-outline-warning d-flex align-items-center me-2"><i class="bi bi-pencil-fill me-1"></i>Editar</a>
            @endcan
            @can('delete', $review)
            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline delete-review-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" class="btn btn-outline-danger d-flex align-items-center"><i class="bi bi-trash3-fill me-1"></i>Eliminar</button>
            </form>
            @endcan
        @endcanany
    </div>
</div>
</div>

@pushOnce('scripts')
    <script src="{{ asset('js/reviews.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endPushOnce

@endsection