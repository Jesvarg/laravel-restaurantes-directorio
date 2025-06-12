@extends('layouts.app')
@section('title', 'Crear Categoría')

@section('content')
<h2>Crear Nueva Categoría</h2>

<form method="POST" action="{{ route('categories.store') }}">
    @csrf
    
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
               value="{{ old('name') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection