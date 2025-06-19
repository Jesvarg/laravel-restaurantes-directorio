@extends('layouts.app')
@section('title', 'Editar Reseña')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reviews.css') }}"> 
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm form-card">
                <div class="card-header form-card-header-warning text-dark">
                    <h1 class="mb-0 h5"><i class="bi bi-pencil-square me-2"></i>Editar reseña para: <strong>{{ $review->restaurant->name }}</strong></h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reviews.update', $review) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="rating" class="form-label"><i class="bi bi-star-fill me-1 text-warning"></i>Puntuación <span class="text-danger">*</span></label>
                            <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                <option value="" disabled {{ old('rating', $review->rating) ? '' : 'selected' }}>-- Selecciona una puntuación --</option>
                                @for($i = 5; $i >= 1; $i--)
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
                            <label for="comment" class="form-label"><i class="bi bi-textarea-t me-1"></i>Comentario</label>
                            <textarea name="comment" id="comment" rows="5" class="form-control @error('comment') is-invalid @enderror" placeholder="Comparte tu experiencia...">{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('restaurants.show', $review->restaurant) }}" class="btn btn-outline-secondary me-md-2 d-flex align-items-center"><i class="bi bi-x-circle-fill me-1"></i>Cancelar</a>
                            <button type="submit" class="btn btn-warning d-flex align-items-center"> <!-- Cambiado a btn-warning para edición -->
                                <i class="bi bi-check-circle-fill me-1"></i> Actualizar reseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection