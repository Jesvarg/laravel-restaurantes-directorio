@extends('layouts.app')
@section('title', 'Escribir Reseña')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reviews.css') }}"> {{-- Para estilos específicos de estrellas/reseñas --}}
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm form-card">
                <div class="card-header form-card-header">
                    <h1 class="mb-0 h5"><i class="bi bi-chat-left-text-fill me-2"></i>Escribir reseña para: <strong>{{ $restaurant->name }}</strong></h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reviews.store', $restaurant) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="rating" class="form-label"><i class="bi bi-star-fill me-1 text-warning"></i>Puntuación <span class="text-danger">*</span></label>
                            <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                <option value="" disabled {{ old('rating') ? '' : 'selected' }}>-- Selecciona una puntuación --</option>
                                <option value="" disabled selected>-- Selecciona una puntuación --</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i == 1 ? 'estrella' : 'estrellas' }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label"><i class="bi bi-textarea-t me-1"></i>Comentario</label>
                            <textarea name="comment" id="comment" rows="5" class="form-control @error('comment') is-invalid @enderror" placeholder="Comparte tu experiencia...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-outline-secondary me-md-2 d-flex align-items-center"><i class="bi bi-x-circle-fill me-1"></i>Cancelar</a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center">
                                <i class="bi bi-send-fill me-1"></i> Publicar reseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection