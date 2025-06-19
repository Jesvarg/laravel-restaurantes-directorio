@extends('layouts.app')
@section('title', 'Restaurantes de ' . $category->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/restaurants.css') }}">
    {{-- Si se crea un categories-show.css específico, añadirlo aquí --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/categories-show.css') }}"> --}}
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0"><i class="bi bi-tag-fill me-2"></i> {{ $category->name }}</h1>
            <p class="text-muted mb-0">Explora los restaurantes de esta categoría.</p>
        </div>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
            <i class="bi bi-arrow-left-circle-fill me-1"></i> Volver a Categorías
        </a>
    </div>

    @if($restaurants->isEmpty())
        <div class="alert alert-light text-center border-0 shadow-sm py-5 mt-4">
            <i class="bi bi-compass fs-1 text-muted mb-3"></i>
            <h4 class="text-muted">No hay restaurantes en esta categoría todavía.</h4>
            <p class="text-muted">Pronto podrían añadirse nuevos lugares. ¡Vuelve a consultar más tarde!</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"> {{-- Eliminado mt-3 para consistencia, el espaciado lo da el contenedor principal --}}
            @foreach ($restaurants as $restaurant)
                <div class="col">
                    @include('restaurants.partials.card', ['restaurant' => $restaurant, 'favoriteRestaurantIds' => $favoriteRestaurantIds ?? []])
                </div>
            @endforeach
        </div>
    @endif

    <!-- Paginación -->
    @if ($restaurants->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $restaurants->links() }}
        </div>
    @endif
</div>
@endsection

@pushOnce('scripts')
    <script src="{{ asset('js/favorites.js') }}"></script>
@endPushOnce