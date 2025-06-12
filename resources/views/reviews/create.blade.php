@extends('layouts.app')
@section('title', 'Escribir Reseña')

@section('content')
<h2>Escribir Reseña</h2>

<form method="POST" action="{{ route('reviews.store') }}">
    @csrf
    
    @if(isset($restaurant))
        <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
        <div class="mb-3">
            <h4>Restaurante: {{ $restaurant->name }}</h4>
        </div>
    @else
        <div class="mb-3">
            <label for="restaurant_id" class="form-label">Restaurante</label>
            <select name="restaurant_id" id="restaurant_id" class="form-select @error('restaurant_id') is-invalid @enderror" required>
                <option value="">Selecciona un restaurante</option>
                @foreach($restaurants as $restaurant)
                    <option value="{{ $restaurant->id }}" {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                        {{ $restaurant->name }}
                    </option>
                @endforeach
            </select>
            @error('restaurant_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif
    
    <div class="mb-3">
        <label for="rating" class="form-label">Puntuación</label>
        <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror" required>
            <option value="">Selecciona una puntuación</option>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'estrella' : 'estrellas' }}</option>
            @endfor
        </select>
        @error('rating')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="comment" class="form-label">Comentario</label>
        <textarea name="comment" id="comment" rows="4" class="form-control @error('comment') is-invalid @enderror">{{ old('comment') }}</textarea>
        @error('comment')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Publicar reseña</button>
    <a href="{{ isset($restaurant) ? route('restaurants.show', $restaurant) : route('reviews.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection