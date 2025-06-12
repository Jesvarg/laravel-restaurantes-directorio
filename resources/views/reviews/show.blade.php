@extends('layouts.app')
@section('title', 'Reseña de ' . $review->restaurant->name)

@section('content')
<h2>Reseña de {{ $review->restaurant->name }}</h2>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
        <div>
            <strong>Puntuación:</strong> {{ $review->rating }}/5
        </div>
        <div>
            <small>{{ $review->created_at->format('d/m/Y H:i') }}</small>
        </div>
    </div>
    <div class="card-body">
        <p class="card-text">{{ $review->comment }}</p>
        <p class="card-text"><small class="text-muted">Por: {{ $review->user->name }}</small></p>
    </div>
    <div class="card-footer">
        <a href="{{ route('restaurants.show', $review->restaurant) }}" class="btn btn-primary">Ver restaurante</a>
        
        @if(Auth::id() == $review->user_id)
            <a href="{{ route('reviews.edit', $review) }}" class="btn btn-warning">Editar</a>
            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
            </form>
        @endif
    </div>
</div>

<a href="{{ route('reviews.index') }}" class="btn btn-secondary">Volver a reseñas</a>
@endsection