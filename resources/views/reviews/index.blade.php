@extends('layouts.app')
@section('title', 'Reseñas')

@section('content')
<h2>Reseñas de Restaurantes</h2>

<div class="mb-3">
    <a href="{{ route('reviews.create') }}" class="btn btn-primary">Escribir reseña</a>
    <a href="{{ route('reviews.my') }}" class="btn btn-outline-primary">Mis reseñas</a>
</div>

@if($reviews->count() > 0)
    <div class="list-group">
        @foreach ($reviews as $review)
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-1">
                        <a href="{{ route('restaurants.show', $review->restaurant) }}">{{ $review->restaurant->name }}</a>
                        <span class="badge bg-primary">{{ $review->rating }}/5</span>
                    </h5>
                    <small>{{ $review->created_at->format('d/m/Y') }}</small>
                </div>
                <p class="mb-1">{{ $review->comment }}</p>
                <small>Por: {{ $review->user->name }}</small>
                
                @if(Auth::id() == $review->user_id)
                    <div class="mt-2">
                        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
@else
    <div class="alert alert-info">
        No hay reseñas disponibles.
    </div>
@endif
@endsection