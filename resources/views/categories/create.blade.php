@extends('layouts.app')
@section('title', 'Crear Nueva Categoría')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm form-card">
                <div class="card-header form-card-header">
                    <h1 class="mb-0"><i class="bi bi-plus-circle-fill me-2"></i>Crear Nueva Categoría</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label"><i class="bi bi-tag me-2"></i>Nombre de la categoría:</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required autofocus placeholder="Ej: Comida Mexicana">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary me-md-2 d-flex align-items-center">
                                <i class="bi bi-x-circle-fill me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-1"></i> Guardar Categoría
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection