@extends('layouts.app')
@section('title', 'Categorías')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/categories.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><i class="bi bi-tags-fill me-2"></i>Categorías</h1>
        @can('create', App\Models\Category::class)
            <a href="{{ route('categories.create') }}" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-plus-circle-fill me-1"></i> Nueva Categoría
            </a>
        @endcan
    </div>

    <div class="row">
        @forelse ($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm category-card">
                    <div class="card-body text-center d-flex flex-column">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text text-muted">
                            <span class="badge bg-light text-dark rounded-pill"><i class="bi bi-building me-1"></i>{{ $category->restaurants_count }} {{ Str::plural('restaurante', $category->restaurants_count) }}</span>"badge bg-secondary">{{ $category->restaurants_count }} {{ Str::plural('restaurante', $category->restaurants_count) }}</span>
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-eye-fill"></i> Ver Restaurantes
                            </a>
                            @can('update', $category)
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                            @endcan
                            @can('delete', $category)
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light text-center border-0 shadow-sm py-4">
                    <i class="bi bi-tags fs-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No hay categorías disponibles.</h4>
                    @can('create', App\Models\Category::class)
                        ¡Sé el primero en <a href="{{ route('categories.create') }}" class="alert-link">crear una</a>!
                    @endcan
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection

@pushOnce('scripts')
    <script src="{{ asset('js/categories.js') }}"></script>
@endPushOnce