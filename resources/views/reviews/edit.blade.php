@extends('layouts.app')
@section('title', 'Editar Reseña')

@section('content')
<h2>Editar Reseña</h2>

<form method="POST" action="{{ route('reviews.update', $review) }}">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
        <h4>Restaurante: {{ $review->restaurant->name }}</h4>
    </div>
    
    <div class="mb-3">
        <label for="rating" class="form-label">Puntuación</label>
        <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror" required>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'selected' : '' }}>
                    {{ $i }} {{ $i == 1 ? 'estrella' : 'estrellas' }}
                </option>
            @endfor
        </select>
        @error('rating')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="comment" class="form-label">Comentario</label>
        <textarea name="comment" id="comment" rows="4" class="form-control @error('comment') is-invalid @enderror">{{ old('comment', $review->comment) }}</textarea>
        @error('comment')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Actualizar reseña</button>
    <a href="{{ route('restaurants.show', $review->restaurant) }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection