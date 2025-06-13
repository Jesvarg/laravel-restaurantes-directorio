@extends('layouts.app')
@section('title', 'Restaurantes de ' . $category->name)

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="bi bi-tag"></i> {{ $category->name }}</h2>
            <p class="text-muted mb-0">Explora los restaurantes de esta categoría.</p>
        </div>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver a Categorías
        </a>
    </div>

    <div class="row">
        @forelse ($restaurants as $restaurant)
            <div class="col-md-4 mb-4">
                @include('restaurants.partials.card', ['restaurant' => $restaurant])
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle-fill"></i> No hay restaurantes en esta categoría todavía.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $restaurants->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const restaurantId = this.dataset.id;
            const isFavorited = this.dataset.favorited === 'true';
            const url = isFavorited ? `/restaurants/${restaurantId}/remove-favorite` : `/restaurants/${restaurantId}/add-favorite`;
            const method = 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.dataset.favorited = !isFavorited;
                    const icon = this.querySelector('i');
                    icon.classList.toggle('bi-heart');
                    icon.classList.toggle('bi-heart-fill');
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message || 'Ha ocurrido un error.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('No se pudo completar la acción.');
            });
        });
    });
});
</script>