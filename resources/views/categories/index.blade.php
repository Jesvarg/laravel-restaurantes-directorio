@extends('layouts.app')
@section('title', 'Categorías')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-tags"></i> Categorías</h2>
        @can('create', App\Models\Category::class)
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Categoría
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
                            <span class="badge bg-secondary">{{ $category->restaurants_count }} {{ Str::plural('restaurante', $category->restaurants_count) }}</span>
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Ver Restaurantes
                            </a>
                            @can('update', $category)
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endcan
                            @can('delete', $category)
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle-fill"></i> No hay categorías disponibles.
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Confirmación para eliminar categorías
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirm('¿Estás seguro de que quieres eliminar esta categoría? Se desvinculará de todos los restaurantes, pero no se eliminarán los restaurantes.')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection