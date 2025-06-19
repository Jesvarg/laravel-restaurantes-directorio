@extends('layouts.app')
@section('title', 'Editar Categoría')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm form-card">
                <div class="card-header form-card-header-warning text-dark"> <!-- Usar una clase específica para encabezados de edición si se desea un color diferente consistentemente -->
                    <h1 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Categoría: {{ $category->name }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('categories.update', $category) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label"><i class="bi bi-tag me-2"></i>Nombre de la categoría:</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $category->name) }}" required autofocus placeholder="Ej: Comida Italiana">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary me-md-2 d-flex align-items-center">
                                <i class="bi bi-x-circle-fill me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-1"></i> Actualizar Categoría
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection